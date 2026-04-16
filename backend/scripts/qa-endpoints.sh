#!/usr/bin/env bash

set -euo pipefail

API_BASE_URL="${API_BASE_URL:-http://127.0.0.1:8000/api/v1}"

CASHIER_EMAIL="${QA_CASHIER_EMAIL:-cashier@vanaila.test}"
CASHIER_PASSWORD="${QA_CASHIER_PASSWORD:-password}"
ADMIN_EMAIL="${QA_ADMIN_EMAIL:-admin@vanaila.test}"
ADMIN_PASSWORD="${QA_ADMIN_PASSWORD:-password}"
SUPERADMIN_EMAIL="${QA_SUPERADMIN_EMAIL:-superadmin@vanaila.test}"
SUPERADMIN_PASSWORD="${QA_SUPERADMIN_PASSWORD:-password}"

if ! command -v curl >/dev/null 2>&1; then
	echo "Missing dependency: curl"
	exit 1
fi

if ! command -v jq >/dev/null 2>&1; then
	echo "Missing dependency: jq"
	exit 1
fi

CHECKS=0
LAST_STATUS=""
LAST_BODY=""
LAST_METHOD=""
LAST_PATH=""
LOGIN_TOKEN=""
LOGIN_USER_ID=""
START_EPOCH="$(date +%s)"

pass() {
	CHECKS=$((CHECKS + 1))
	printf "PASS %02d - %s\n" "$CHECKS" "$1" >&2
}

fail() {
	echo "FAIL - $1" >&2
	echo "Request: ${LAST_METHOD} ${LAST_PATH}" >&2
	echo "Status : ${LAST_STATUS}" >&2
	if [[ -n "${LAST_BODY}" ]]; then
		echo "Body   :" >&2
		echo "${LAST_BODY}" | jq . 2>/dev/null >&2 || echo "${LAST_BODY}" >&2
	fi
	exit 1
}

request() {
	local method="$1"
	local path="$2"
	local token="${3:-}"
	local payload="${4:-}"
	local response_file
	local url="${API_BASE_URL}${path}"

	LAST_METHOD="$method"
	LAST_PATH="$path"

	response_file="$(mktemp)"

	local curl_args=(
		--silent
		--show-error
		--request "$method"
		--header "Accept: application/json"
		--url "$url"
		--output "$response_file"
		--write-out "%{http_code}"
	)

	if [[ -n "$token" ]]; then
		curl_args+=(--header "Authorization: Bearer ${token}")
	fi

	if [[ -n "$payload" ]]; then
		curl_args+=(--header "Content-Type: application/json")
		curl_args+=(--data "$payload")
	fi

	LAST_STATUS="$(curl "${curl_args[@]}")"
	LAST_BODY="$(cat "$response_file")"
	rm -f "$response_file"
}

assert_status() {
	local expected="$1"
	local label="$2"

	if [[ "${LAST_STATUS}" != "${expected}" ]]; then
		fail "${label} (expected status ${expected})"
	fi

	pass "${label}"
}

assert_json() {
	local expression="$1"
	local label="$2"

	if ! echo "${LAST_BODY}" | jq -e "${expression}" >/dev/null 2>&1; then
		fail "${label} (expression failed: ${expression})"
	fi

	pass "${label}"
}

json_value() {
	local expression="$1"
	echo "${LAST_BODY}" | jq -er "${expression}"
}

login() {
	local email="$1"
	local password="$2"
	local expected_role="$3"
	local device_name="$4"
	local payload

	payload="$(jq -nc \
		--arg email "$email" \
		--arg password "$password" \
		--arg device_name "$device_name" \
		'{email: $email, password: $password, device_name: $device_name}')"

	request "POST" "/auth/login" "" "$payload"
	assert_status "200" "Login as ${expected_role}"
	assert_json '.token | type == "string" and (length > 10)' "Received bearer token (${expected_role})"
	assert_json ".user.role == \"${expected_role}\"" "Role check (${expected_role})"
	LOGIN_TOKEN="$(json_value '.token')"
	LOGIN_USER_ID="$(json_value '.user.id')"
}

echo "Running backend endpoint QA against ${API_BASE_URL}"

# Public checks
request "GET" "/health"
assert_status "200" "Health endpoint"
assert_json '.status == "ok"' "Health payload has ok status"

request "GET" "/auth/me"
assert_status "401" "Unauthenticated /auth/me is blocked"

# Cashier flow
login "$CASHIER_EMAIL" "$CASHIER_PASSWORD" "cashier" "qa-cashier"
CASHIER_TOKEN="${LOGIN_TOKEN}"

request "GET" "/auth/me" "$CASHIER_TOKEN"
assert_status "200" "Cashier can access /auth/me"
assert_json '.user.role == "cashier"' "Cashier role returned by /auth/me"

request "GET" "/products?per_page=20" "$CASHIER_TOKEN"
assert_status "200" "Cashier can list products"
assert_json '.data | type == "array" and (length > 0)' "Products list is non-empty"

PRODUCT_ID="$(echo "${LAST_BODY}" | jq -er '.data[] | select(.is_active == true and .stock_quantity > 0) | .id' | head -n1)"
STORE_ID="$(echo "${LAST_BODY}" | jq -er '.data[] | select(.id == '"${PRODUCT_ID}"') | .store_id')"

if [[ -z "${PRODUCT_ID}" || -z "${STORE_ID}" ]]; then
	fail "Could not find active product with stock > 0"
fi
pass "Selected product ${PRODUCT_ID} for order flow"

request "GET" "/products/${PRODUCT_ID}" "$CASHIER_TOKEN"
assert_status "200" "Cashier can view a product"
assert_json ".data.id == ${PRODUCT_ID}" "Product detail matches selected ID"

request "GET" "/orders?per_page=5" "$CASHIER_TOKEN"
assert_status "200" "Cashier can list orders"
assert_json '.data | type == "array"' "Orders list shape is valid"

request "GET" "/cashier/overview" "$CASHIER_TOKEN"
assert_status "200" "Cashier overview endpoint"
assert_json '.data.store_count >= 1' "Cashier overview contains store_count"

ORDER_CREATE_PAYLOAD="$(jq -nc \
	--argjson store_id "${STORE_ID}" \
	--argjson product_id "${PRODUCT_ID}" \
	'{store_id: $store_id, payment_method: "cash", status: "open", items: [{product_id: $product_id, quantity: 1}]}')"

request "POST" "/orders" "$CASHIER_TOKEN" "$ORDER_CREATE_PAYLOAD"
assert_status "201" "Cashier can create order"
assert_json '.data.status == "open"' "New order starts in open status"
ORDER_ID="$(json_value '.data.id')"

request "GET" "/orders/${ORDER_ID}" "$CASHIER_TOKEN"
assert_status "200" "Cashier can view created order"
assert_json ".data.id == ${ORDER_ID}" "Created order detail matches ID"

ORDER_UPDATE_PAYLOAD='{"status":"paid","payment_method":"cash"}'
request "PUT" "/orders/${ORDER_ID}" "$CASHIER_TOKEN" "$ORDER_UPDATE_PAYLOAD"
assert_status "200" "Cashier can update order to paid"
assert_json '.data.status == "paid"' "Order transition to paid is reflected"

request "POST" "/auth/logout" "$CASHIER_TOKEN"
assert_status "204" "Cashier logout"

request "GET" "/auth/me" "$CASHIER_TOKEN"
assert_status "401" "Revoked cashier token cannot access /auth/me"

# Admin flow
login "$ADMIN_EMAIL" "$ADMIN_PASSWORD" "admin" "qa-admin"
ADMIN_TOKEN="${LOGIN_TOKEN}"

request "GET" "/admin/overview" "$ADMIN_TOKEN"
assert_status "200" "Admin overview endpoint"
assert_json '.data.products_active >= 0' "Admin overview contains products_active"

request "GET" "/admin/reports/sales" "$ADMIN_TOKEN"
assert_status "200" "Admin sales report endpoint"
assert_json '.summary.total_orders >= 0' "Admin sales report contains summary"

request "GET" "/admin/reports/products?limit=5" "$ADMIN_TOKEN"
assert_status "200" "Admin products report endpoint"
assert_json '.data | type == "array"' "Admin products report data shape"

NOW_TAG="$(date +%s)"
ADMIN_PRODUCT_PAYLOAD="$(jq -nc \
	--argjson store_id "${STORE_ID}" \
	--arg sku "QA-${NOW_TAG}" \
	--arg name "QA Product ${NOW_TAG}" \
	--arg slug "qa-product-${NOW_TAG}" \
	'{store_id: $store_id, sku: $sku, name: $name, slug: $slug, category: "QA", description: "QA endpoint script product", price_cents: 12345, stock_quantity: 7, is_active: true}')"

request "POST" "/admin/products" "$ADMIN_TOKEN" "$ADMIN_PRODUCT_PAYLOAD"
assert_status "201" "Admin can create product"
ADMIN_PRODUCT_ID="$(json_value '.data.id')"

ADMIN_PRODUCT_UPDATE='{"name":"QA Product Updated","price_cents":13000}'
request "PUT" "/admin/products/${ADMIN_PRODUCT_ID}" "$ADMIN_TOKEN" "$ADMIN_PRODUCT_UPDATE"
assert_status "200" "Admin can update product"
assert_json '.data.name == "QA Product Updated"' "Updated product name is returned"

request "DELETE" "/admin/products/${ADMIN_PRODUCT_ID}" "$ADMIN_TOKEN"
assert_status "200" "Admin can deactivate product"
assert_json '.message == "Product deactivated."' "Admin product deactivation message"

request "GET" "/superadmin/overview" "$ADMIN_TOKEN"
assert_status "403" "Admin blocked from superadmin endpoint"

request "POST" "/auth/logout" "$ADMIN_TOKEN"
assert_status "204" "Admin logout"

# Superadmin flow
login "$SUPERADMIN_EMAIL" "$SUPERADMIN_PASSWORD" "superadmin" "qa-superadmin"
SUPERADMIN_TOKEN="${LOGIN_TOKEN}"
SUPERADMIN_ID="${LOGIN_USER_ID}"

request "GET" "/superadmin/overview" "$SUPERADMIN_TOKEN"
assert_status "200" "Superadmin overview endpoint"
assert_json '.data.stores_total >= 1' "Superadmin overview contains stores_total"

request "GET" "/superadmin/stores" "$SUPERADMIN_TOKEN"
assert_status "200" "Superadmin can list stores"
assert_json '.data | type == "array" and (length > 0)' "Superadmin store list non-empty"

request "GET" "/superadmin/reports/stores" "$SUPERADMIN_TOKEN"
assert_status "200" "Superadmin stores report endpoint"
assert_json '.data | type == "array"' "Superadmin stores report data shape"

request "GET" "/superadmin/admins" "$SUPERADMIN_TOKEN"
assert_status "200" "Superadmin can list admins/cashiers"
assert_json '.data | type == "array" and (length > 0)' "Superadmin admin roster non-empty"

STORE_CODE_TAG="$(date +%s)"
CREATE_STORE_PAYLOAD="$(jq -nc \
	--arg name "QA Store ${STORE_CODE_TAG}" \
	--arg code "QAS-${STORE_CODE_TAG}" \
	'{name: $name, code: $code, timezone: "Asia/Jakarta", currency: "IDR", address: "QA street", phone: "+62 800 0000 0000", is_active: true}')"

request "POST" "/superadmin/stores" "$SUPERADMIN_TOKEN" "$CREATE_STORE_PAYLOAD"
assert_status "201" "Superadmin can create store"
QA_STORE_ID="$(json_value '.data.id')"

request "PUT" "/superadmin/stores/${QA_STORE_ID}" "$SUPERADMIN_TOKEN" '{"name":"QA Store Updated"}'
assert_status "200" "Superadmin can update store"
assert_json '.data.name == "QA Store Updated"' "Updated store name is returned"

request "DELETE" "/superadmin/stores/${QA_STORE_ID}" "$SUPERADMIN_TOKEN"
assert_status "200" "Superadmin can deactivate store"
assert_json '.message == "Store deactivated."' "Superadmin store deactivation message"

USER_TAG="$(date +%s)"
CREATE_ADMIN_PAYLOAD="$(jq -nc \
	--arg name "QA Admin ${USER_TAG}" \
	--arg email "qa-admin-${USER_TAG}@example.test" \
	'{name: $name, email: $email, phone: "+62 811 1111 1111", role: "admin", password: "password123", is_active: true}')"

request "POST" "/superadmin/admins" "$SUPERADMIN_TOKEN" "$CREATE_ADMIN_PAYLOAD"
assert_status "201" "Superadmin can create admin user"
QA_USER_ID="$(json_value '.data.id')"

request "PUT" "/superadmin/admins/${QA_USER_ID}" "$SUPERADMIN_TOKEN" '{"role":"cashier","name":"QA Staff Updated"}'
assert_status "200" "Superadmin can update admin user"
assert_json '.data.role == "cashier"' "Updated user role is returned"

request "DELETE" "/superadmin/admins/${QA_USER_ID}" "$SUPERADMIN_TOKEN"
assert_status "200" "Superadmin can deactivate admin user"
assert_json '.message == "User deactivated."' "Superadmin user deactivation message"

request "DELETE" "/superadmin/admins/${SUPERADMIN_ID}" "$SUPERADMIN_TOKEN"
assert_status "403" "Superadmin cannot deactivate superadmin account"

request "POST" "/auth/logout" "$SUPERADMIN_TOKEN"
assert_status "204" "Superadmin logout"

END_EPOCH="$(date +%s)"
echo "QA endpoint script completed: ${CHECKS} checks passed in $((END_EPOCH - START_EPOCH))s."

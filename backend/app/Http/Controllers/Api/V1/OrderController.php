<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Order::class);

        $orders = Order::query()
            ->with(['store:id,name,code', 'cashier:id,name,email'])
            ->when($request->filled('store_id'), fn ($query) => $query->where('store_id', $request->integer('store_id')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->latest('placed_at')
            ->paginate($request->integer('per_page', 15));

        return OrderResource::collection($orders)->response();
    }

    public function show(Order $order): JsonResponse
    {
        $this->authorize('view', $order);

        return OrderResource::make($order->load(['store', 'cashier', 'items.product']))->response();
    }

    public function store(StoreOrderRequest $request): JsonResponse
    {
        $this->authorize('create', Order::class);

        $validated = $request->validated();

        $order = DB::transaction(function () use ($request, $validated) {
            $productIds = collect($validated['items'])->pluck('product_id');

            // Lock rows for update to prevent race conditions on stock
            $products = Product::query()
                ->whereIn('id', $productIds)
                ->where('is_active', true)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $lineItems = [];
            $subtotal = 0;

            foreach ($validated['items'] as $item) {
                $product = $products->get($item['product_id']);

                if (! $product) {
                    throw ValidationException::withMessages([
                        'items' => ["Product ID {$item['product_id']} is not available."],
                    ]);
                }

                if ($product->stock_quantity < $item['quantity']) {
                    throw ValidationException::withMessages([
                        'items' => ["Insufficient stock for \"{$product->name}\". Available: {$product->stock_quantity}."],
                    ]);
                }

                $lineTotal = $product->price_cents * $item['quantity'];
                $subtotal += $lineTotal;

                $lineItems[] = [
                    'product_id'       => $product->id,
                    'name_snapshot'    => $product->name,
                    'sku_snapshot'     => $product->sku,
                    'quantity'         => $item['quantity'],
                    'unit_price_cents' => $product->price_cents,
                    'line_total_cents' => $lineTotal,
                ];

                // Deduct stock atomically
                $product->decrement('stock_quantity', $item['quantity']);
            }

            $tax      = (int) ($validated['tax_cents'] ?? 0);
            $discount = (int) ($validated['discount_cents'] ?? 0);
            $status   = $validated['status'] ?? 'paid';

            $order = Order::query()->create([
                'order_number'     => 'ORD-'.now()->format('Ymd-His').'-'.str_pad((string) random_int(1, 999), 3, '0', STR_PAD_LEFT),
                'store_id'         => $validated['store_id'],
                'cashier_id'       => $request->user()->id,
                'status'           => $status,
                'payment_method'   => $validated['payment_method'],
                'subtotal_cents'   => $subtotal,
                'tax_cents'        => $tax,
                'discount_cents'   => $discount,
                'total_cents'      => max($subtotal + $tax - $discount, 0),
                'notes'            => $validated['notes'] ?? null,
                'placed_at'        => now(),
                'paid_at'          => $status === 'paid' ? now() : null,
            ]);

            $order->items()->createMany($lineItems);

            return $order;
        });

        return OrderResource::make($order->load(['store', 'cashier', 'items.product']))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateOrderRequest $request, Order $order): JsonResponse
    {
        $this->authorize('update', $order);

        $validated = $request->validated();
        $newStatus = $validated['status'];

        // Guard invalid transitions
        $allowed = match ($order->status) {
            'open'      => ['paid', 'cancelled'],
            'paid'      => [],
            'cancelled' => [],
            default     => [],
        };

        if (! in_array($newStatus, $allowed, true)) {
            throw ValidationException::withMessages([
                'status' => ["Cannot transition order from \"{$order->status}\" to \"{$newStatus}\"."],
            ]);
        }

        $update = ['status' => $newStatus];

        if ($newStatus === 'paid') {
            $update['paid_at'] = now();
            if (isset($validated['payment_method'])) {
                $update['payment_method'] = $validated['payment_method'];
            }
        }

        // Restore stock if cancelling
        if ($newStatus === 'cancelled') {
            DB::transaction(function () use ($order, $update) {
                foreach ($order->items as $item) {
                    if ($item->product_id) {
                        Product::query()
                            ->where('id', $item->product_id)
                            ->increment('stock_quantity', $item->quantity);
                    }
                }
                $order->update($update);
            });
        } else {
            $order->update($update);
        }

        if (isset($validated['notes'])) {
            $order->update(['notes' => $validated['notes']]);
        }

        return OrderResource::make($order->fresh()->load(['store', 'cashier', 'items.product']))->response();
    }
}

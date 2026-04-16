import { browser } from '$app/environment';
import { resolve } from '$app/paths';
import { appConfig } from '$config/app';

const STORAGE_KEY = 'vanaila-pos-session';

export type ApiValidationErrors = Record<string, string[]>;

export interface ApiPaginatedResponse<T> {
	data: T[];
	links: {
		first: string | null;
		last: string | null;
		prev: string | null;
		next: string | null;
	};
	meta: {
		current_page: number;
		from: number | null;
		last_page: number;
		path: string;
		per_page: number;
		to: number | null;
		total: number;
		links: Array<{
			url: string | null;
			label: string;
			active: boolean;
		}>;
	};
}

export class ApiError extends Error {
	status: number;
	errors?: ApiValidationErrors;

	constructor(message: string, status: number, errors?: ApiValidationErrors) {
		super(message);
		this.name = 'ApiError';
		this.status = status;
		this.errors = errors;
	}
}

function readToken() {
	if (!browser) {
		return null;
	}

	const session = localStorage.getItem(STORAGE_KEY);

	if (!session) {
		return null;
	}

	try {
		const parsed = JSON.parse(session) as { token?: string };
		return parsed.token ?? null;
	} catch {
		return null;
	}
}

function parseErrorPayload(
	payload: unknown
): { message: string; errors?: ApiValidationErrors } | null {
	if (!payload || typeof payload !== 'object') {
		return null;
	}

	const value = payload as { message?: unknown; errors?: unknown };
	const message = typeof value.message === 'string' ? value.message : null;
	const errors =
		value.errors && typeof value.errors === 'object'
			? (value.errors as ApiValidationErrors)
			: undefined;

	const firstValidationMessage = errors
		? Object.values(errors)
				.flat()
				.find((entry) => typeof entry === 'string' && entry.length > 0)
		: null;

	if (!message && !firstValidationMessage) {
		return null;
	}

	return {
		message: firstValidationMessage ?? message ?? 'Request failed.',
		errors
	};
}

function handleUnauthorizedResponse() {
	if (!browser) {
		return;
	}

	localStorage.removeItem(STORAGE_KEY);

	if (window.location.pathname !== resolve('/auth/login')) {
		window.location.assign(resolve('/auth/login'));
	}
}

export async function apiRequest<T>(path: string, init: RequestInit = {}): Promise<T> {
	const headers = new Headers(init.headers);

	headers.set('Accept', 'application/json');

	if (!headers.has('Content-Type') && init.body) {
		headers.set('Content-Type', 'application/json');
	}

	const token = readToken();

	if (token) {
		headers.set('Authorization', `Bearer ${token}`);
	}

	const response = await fetch(`${appConfig.apiBaseUrl}${path}`, {
		...init,
		headers
	});

	if (!response.ok) {
		const contentType = response.headers.get('content-type') ?? '';
		const isJson = contentType.includes('application/json');
		const payload = isJson ? await response.json().catch(() => null) : null;
		const fallbackText = isJson ? '' : (await response.text()).trim();
		const parsed = parseErrorPayload(payload);

		if (response.status === 401) {
			handleUnauthorizedResponse();
		}

		throw new ApiError(
			parsed?.message || fallbackText || `API request failed with status ${response.status}`,
			response.status,
			parsed?.errors
		);
	}

	if (response.status === 204) {
		return undefined as T;
	}

	return (await response.json()) as T;
}

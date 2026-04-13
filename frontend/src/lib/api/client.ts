import { browser } from '$app/environment';
import { appConfig } from '$config/app';

const STORAGE_KEY = 'vanaila-pos-session';

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
		const message = await response.text();
		throw new Error(message || `API request failed with status ${response.status}`);
	}

	if (response.status === 204) {
		return undefined as T;
	}

	return (await response.json()) as T;
}

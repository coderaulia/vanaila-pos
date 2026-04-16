import { browser } from '$app/environment';
import { apiRequest } from '$lib/api/client';
import type { AppSession, AppUser } from '$types/pos';

const STORAGE_KEY = 'vanaila-pos-session';
const DEMO_TOKEN_PREFIX = 'demo-';

class SessionStore {
	current = $state<AppSession | null>(null);

	constructor() {
		if (!browser) {
			return;
		}

		const stored = localStorage.getItem(STORAGE_KEY);

		if (!stored) {
			return;
		}

		try {
			this.current = JSON.parse(stored) as AppSession;
		} catch {
			localStorage.removeItem(STORAGE_KEY);
		}
	}

	setSession(token: string, user: AppUser) {
		this.current = {
			token,
			user,
			loggedInAt: new Date().toISOString()
		};
		this.persist();
	}

	async logout() {
		if (this.current && !this.isDemoToken(this.current.token)) {
			try {
				await apiRequest('/auth/logout', { method: 'POST' });
			} catch {
				// Ignore logout API errors and always clear local session.
			}
		}

		this.clear();
	}

	async validate(): Promise<boolean> {
		if (!this.current) {
			return false;
		}

		if (this.isDemoToken(this.current.token)) {
			return true;
		}

		try {
			const response = await apiRequest<{ user: AppUser }>('/auth/me');
			this.current = {
				...this.current,
				user: response.user
			};
			this.persist();

			return true;
		} catch {
			this.clear();
			return false;
		}
	}

	private persist() {
		if (!browser || !this.current) {
			return;
		}

		localStorage.setItem(STORAGE_KEY, JSON.stringify(this.current));
	}

	private clear() {
		this.current = null;

		if (browser) {
			localStorage.removeItem(STORAGE_KEY);
		}
	}

	private isDemoToken(token: string) {
		return token.startsWith(DEMO_TOKEN_PREFIX);
	}
}

export const session = new SessionStore();

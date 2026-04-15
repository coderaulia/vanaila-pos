import { browser } from '$app/environment';
import type { AppSession, AppUser } from '$types/pos';

const STORAGE_KEY = 'vanaila-pos-session';

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

	logout() {
		this.current = null;
		if (browser) {
			localStorage.removeItem(STORAGE_KEY);
		}
	}

	private persist() {
		if (!browser || !this.current) {
			return;
		}

		localStorage.setItem(STORAGE_KEY, JSON.stringify(this.current));
	}
}

export const session = new SessionStore();

import { browser } from '$app/environment';
import { demoUsers } from '$mocks/pos';
import type { DemoSession, UserRole } from '$types/pos';

const STORAGE_KEY = 'vanaila-pos-session';

class SessionStore {
	current = $state<DemoSession | null>(null);

	constructor() {
		if (!browser) {
			return;
		}

		const stored = localStorage.getItem(STORAGE_KEY);

		if (!stored) {
			return;
		}

		try {
			this.current = JSON.parse(stored) as DemoSession;
		} catch {
			localStorage.removeItem(STORAGE_KEY);
		}
	}

	loginAs(role: UserRole) {
		this.current = {
			token: `demo-${role}-token`,
			user: demoUsers[role],
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

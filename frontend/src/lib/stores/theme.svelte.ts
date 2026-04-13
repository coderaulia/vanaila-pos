import { browser } from '$app/environment';

export type Theme = 'light' | 'dark';

const STORAGE_KEY = 'vanaila-pos-theme';

class ThemeStore {
	current = $state<Theme>('light');

	constructor() {
		if (!browser) {
			return;
		}

		const stored = localStorage.getItem(STORAGE_KEY);
		const fallback = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
		this.current = stored === 'dark' || stored === 'light' ? stored : fallback;
		this.apply();
	}

	toggle() {
		this.current = this.current === 'dark' ? 'light' : 'dark';
		this.apply();
	}

	private apply() {
		if (!browser) {
			return;
		}

		document.documentElement.dataset.theme = this.current;
		localStorage.setItem(STORAGE_KEY, this.current);
	}
}

export const theme = new ThemeStore();

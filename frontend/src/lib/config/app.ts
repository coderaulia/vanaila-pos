import { env } from '$env/dynamic/public';

export const appConfig = {
	name: 'Vanaila POS',
	apiBaseUrl: env.PUBLIC_API_BASE_URL || 'http://127.0.0.1:8000/api/v1'
};

export const navigationItems = [
	{
		label: 'Overview',
		href: '/',
		description: 'Project landing page and route map.'
	},
	{
		label: 'Login',
		href: '/auth/login',
		description: 'Prepared login shell for Sanctum token auth.'
	},
	{
		label: 'Cashier',
		href: '/cashier',
		description: 'Mobile-first terminal for order capture and payment.'
	},
	{
		label: 'Admin',
		href: '/admin',
		description: 'Desktop dashboard for catalog and store operations.'
	},
	{
		label: 'Superadmin',
		href: '/superadmin',
		description: 'Platform-level management across stores and admins.'
	}
];

export const featureChecklist = [
	'Svelte 5 runes mode',
	'SvelteKit adapter-static with SPA fallback',
	'Bits UI tab primitives',
	'Role-aware demo session store',
	'Dark mode design tokens',
	'Hostinger-friendly `.htaccess` rewrite'
];

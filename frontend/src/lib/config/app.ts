import { env } from '$env/dynamic/public';
import type { AppNavItem } from '$types/navigation';

export const appConfig = {
	name: 'Vanaila POS',
	apiBaseUrl: env.PUBLIC_API_BASE_URL || 'http://127.0.0.1:8000/api/v1'
};

export const getTablePath = (tableCode: string): `/table/${string}` => `/table/${tableCode}`;

export const tableDemoCode = 'demo-t12';
export const tableDemoPath: `/table/${string}` = getTablePath(tableDemoCode);

export const publicNavigationItems: AppNavItem[] = [
	{
		label: 'Overview',
		href: '/',
		description: 'Project landing page and route map.',
		icon: 'home'
	},
	{
		label: 'Login',
		href: '/auth/login',
		description: 'Prepared login shell for Sanctum token auth.',
		icon: 'login'
	},
	{
		label: 'Table Demo',
		href: '/table',
		description: 'Customer self-checkout preview from the table.',
		icon: 'table'
	}
];

export const launchRoutes: AppNavItem[] = [
	{
		label: 'Cashier',
		href: '/cashier',
		description: 'Mobile-first terminal for order capture and payment.',
		icon: 'cashier'
	},
	{
		label: 'Admin',
		href: '/admin',
		description: 'Desktop dashboard for catalog and store operations.',
		icon: 'admin'
	},
	{
		label: 'Superadmin',
		href: '/superadmin',
		description: 'Platform-level management across stores and admins.',
		icon: 'superadmin'
	},
	{
		label: 'Table Self-Checkout',
		href: tableDemoPath,
		description: 'Guest-friendly browse, cart, and checkout flow for QR/table ordering.',
		icon: 'table'
	}
];

export const cashierNavigationItems: AppNavItem[] = [
	{
		label: 'New Order',
		href: '/cashier',
		description: 'Active sale and cart building workspace.',
		icon: 'cashier'
	},
	{
		label: 'Held Orders',
		href: '/cashier/held',
		description: 'Draft and parked tickets waiting for pickup or payment.',
		icon: 'held'
	},
	{
		label: 'Transactions',
		href: '/cashier/transactions',
		description: 'Recent payment activity and reversals.',
		icon: 'transactions'
	},
	{
		label: 'Shift',
		href: '/cashier/shift',
		description: 'Shift summary, handoff, and register checks.',
		icon: 'shift'
	}
];

export const adminNavigationItems: AppNavItem[] = [
	{
		label: 'Dashboard',
		href: '/admin',
		description: 'Store-wide performance overview.',
		icon: 'admin'
	},
	{
		label: 'Catalog',
		href: '/admin/catalog',
		description: 'Products, categories, and merchandising readiness.',
		icon: 'catalog'
	},
	{
		label: 'Orders',
		href: '/admin/orders',
		description: 'Operational queue and order monitoring.',
		icon: 'orders'
	},
	{
		label: 'Reports',
		href: '/admin/reports',
		description: 'Sales and inventory trend surfaces.',
		icon: 'reports'
	}
];

export const superadminNavigationItems: AppNavItem[] = [
	{
		label: 'Overview',
		href: '/superadmin',
		description: 'Network-wide health and global KPIs.',
		icon: 'superadmin'
	},
	{
		label: 'Stores',
		href: '/superadmin/stores',
		description: 'Store provisioning and rollout posture.',
		icon: 'stores'
	},
	{
		label: 'Admins',
		href: '/superadmin/admins',
		description: 'Admin roster, privilege checks, and account readiness.',
		icon: 'admins'
	},
	{
		label: 'Settings',
		href: '/superadmin/settings',
		description: 'Platform defaults and shared controls.',
		icon: 'settings'
	}
];

export const getTableNavigationItems = (tableCode: string): AppNavItem[] => {
	const tablePath = getTablePath(tableCode);
	const cartPath: `/table/${string}/cart` = `${tablePath}/cart`;
	const checkoutPath: `/table/${string}/checkout` = `${tablePath}/checkout`;
	const helpPath: `/table/${string}/help` = `${tablePath}/help`;

	return [
		{
			label: 'Menu',
			href: tablePath,
			description: 'Browse the table menu and add items.',
			icon: 'table'
		},
		{
			label: 'Cart',
			href: cartPath,
			description: 'Review quantities, notes, and totals.',
			icon: 'cart'
		},
		{
			label: 'Checkout',
			href: checkoutPath,
			description: 'Choose payment and place the order.',
			icon: 'checkout'
		},
		{
			label: 'Help',
			href: helpPath,
			description: 'Request assistance without leaving the table.',
			icon: 'help'
		}
	];
};

export const featureChecklist = [
	'Svelte 5 runes mode',
	'SvelteKit adapter-static with SPA fallback',
	'Role-scoped route shells',
	'Bits UI tab primitives',
	'Role-aware demo session store',
	'Customer table self-checkout demo route',
	'Dark mode design tokens',
	'Hostinger-friendly `.htaccess` rewrite'
];

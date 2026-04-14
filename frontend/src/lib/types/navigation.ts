export type BadgeTone = 'neutral' | 'accent' | 'sun' | 'success' | 'danger';

export type AppHref =
	| '/'
	| '/auth/login'
	| '/table'
	| '/cashier'
	| '/cashier/held'
	| '/cashier/transactions'
	| '/cashier/shift'
	| '/admin'
	| '/admin/catalog'
	| '/admin/orders'
	| '/admin/reports'
	| '/superadmin'
	| '/superadmin/stores'
	| '/superadmin/admins'
	| '/superadmin/settings'
	| `/table/${string}`
	| `/table/${string}/cart`
	| `/table/${string}/checkout`
	| `/table/${string}/help`;

export interface AppNavItem {
	label: string;
	href: AppHref;
	description: string;
}

export interface ShellBadge {
	label: string;
	tone?: BadgeTone;
}

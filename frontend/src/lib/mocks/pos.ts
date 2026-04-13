import type {
	AlertItem,
	DemoUser,
	MetricCardItem,
	ProductCardItem,
	StoreHealthCard,
	UserRole
} from '$types/pos';

export const demoUsers: Record<UserRole, DemoUser> = {
	cashier: {
		name: 'Maya Cashier',
		email: 'cashier@vanaila.test',
		role: 'cashier'
	},
	admin: {
		name: 'Raka Admin',
		email: 'admin@vanaila.test',
		role: 'admin'
	},
	superadmin: {
		name: 'Nadya Superadmin',
		email: 'superadmin@vanaila.test',
		role: 'superadmin'
	}
};

export const cashierSummary = {
	store: 'Vanaila Tebet',
	openTickets: '08',
	sales: '$1,284'
};

export const cashierProducts: ProductCardItem[] = [
	{
		id: 1,
		name: 'Vanilla Cloud Latte',
		sku: 'LATTE-12',
		category: 'Coffee',
		description: 'Silky espresso, vanilla cream cap, and a clean finish.',
		price: 5.5,
		stock: 72
	},
	{
		id: 2,
		name: 'Caramel Bean Frappe',
		sku: 'FRAP-09',
		category: 'Blended',
		description: 'Cold, sweet, and built for the afternoon rush.',
		price: 6.25,
		stock: 31
	},
	{
		id: 3,
		name: 'Cocoa Butter Croffle',
		sku: 'CROF-22',
		category: 'Bakery',
		description: 'Layered pastry with dark cocoa glaze.',
		price: 4.1,
		stock: 14
	},
	{
		id: 4,
		name: 'Matcha Oat Shake',
		sku: 'MATCH-08',
		category: 'Signature',
		description: 'Creamy oat milk with premium ceremonial matcha.',
		price: 5.95,
		stock: 18
	},
	{
		id: 5,
		name: 'Sparkling Citrus Tea',
		sku: 'TEA-19',
		category: 'Tea',
		description: 'Bright tea profile with lime zest and soda lift.',
		price: 4.75,
		stock: 54
	},
	{
		id: 6,
		name: 'Burnt Sugar Kouign',
		sku: 'PASTRY-07',
		category: 'Bakery',
		description: 'Buttery laminated pastry with a crackly caramel shell.',
		price: 4.85,
		stock: 9
	}
];

export const cashierCart = [
	{
		name: 'Vanilla Cloud Latte',
		quantity: 2,
		unitPrice: 5.5,
		notes: 'Less sugar'
	},
	{
		name: 'Cocoa Butter Croffle',
		quantity: 1,
		unitPrice: 4.1,
		notes: 'Warm before serve'
	},
	{
		name: 'Sparkling Citrus Tea',
		quantity: 1,
		unitPrice: 4.75,
		notes: 'No ice'
	}
];

export const adminMetrics: MetricCardItem[] = [
	{
		label: 'Net sales',
		value: '$18,240',
		detail: 'Daily trend is ahead of target by 9%.'
	},
	{
		label: 'Orders completed',
		value: '412',
		detail: 'Peak load centered between 11:00 and 13:30.'
	},
	{
		label: 'Active SKUs',
		value: '96',
		detail: '12 SKUs are part of this week’s featured campaign.'
	}
];

export const adminProducts = cashierProducts.slice(0, 4);

export const adminAlerts: AlertItem[] = [
	{
		title: 'Milk stock threshold triggered',
		detail: 'Barista milk inventory should be reordered before tomorrow’s morning rush.'
	},
	{
		title: 'Promo bundle outperforming baseline',
		detail: 'Vanilla Cloud Latte combo bundle has the strongest attach rate this week.'
	},
	{
		title: 'Two voided orders need review',
		detail: 'Manager acknowledgement is required before end-of-day close.'
	}
];

export const superadminMetrics: MetricCardItem[] = [
	{
		label: 'Live stores',
		value: '6',
		detail: 'All production stores are online.'
	},
	{
		label: 'Monthly GMV',
		value: '$204K',
		detail: 'Network-wide blended performance.'
	},
	{
		label: 'Admins managed',
		value: '11',
		detail: 'Across operations, finance, and store leadership.'
	}
];

export const superadminStores: StoreHealthCard[] = [
	{
		name: 'Vanaila Tebet',
		city: 'Jakarta',
		staff: 14,
		orders: 428,
		health: 'Healthy'
	},
	{
		name: 'Vanaila BSD',
		city: 'Tangerang',
		staff: 11,
		orders: 376,
		health: 'Healthy'
	},
	{
		name: 'Vanaila Braga',
		city: 'Bandung',
		staff: 9,
		orders: 214,
		health: 'Watch inventory sync'
	}
];

export type UserRole = 'cashier' | 'admin' | 'superadmin';

export interface DemoUser {
	name: string;
	email: string;
	role: UserRole;
}

export interface DemoSession {
	token: string;
	user: DemoUser;
	loggedInAt: string;
}

export interface CartItem {
	id: string;
	name: string;
	quantity: number;
	unitPrice: number;
	notes?: string;
}

export interface ProductCardItem {
	id: number;
	name: string;
	sku: string;
	category: string;
	description: string;
	price: number;
	stock: number;
}

export interface MetricCardItem {
	label: string;
	value: string;
	detail: string;
}

export interface AlertItem {
	title: string;
	detail: string;
}

export interface StoreHealthCard {
	name: string;
	city: string;
	staff: number;
	orders: number;
	health: string;
}

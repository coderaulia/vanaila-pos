export type UserRole = 'cashier' | 'admin' | 'superadmin';

export interface AppUser {
	id: number;
	name: string;
	email: string;
	phone?: string;
	role: UserRole;
}

export interface AppSession {
	token: string;
	user: AppUser;
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

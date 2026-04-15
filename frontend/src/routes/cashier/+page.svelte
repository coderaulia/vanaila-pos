<script lang="ts">
	import { CreditCard, Sparkles } from 'lucide-svelte';
	import { resolve } from '$app/paths';
	import CartSummaryCard from '$components/commerce/CartSummaryCard.svelte';
	import CatalogToolbar from '$components/commerce/CatalogToolbar.svelte';
	import ProductGrid from '$components/commerce/ProductGrid.svelte';
	import Card from '$components/ui/Card.svelte';
	import MetricCard from '$components/ui/MetricCard.svelte';
	import { cashierCart, cashierProducts, cashierSummary } from '$mocks/pos';
	import type { CartItem, ProductCardItem } from '$types/pos';

	let search = $state('');
	let activeCategory = $state('All');
	let cart = $state<CartItem[]>([...cashierCart]);

	const categories = ['All', ...new Set(cashierProducts.map((product) => product.category))];

	const filteredProducts = $derived(
		cashierProducts.filter((product) => {
			const matchesCategory = activeCategory === 'All' || product.category === activeCategory;
			const matchesSearch =
				search.length === 0 ||
				product.name.toLowerCase().includes(search.toLowerCase()) ||
				product.sku.toLowerCase().includes(search.toLowerCase());

			return matchesCategory && matchesSearch;
		})
	);

	function addToCart(product: ProductCardItem) {
		const existing = cart.find((item) => item.name === product.name);

		if (existing) {
			existing.quantity += 1;
			cart = [...cart];
			return;
		}

		cart = [
			...cart,
			{
				id: `cashier-${product.id}`,
				name: product.name,
				quantity: 1,
				unitPrice: product.price,
				notes: 'Added from menu'
			}
		];
	}
</script>

<svelte:head>
	<title>Cashier | Vanaila POS</title>
</svelte:head>

<div class="page">
	<div class="metric-grid">
		<MetricCard
			label="Current store"
			value={cashierSummary.store}
			detail="Primary register online"
		/>
		<MetricCard
			label="Open tickets"
			value={cashierSummary.openTickets}
			detail="Kitchen still processing"
		/>
		<MetricCard
			label="Today’s sales"
			value={cashierSummary.sales}
			detail="+14% against yesterday"
		/>
	</div>

	<section class="split-grid">
		<div class="page">
			<CatalogToolbar
				bind:search
				bind:activeCategory
				{categories}
				title="Catalog search"
				description="Search by product or SKU, then add directly into the active cart."
				searchLabel="Search by product or SKU"
				placeholder="Try Vanilla Cloud or LATTE-12"
			/>

			<ProductGrid products={filteredProducts} onSelect={addToCart} />
		</div>

		<div class="page">
			<CartSummaryCard items={cart} taxAmount={1.72} />

			<Card>
				<div class="section-header">
					<p class="kicker">Quick jump</p>
					<h2 class="section-title">Shift shortcuts</h2>
				</div>
				<div class="button-row">
					<a class="button ghost" href={resolve('/cashier/held')}>Open held orders</a>
					<a class="button ghost" href={resolve('/cashier/transactions')}>See transactions</a>
				</div>
			</Card>

			<div class="button-row">
				<button class="button">
					<CreditCard size={18} />
					Process payment
				</button>
				<button class="button secondary">
					<Sparkles size={18} />
					Save draft
				</button>
			</div>
		</div>
	</section>
</div>

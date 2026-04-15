<script lang="ts">
	import { resolve } from '$app/paths';
	import { page } from '$app/state';
	import CartSummaryCard from '$components/commerce/CartSummaryCard.svelte';
	import CatalogToolbar from '$components/commerce/CatalogToolbar.svelte';
	import ProductGrid from '$components/commerce/ProductGrid.svelte';
	import Card from '$components/ui/Card.svelte';
	import { tableDemoCode } from '$config/app';
	import { cashierProducts, tableCart, tableDemoProfile } from '$mocks/pos';
	import type { CartItem, ProductCardItem } from '$types/pos';

	let search = $state('');
	let activeCategory = $state('All');
	let cart = $state<CartItem[]>([...tableCart]);

	const tableCode = $derived(page.params.tableCode?.toUpperCase() ?? tableDemoProfile.code);
	const categories = ['All', ...new Set(cashierProducts.map((product) => product.category))];

	const filteredProducts = $derived(
		cashierProducts.filter((product) => {
			const matchesCategory = activeCategory === 'All' || product.category === activeCategory;
			const matchesSearch =
				search.length === 0 || product.name.toLowerCase().includes(search.toLowerCase());

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
				id: `table-${product.id}`,
				name: product.name,
				quantity: 1,
				unitPrice: product.price,
				notes: 'Guest-added item'
			}
		];
	}
</script>

<svelte:head>
	<title>Table Menu | Vanaila POS</title>
</svelte:head>

<div class="page">
	<section class="split-grid">
		<div class="page">
			<CatalogToolbar
				bind:search
				bind:activeCategory
				{categories}
				title="Browse the menu"
				description="Pick a category, search quickly, and add items to this table."
				searchLabel="Search menu"
				placeholder="Search drinks or bakery"
			/>

			<ProductGrid
				products={filteredProducts}
				actionLabel="Add to order"
				showStock={false}
				onSelect={addToCart}
			/>
		</div>

		<div class="page">
			<CartSummaryCard
				items={cart}
				kicker="Table order"
				title="Your current items"
				taxAmount={1.34}
				totalLabel="Estimated total"
			/>

			<Card>
				<div class="section-header">
					<p class="kicker">Before checkout</p>
					<h2 class="section-title">Helpful guest cues</h2>
				</div>

				<div class="list">
					<div class="product-card">
						<strong>Table linked</strong>
						<p class="muted">{tableDemoProfile.zone} at {tableDemoProfile.store}.</p>
					</div>
					<div class="product-card">
						<strong>Need help?</strong>
						<p class="muted">Use the Help tab if a guest wants staff assistance before paying.</p>
					</div>
				</div>

				<a
					class="button"
					href={resolve('/table/[tableCode]/checkout', {
						tableCode
					})}
				>
					Continue to checkout
				</a>
			</Card>
		</div>
	</section>
</div>

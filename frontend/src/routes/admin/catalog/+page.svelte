<script lang="ts">
	import PageIntro from '$components/layout/PageIntro.svelte';
	import CatalogToolbar from '$components/commerce/CatalogToolbar.svelte';
	import ProductGrid from '$components/commerce/ProductGrid.svelte';
	import Card from '$components/ui/Card.svelte';
	import { adminProducts } from '$mocks/pos';

	let search = $state('');
	let activeCategory = $state('All');

	const categories = ['All', ...new Set(adminProducts.map((product) => product.category))];

	const filteredProducts = $derived(
		adminProducts.filter((product) => {
			const matchesCategory = activeCategory === 'All' || product.category === activeCategory;
			const matchesSearch =
				search.length === 0 ||
				product.name.toLowerCase().includes(search.toLowerCase()) ||
				product.sku.toLowerCase().includes(search.toLowerCase());

			return matchesCategory && matchesSearch;
		})
	);
</script>

<svelte:head>
	<title>Catalog | Vanaila POS</title>
</svelte:head>

<div class="page">
	<PageIntro
		compact={true}
		kicker="Catalog"
		title="Products and categories now have a cleaner workspace than the old all-in-one dashboard."
		description="This route is the foundation for future product create, edit, publish, and stock actions, while keeping the current UI focused on discoverability."
		badges={[
			{ label: 'Merchandising', tone: 'accent' },
			{ label: 'Search-ready', tone: 'sun' }
		]}
	/>

	<section class="split-grid">
		<div class="page">
			<CatalogToolbar
				bind:search
				bind:activeCategory
				{categories}
				title="Browse active SKUs"
				description="Filter by product family to review readiness before backend CRUD is wired up."
				searchLabel="Search by product or SKU"
				placeholder="Try LATTE-12"
			/>

			<ProductGrid products={filteredProducts} actionLabel="Review product" onSelect={() => {}} />
		</div>

		<Card>
			<div class="section-header">
				<p class="kicker">Catalog notes</p>
				<h2 class="section-title">What this route is preparing for</h2>
			</div>

			<div class="list">
				<div class="product-card">
					<strong>Product editing</strong>
					<p class="muted">
						The layout leaves space for side panels, edit drawers, and media actions later.
					</p>
				</div>
				<div class="product-card">
					<strong>Publishing flow</strong>
					<p class="muted">
						Admin can stage promos and availability without mixing that work into reporting screens.
					</p>
				</div>
				<div class="product-card">
					<strong>Multi-channel consistency</strong>
					<p class="muted">
						These same SKUs will feed cashier and table self-checkout once the API is connected.
					</p>
				</div>
			</div>
		</Card>
	</section>
</div>

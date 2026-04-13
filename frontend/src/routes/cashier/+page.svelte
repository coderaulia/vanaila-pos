<script lang="ts">
	import { CreditCard, Search, ShoppingCart, Sparkles } from 'lucide-svelte';
	import Badge from '$components/ui/Badge.svelte';
	import Card from '$components/ui/Card.svelte';
	import MetricCard from '$components/ui/MetricCard.svelte';
	import { cashierCart, cashierProducts, cashierSummary } from '$mocks/pos';

	let search = $state('');
	let activeCategory = $state('All');

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

	const cartTotal = $derived(
		cashierCart.reduce((total, item) => total + item.unitPrice * item.quantity, 0)
	);
</script>

<svelte:head>
	<title>Cashier | Vanaila POS</title>
</svelte:head>

<div class="page">
	<section class="hero-card">
		<div class="cluster">
			<Badge tone="accent">Mobile-first POS</Badge>
			<Badge tone="success">Shift ready</Badge>
		</div>

		<div class="spotlight">
			<p class="eyebrow">Cashier terminal</p>
			<h1 class="display-title">
				Fast tap targets, quick cart review, and payment clarity on smaller screens.
			</h1>
			<p class="lead">
				The cashier route prioritizes one-thumb navigation, sticky cart awareness, and minimal
				cognitive load during busy service windows.
			</p>
		</div>

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
	</section>

	<section class="split-grid">
		<div class="page">
			<Card>
				<div class="section-header">
					<p class="kicker">Find products</p>
					<h2 class="section-title">Catalog search</h2>
				</div>

				<label class="field">
					<span class="muted">Search by product or SKU</span>
					<div class="cluster">
						<Search size={18} />
						<input bind:value={search} type="search" placeholder="Try Vanilla Cloud or LATTE-12" />
					</div>
				</label>

				<div class="chip-row">
					{#each categories as category (category)}
						<button
							class={`chip ${category === activeCategory ? 'active' : ''}`}
							onclick={() => (activeCategory = category)}
						>
							{category}
						</button>
					{/each}
				</div>
			</Card>

			<div class="product-grid">
				{#each filteredProducts as product (product.id)}
					<article class="product-card">
						<div class="list-row">
							<div>
								<strong>{product.name}</strong>
								<div class="meta">{product.sku}</div>
							</div>
							<Badge tone={product.stock < 12 ? 'sun' : 'accent'}>
								{product.stock < 12 ? 'Low stock' : 'Ready'}
							</Badge>
						</div>

						<p class="muted">{product.description}</p>

						<div class="cluster">
							<span class="price">${product.price.toFixed(2)}</span>
							<span class="meta">{product.category}</span>
						</div>

						<div class="progress-bar">
							<div class="progress-fill" style={`--fill:${Math.max(12, product.stock)}%`}></div>
						</div>

						<button class="button">
							<ShoppingCart size={18} />
							Add to cart
						</button>
					</article>
				{/each}
			</div>
		</div>

		<Card>
			<div class="section-header">
				<p class="kicker">Current order</p>
				<h2 class="section-title">Cart review</h2>
			</div>

			<div class="cart-list">
				{#each cashierCart as item (item.name)}
					<div class="product-card">
						<div class="list-row">
							<div>
								<strong>{item.name}</strong>
								<div class="meta">{item.quantity} x ${item.unitPrice.toFixed(2)}</div>
							</div>
							<Badge>{item.notes}</Badge>
						</div>
						<div class="list-row">
							<span class="muted">Line total</span>
							<strong>${(item.unitPrice * item.quantity).toFixed(2)}</strong>
						</div>
					</div>
				{/each}
			</div>

			<div class="divider"></div>

			<div class="list">
				<div class="list-row">
					<span class="muted">Subtotal</span>
					<strong>${cartTotal.toFixed(2)}</strong>
				</div>
				<div class="list-row">
					<span class="muted">Tax</span>
					<strong>$1.72</strong>
				</div>
				<div class="list-row">
					<span class="muted">Total due</span>
					<span class="price">${(cartTotal + 1.72).toFixed(2)}</span>
				</div>
			</div>

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
		</Card>
	</section>
</div>

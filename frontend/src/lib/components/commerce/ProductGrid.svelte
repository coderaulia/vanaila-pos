<script lang="ts">
	import { ShoppingCart } from 'lucide-svelte';
	import { fade, fly } from 'svelte/transition';
	import { flip } from 'svelte/animate';
	import Badge from '$components/ui/Badge.svelte';
	import type { ProductCardItem } from '$types/pos';

	type Props = {
		products: ProductCardItem[];
		actionLabel?: string;
		showStock?: boolean;
		emptyTitle?: string;
		emptyCopy?: string;
		onSelect?: (product: ProductCardItem) => void;
	};

	let {
		products,
		actionLabel = 'Add to cart',
		showStock = true,
		emptyTitle = 'No products found',
		emptyCopy = 'Try a different search term or switch categories.',
		onSelect = () => {}
	}: Props = $props();
</script>

{#if products.length > 0}
	<div class="product-grid" in:fade={{ duration: 200 }}>
		{#each products as product, i (product.id)}
			<article
				class="product-card"
				animate:flip={{ duration: 300 }}
				in:fly={{ y: 20, duration: 400, delay: Math.min(i * 50, 400) }}
				out:fade={{ duration: 150 }}
			>
				<div class="list-row">
					<div>
						<strong>{product.name}</strong>
						<div class="meta">{product.sku}</div>
					</div>
					<Badge tone={showStock && product.stock < 12 ? 'sun' : 'accent'}>
						{showStock && product.stock < 12 ? 'Low stock' : product.category}
					</Badge>
				</div>

				<p class="muted">{product.description}</p>

				<div class="cluster">
					<span class="price">${product.price.toFixed(2)}</span>
					{#if showStock}
						<span class="meta">{product.stock} ready</span>
					{/if}
				</div>

				{#if showStock}
					<div class="progress-bar">
						<div class="progress-fill" style={`--fill:${Math.max(12, product.stock)}%`}></div>
					</div>
				{/if}

				<button class="button" onclick={() => onSelect(product)}>
					<ShoppingCart size={18} />
					{actionLabel}
				</button>
			</article>
		{/each}
	</div>
{:else}
	<div class="empty-state">
		<strong>{emptyTitle}</strong>
		<p class="muted">{emptyCopy}</p>
	</div>
{/if}

<script lang="ts">
	import Card from '$components/ui/Card.svelte';
	import Badge from '$components/ui/Badge.svelte';
	import { slide, fade } from 'svelte/transition';
	import { flip } from 'svelte/animate';
	import type { CartItem } from '$types/pos';

	type Props = {
		items: CartItem[];
		title?: string;
		kicker?: string;
		taxAmount?: number;
		totalLabel?: string;
	};

	let {
		items,
		title = 'Cart review',
		kicker = 'Current order',
		taxAmount = 0,
		totalLabel = 'Total due'
	}: Props = $props();

	const subtotal = $derived(
		items.reduce((total, item) => total + item.unitPrice * item.quantity, 0)
	);
	const total = $derived(subtotal + taxAmount);
</script>

<Card className="cart-summary">
	<div class="section-header">
		<p class="kicker">{kicker}</p>
		<h2 class="section-title">{title}</h2>
	</div>

	{#if items.length > 0}
		<div class="cart-list" in:fade={{ duration: 200 }}>
			{#each items as item (item.id)}
				<div
					class="product-card"
					animate:flip={{ duration: 300 }}
					in:slide={{ duration: 300, axis: 'y' }}
					out:slide={{ duration: 200, axis: 'y' }}
				>
					<div class="list-row">
						<div>
							<strong>{item.name}</strong>
							<div class="meta">{item.quantity} x ${item.unitPrice.toFixed(2)}</div>
						</div>
						{#if item.notes}
							<Badge>{item.notes}</Badge>
						{/if}
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
				<strong>${subtotal.toFixed(2)}</strong>
			</div>
			<div class="list-row">
				<span class="muted">Tax</span>
				<strong>${taxAmount.toFixed(2)}</strong>
			</div>
			<div class="list-row">
				<span class="muted">{totalLabel}</span>
				<span class="price">${total.toFixed(2)}</span>
			</div>
		</div>
	{:else}
		<div class="empty-state">
			<strong>Cart is empty</strong>
			<p class="muted">Add at least one item to continue.</p>
		</div>
	{/if}
</Card>

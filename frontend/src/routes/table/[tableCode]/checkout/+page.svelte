<script lang="ts">
	import { CreditCard, QrCode, Wallet } from 'lucide-svelte';
	import CartSummaryCard from '$components/commerce/CartSummaryCard.svelte';
	import Card from '$components/ui/Card.svelte';
	import { tableCart } from '$mocks/pos';

	const paymentMethods = [
		{
			title: 'QRIS',
			detail: 'Fastest guest path for mobile checkout at the table.',
			icon: QrCode
		},
		{
			title: 'Card',
			detail: 'Prepared for embedded card or hosted payment handoff later.',
			icon: CreditCard
		},
		{
			title: 'Pay staff',
			detail: 'Fallback if the guest prefers to settle with a cashier or waiter.',
			icon: Wallet
		}
	];
</script>

<svelte:head>
	<title>Table Checkout | Vanaila POS</title>
</svelte:head>

<div class="page">
	<section class="split-grid">
		<Card>
			<div class="section-header">
				<p class="kicker">Select payment</p>
				<h2 class="section-title">Available methods</h2>
			</div>

			<div class="list">
				{#each paymentMethods as method (method.title)}
					<div class="product-card">
						<div class="cluster">
							<method.icon size={18} />
							<strong>{method.title}</strong>
						</div>
						<p class="muted">{method.detail}</p>
						<button class="button secondary">Choose {method.title}</button>
					</div>
				{/each}
			</div>
		</Card>

		<CartSummaryCard
			items={tableCart}
			kicker="Payment summary"
			title="What the guest will pay"
			taxAmount={1.34}
			totalLabel="Pay now"
		/>
	</section>
</div>

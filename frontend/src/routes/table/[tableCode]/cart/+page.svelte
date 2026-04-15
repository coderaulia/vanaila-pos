<script lang="ts">
	import { resolve } from '$app/paths';
	import { page } from '$app/state';
	import CartSummaryCard from '$components/commerce/CartSummaryCard.svelte';
	import Card from '$components/ui/Card.svelte';
	import { tableCart } from '$mocks/pos';
	import { tableDemoCode } from '$config/app';
</script>

<svelte:head>
	<title>Table Cart | Vanaila POS</title>
</svelte:head>

<div class="page">
	<section class="split-grid">
		<CartSummaryCard
			items={tableCart}
			kicker="Table order"
			title="Current basket"
			taxAmount={1.34}
			totalLabel="Estimated total"
		/>

		<Card>
			<div class="section-header">
				<p class="kicker">Next step</p>
				<h2 class="section-title">Ready to pay?</h2>
			</div>

			<div class="list">
				<div class="product-card">
					<strong>Split-bill ready later</strong>
					<p class="muted">
						This layout leaves room for item reassignment or split payment options in a later pass.
					</p>
				</div>
				<div class="product-card">
					<strong>Staff fallback</strong>
					<p class="muted">
						If something looks wrong, the Help route gives guests a way to call a staff member.
					</p>
				</div>
			</div>

			<a
				class="button"
				href={resolve('/table/[tableCode]/checkout', {
					tableCode: page.params.tableCode ?? tableDemoCode
				})}
			>
				Continue to payment
			</a>
		</Card>
	</section>
</div>

<script lang="ts">
	import { Play, ReceiptText } from 'lucide-svelte';
	import PageIntro from '$components/layout/PageIntro.svelte';
	import Badge from '$components/ui/Badge.svelte';
	import Card from '$components/ui/Card.svelte';
	import { cashierHeldOrders } from '$mocks/pos';
</script>

<svelte:head>
	<title>Held Orders | Vanaila POS</title>
</svelte:head>

<div class="page">
	<PageIntro
		compact={true}
		kicker="Held orders"
		title="Parked tickets are separated from the active register so the main sale flow stays clean."
		description="Use this route for interrupted tables, preorder handoff, and anything the cashier needs to resume later without cluttering the new-order screen."
		badges={[
			{ label: `${cashierHeldOrders.length} parked tickets`, tone: 'accent' },
			{ label: 'Resume-ready', tone: 'sun' }
		]}
	/>

	<section class="dashboard-grid">
		<Card>
			<div class="section-header">
				<p class="kicker">Queued work</p>
				<h2 class="section-title">Orders waiting to be resumed</h2>
			</div>

			<div class="list">
				{#each cashierHeldOrders as order (order.id)}
					<div class="product-card">
						<div class="list-row">
							<div>
								<strong>{order.title}</strong>
								<div class="meta">{order.detail}</div>
							</div>
							<Badge tone="accent">{order.total}</Badge>
						</div>
						<div class="list-row">
							<span class="muted">{order.updatedAt}</span>
							<button class="button secondary">
								<Play size={16} />
								Resume
							</button>
						</div>
					</div>
				{/each}
			</div>
		</Card>

		<Card>
			<div class="section-header">
				<p class="kicker">Operator notes</p>
				<h2 class="section-title">When to hold instead of closing</h2>
			</div>

			<div class="list">
				<div class="product-card">
					<strong>Counter pickup timing</strong>
					<p class="muted">
						Keep orders parked until the guest confirms pickup or the item is fully prepared.
					</p>
				</div>
				<div class="product-card">
					<strong>Shared tables</strong>
					<p class="muted">
						Split service for a table can stay held until everyone is ready to settle together.
					</p>
				</div>
				<div class="product-card">
					<strong>Escalations</strong>
					<p class="muted">
						If an item needs manager confirmation, hold it here rather than blocking the next guest.
					</p>
				</div>
			</div>

			<div class="empty-state">
				<div class="cluster">
					<ReceiptText size={18} />
					<strong>Future API fit</strong>
				</div>
				<p class="muted">
					This screen is shaped to map to held-order endpoints later, but it stays mock-driven for
					the frontend pass.
				</p>
			</div>
		</Card>
	</section>
</div>

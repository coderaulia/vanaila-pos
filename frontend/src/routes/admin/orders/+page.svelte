<script lang="ts">
	import PageIntro from '$components/layout/PageIntro.svelte';
	import Badge from '$components/ui/Badge.svelte';
	import Card from '$components/ui/Card.svelte';
	import { adminOrders } from '$mocks/pos';
</script>

<svelte:head>
	<title>Orders | Vanaila POS</title>
</svelte:head>

<div class="page">
	<PageIntro
		compact={true}
		kicker="Orders"
		title="Operational monitoring gets its own board so managers can track flow without opening the cashier screen."
		description="This route is shaped for service oversight across walk-ins, cashier-assisted orders, and guest self-checkout orders."
		badges={[
			{ label: 'Ops board', tone: 'accent' },
			{ label: 'Cross-channel', tone: 'sun' }
		]}
	/>

	<section class="dashboard-grid">
		<Card>
			<div class="section-header">
				<p class="kicker">Live queue</p>
				<h2 class="section-title">Recent orders</h2>
			</div>

			<div class="list">
				{#each adminOrders as order (order.id)}
					<div class="product-card">
						<div class="list-row">
							<div>
								<strong>{order.id}</strong>
								<div class="meta">{order.guest} · {order.detail}</div>
							</div>
							<Badge tone={order.status === 'Completed' ? 'success' : 'accent'}
								>{order.status}</Badge
							>
						</div>
						<div class="list-row">
							<span class="muted">Order total</span>
							<strong>{order.total}</strong>
						</div>
					</div>
				{/each}
			</div>
		</Card>

		<Card>
			<div class="section-header">
				<p class="kicker">Manager actions</p>
				<h2 class="section-title">Quick review areas</h2>
			</div>

			<div class="list">
				<div class="product-card">
					<strong>Kitchen pacing</strong>
					<p class="muted">
						Track where queue pressure is forming before customer wait times spike.
					</p>
				</div>
				<div class="product-card">
					<strong>Payment follow-up</strong>
					<p class="muted">
						Keep an eye on self-checkout orders that need manual intervention or verification.
					</p>
				</div>
				<div class="product-card">
					<strong>Service recovery</strong>
					<p class="muted">
						This section is ready for refunds, order reassignment, and issue tagging later.
					</p>
				</div>
			</div>
		</Card>
	</section>
</div>

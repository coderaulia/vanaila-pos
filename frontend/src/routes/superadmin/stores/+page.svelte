<script lang="ts">
	import PageIntro from '$components/layout/PageIntro.svelte';
	import Badge from '$components/ui/Badge.svelte';
	import Card from '$components/ui/Card.svelte';
	import { superadminStores } from '$mocks/pos';
</script>

<svelte:head>
	<title>Stores | Vanaila POS</title>
</svelte:head>

<div class="page">
	<PageIntro
		compact={true}
		kicker="Stores"
		title="Store-level health gets its own network view for rollout and support decisions."
		description="This is the foundation for provisioning, store status, and per-location configuration once the backend catches up."
		badges={[
			{ label: 'Multi-store', tone: 'accent' },
			{ label: 'Rollout watch', tone: 'sun' }
		]}
	/>

	<section class="dashboard-grid">
		<Card>
			<div class="section-header">
				<p class="kicker">Store roster</p>
				<h2 class="section-title">Current locations</h2>
			</div>

			<div class="list">
				{#each superadminStores as store (store.name)}
					<div class="product-card">
						<div class="list-row">
							<div>
								<strong>{store.name}</strong>
								<div class="meta">
									{store.city} · {store.staff} staff · {store.orders} orders today
								</div>
							</div>
							<Badge tone={store.health === 'Healthy' ? 'success' : 'sun'}>{store.health}</Badge>
						</div>
					</div>
				{/each}
			</div>
		</Card>

		<Card>
			<div class="section-header">
				<p class="kicker">Network posture</p>
				<h2 class="section-title">Immediate priorities</h2>
			</div>

			<div class="list">
				<div class="product-card">
					<strong>Inventory sync monitoring</strong>
					<p class="muted">
						Flag stores whose catalog or stock propagation needs manual attention.
					</p>
				</div>
				<div class="product-card">
					<strong>Launch readiness</strong>
					<p class="muted">
						This area can later host domain, payment, and printer readiness signals per store.
					</p>
				</div>
			</div>
		</Card>
	</section>
</div>

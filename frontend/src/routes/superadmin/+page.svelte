<script lang="ts">
	import { resolve } from '$app/paths';
	import { ArrowRight } from 'lucide-svelte';
	import Card from '$components/ui/Card.svelte';
	import MetricCard from '$components/ui/MetricCard.svelte';
	import { superadminMetrics, superadminStores } from '$mocks/pos';
</script>

<svelte:head>
	<title>Superadmin | Vanaila POS</title>
</svelte:head>

<div class="page">
	<div class="metric-grid">
		{#each superadminMetrics as metric (metric.label)}
			<MetricCard label={metric.label} value={metric.value} detail={metric.detail} />
		{/each}
	</div>

	<section class="split-grid">
		<Card>
			<div class="section-header">
				<p class="kicker">Control surfaces</p>
				<h2 class="section-title">Jump into the governance area you need</h2>
			</div>
			<div class="list">
				<div class="product-card">
					<div class="list-row">
						<div>
							<strong>Stores</strong>
							<div class="meta">
								Provisioning, health tracking, and rollout confidence by location.
							</div>
						</div>
						<a class="button secondary" href={resolve('/superadmin/stores')}>
							Open
							<ArrowRight size={16} />
						</a>
					</div>
				</div>
				<div class="product-card">
					<div class="list-row">
						<div>
							<strong>Admins</strong>
							<div class="meta">Rosters, access policy, and privilege readiness across teams.</div>
						</div>
						<a class="button secondary" href={resolve('/superadmin/admins')}>
							Open
							<ArrowRight size={16} />
						</a>
					</div>
				</div>
				<div class="product-card">
					<div class="list-row">
						<div>
							<strong>Settings</strong>
							<div class="meta">Tax presets, brand defaults, and platform policy controls.</div>
						</div>
						<a class="button secondary" href={resolve('/superadmin/settings')}>
							Open
							<ArrowRight size={16} />
						</a>
					</div>
				</div>
			</div>
		</Card>

		<Card>
			<div class="section-header">
				<p class="kicker">Network status</p>
				<h2 class="section-title">Store snapshot</h2>
			</div>

			<div class="list">
				{#each superadminStores as store (store.name)}
					<div class="product-card">
						<strong>{store.name}</strong>
						<p class="muted">{store.city} · {store.staff} staff · {store.orders} orders today</p>
					</div>
				{/each}
			</div>
		</Card>
	</section>
</div>

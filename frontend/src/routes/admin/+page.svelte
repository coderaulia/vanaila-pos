<script lang="ts">
	import { resolve } from '$app/paths';
	import { ArrowRight } from 'lucide-svelte';
	import Card from '$components/ui/Card.svelte';
	import MetricCard from '$components/ui/MetricCard.svelte';
	import { adminAlerts, adminMetrics } from '$mocks/pos';
</script>

<svelte:head>
	<title>Admin | Vanaila POS</title>
</svelte:head>

<div class="page">
	<div class="metric-grid">
		{#each adminMetrics as metric (metric.label)}
			<MetricCard label={metric.label} value={metric.value} detail={metric.detail} />
		{/each}
	</div>

	<section class="dashboard-grid">
		<Card>
			<div class="section-header">
				<p class="kicker">Workspace routes</p>
				<h2 class="section-title">Choose the management area you want next</h2>
			</div>
			<div class="list">
				<div class="product-card">
					<div class="list-row">
						<div>
							<strong>Catalog</strong>
							<div class="meta">Product readiness, categories, and merchandising hygiene.</div>
						</div>
						<a class="button secondary" href={resolve('/admin/catalog')}>
							Open
							<ArrowRight size={16} />
						</a>
					</div>
				</div>
				<div class="product-card">
					<div class="list-row">
						<div>
							<strong>Orders</strong>
							<div class="meta">
								Track service progress, payment channel, and operational queue.
							</div>
						</div>
						<a class="button secondary" href={resolve('/admin/orders')}>
							Open
							<ArrowRight size={16} />
						</a>
					</div>
				</div>
				<div class="product-card">
					<div class="list-row">
						<div>
							<strong>Reports</strong>
							<div class="meta">
								Review sales pace, basket quality, and inventory watch signals.
							</div>
						</div>
						<a class="button secondary" href={resolve('/admin/reports')}>
							Open
							<ArrowRight size={16} />
						</a>
					</div>
				</div>
			</div>
		</Card>

		<Card>
			<div class="section-header">
				<p class="kicker">Admin checklist</p>
				<h2 class="section-title">Today’s focus</h2>
			</div>

			<div class="list">
				{#each adminAlerts as alert (alert.title)}
					<div class="product-card">
						<strong>{alert.title}</strong>
						<p class="muted">{alert.detail}</p>
					</div>
				{/each}
			</div>
		</Card>
	</section>
</div>

<script lang="ts">
	import Card from '$components/ui/Card.svelte';
	import MetricCard from '$components/ui/MetricCard.svelte';
	import { adminAlerts, adminMetrics } from '$mocks/pos';
</script>

<svelte:head>
	<title>Reports | Vanaila POS</title>
</svelte:head>

<div class="page">
	<div class="metric-grid">
		{#each adminMetrics as metric (metric.label)}
			<MetricCard label={metric.label} value={metric.value} detail={metric.detail} />
		{/each}
	</div>

	<Card>
		<div class="section-header">
			<p class="kicker">Today’s watchlist</p>
			<h2 class="section-title">Signals worth reviewing</h2>
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
</div>

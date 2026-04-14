<script lang="ts">
	import PageIntro from '$components/layout/PageIntro.svelte';
	import Card from '$components/ui/Card.svelte';
	import MetricCard from '$components/ui/MetricCard.svelte';
	import { adminAlerts, adminMetrics } from '$mocks/pos';
</script>

<svelte:head>
	<title>Reports | Vanaila POS</title>
</svelte:head>

<div class="page">
	<PageIntro
		compact={true}
		kicker="Reports"
		title="Performance review stays separate from operational screens so analysis gets room to breathe."
		description="This view is tuned for desktop reading and will be the future home for trend charts, export actions, and store reporting filters."
		badges={[
			{ label: 'Analytics', tone: 'accent' },
			{ label: 'Desktop-first', tone: 'sun' }
		]}
	/>

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

<script lang="ts">
	import { Tabs } from 'bits-ui';
	import { Boxes, ChartColumn, ClipboardList } from 'lucide-svelte';
	import Badge from '$components/ui/Badge.svelte';
	import Card from '$components/ui/Card.svelte';
	import MetricCard from '$components/ui/MetricCard.svelte';
	import { adminAlerts, adminMetrics, adminProducts } from '$mocks/pos';

	let activeTab = $state('catalog');
</script>

<svelte:head>
	<title>Admin | Vanaila POS</title>
</svelte:head>

<div class="page">
	<section class="hero-card">
		<div class="cluster">
			<Badge tone="accent">Desktop-first dashboard</Badge>
			<Badge tone="sun">Store operations</Badge>
		</div>

		<div class="spotlight">
			<p class="eyebrow">Admin workspace</p>
			<h1 class="display-title">
				Track store performance and keep the catalog in a healthy, sellable state.
			</h1>
			<p class="lead">
				The admin surface prioritizes wide-screen density, faster comparative scanning, and
				at-a-glance operational signals for a single store or location cluster.
			</p>
		</div>

		<div class="metric-grid">
			{#each adminMetrics as metric (metric.label)}
				<MetricCard label={metric.label} value={metric.value} detail={metric.detail} />
			{/each}
		</div>
	</section>

	<section class="dashboard-grid">
		<Card>
			<div class="section-header">
				<p class="kicker">Operations board</p>
				<h2 class="section-title">Role-based management tabs</h2>
			</div>

			<Tabs.Root bind:value={activeTab}>
				<Tabs.List class="tabs-list">
					<Tabs.Trigger class="tabs-trigger" value="catalog">
						<Boxes size={16} />
						Catalog
					</Tabs.Trigger>
					<Tabs.Trigger class="tabs-trigger" value="reports">
						<ChartColumn size={16} />
						Reports
					</Tabs.Trigger>
					<Tabs.Trigger class="tabs-trigger" value="ops">
						<ClipboardList size={16} />
						Ops
					</Tabs.Trigger>
				</Tabs.List>

				<Tabs.Content class="tabs-panel" value="catalog">
					<div class="table-card">
						<div class="list-row">
							<strong>Top moving products</strong>
							<Badge>14 SKUs this week</Badge>
						</div>
						{#each adminProducts as product (product.id)}
							<div class="table-row">
								<div>
									<strong>{product.name}</strong>
									<div class="meta">{product.category} · {product.sku}</div>
								</div>
								<div>
									<strong>${product.price.toFixed(2)}</strong>
									<div class="meta">{product.stock} in stock</div>
								</div>
							</div>
						{/each}
					</div>
				</Tabs.Content>

				<Tabs.Content class="tabs-panel" value="reports">
					<div class="table-card">
						<div class="table-row">
							<div>
								<strong>Revenue pulse</strong>
								<div class="meta">Gross sales rose after the weekend promo push.</div>
							</div>
							<Badge tone="success">+12.4%</Badge>
						</div>
						<div class="table-row">
							<div>
								<strong>Average basket</strong>
								<div class="meta">Upsell bundle adoption is trending upward.</div>
							</div>
							<Badge tone="accent">$11.80</Badge>
						</div>
						<div class="table-row">
							<div>
								<strong>Inventory risk</strong>
								<div class="meta">2 categories need replenishment before Friday noon rush.</div>
							</div>
							<Badge tone="sun">Watchlist</Badge>
						</div>
					</div>
				</Tabs.Content>

				<Tabs.Content class="tabs-panel" value="ops">
					<div class="list">
						{#each adminAlerts as alert (alert.title)}
							<div class="empty-state">
								<strong>{alert.title}</strong>
								<p class="muted">{alert.detail}</p>
							</div>
						{/each}
					</div>
				</Tabs.Content>
			</Tabs.Root>
		</Card>

		<Card>
			<div class="section-header">
				<p class="kicker">Admin checklist</p>
				<h2 class="section-title">Today’s focus</h2>
			</div>

			<div class="list">
				<div class="product-card">
					<strong>Review stock thresholds</strong>
					<p class="muted">
						Automatic reorder suggestions should be rebalanced after the new drinks launch.
					</p>
				</div>
				<div class="product-card">
					<strong>Validate closing summary</strong>
					<p class="muted">
						Yesterday’s cash variance is under tolerance, but still needs manager acknowledgement.
					</p>
				</div>
				<div class="product-card">
					<strong>Refresh promo availability</strong>
					<p class="muted">
						Weekend promo SKUs should end automatically at midnight local store time.
					</p>
				</div>
			</div>
		</Card>
	</section>
</div>

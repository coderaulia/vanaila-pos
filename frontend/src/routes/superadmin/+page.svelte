<script lang="ts">
	import { Tabs } from 'bits-ui';
	import { Building2, Settings2, UserCog } from 'lucide-svelte';
	import Badge from '$components/ui/Badge.svelte';
	import Card from '$components/ui/Card.svelte';
	import MetricCard from '$components/ui/MetricCard.svelte';
	import { superadminMetrics, superadminStores } from '$mocks/pos';

	let activeTab = $state('stores');
</script>

<svelte:head>
	<title>Superadmin | Vanaila POS</title>
</svelte:head>

<div class="page">
	<section class="hero-card">
		<div class="cluster">
			<Badge tone="accent">Global governance</Badge>
			<Badge tone="sun">Multi-store visibility</Badge>
		</div>

		<div class="spotlight">
			<p class="eyebrow">Superadmin control tower</p>
			<h1 class="display-title">Manage the full POS network from one operational plane.</h1>
			<p class="lead">
				Superadmins get the widest lens: store provisioning, staff permissions, policy defaults, and
				the system health required to scale beyond a single location.
			</p>
		</div>

		<div class="metric-grid">
			{#each superadminMetrics as metric (metric.label)}
				<MetricCard label={metric.label} value={metric.value} detail={metric.detail} />
			{/each}
		</div>
	</section>

	<section class="split-grid">
		<Card>
			<div class="section-header">
				<p class="kicker">Control surfaces</p>
				<h2 class="section-title">Governance tabs powered by Bits UI</h2>
			</div>

			<Tabs.Root bind:value={activeTab}>
				<Tabs.List class="tabs-list">
					<Tabs.Trigger class="tabs-trigger" value="stores">
						<Building2 size={16} />
						Stores
					</Tabs.Trigger>
					<Tabs.Trigger class="tabs-trigger" value="admins">
						<UserCog size={16} />
						Admins
					</Tabs.Trigger>
					<Tabs.Trigger class="tabs-trigger" value="settings">
						<Settings2 size={16} />
						Settings
					</Tabs.Trigger>
				</Tabs.List>

				<Tabs.Content class="tabs-panel" value="stores">
					<div class="table-card">
						{#each superadminStores as store (store.name)}
							<div class="table-row">
								<div>
									<strong>{store.name}</strong>
									<div class="meta">{store.city} · {store.staff} staff</div>
								</div>
								<div>
									<strong>{store.health}</strong>
									<div class="meta">{store.orders} orders today</div>
								</div>
							</div>
						{/each}
					</div>
				</Tabs.Content>

				<Tabs.Content class="tabs-panel" value="admins">
					<div class="list">
						<div class="empty-state">
							<strong>Admin provisioning</strong>
							<p class="muted">
								Invite flows, password resets, and store assignment rules live in this panel.
							</p>
						</div>
						<div class="empty-state">
							<strong>Role escalation policy</strong>
							<p class="muted">
								Only superadmins can create admins, rotate store access, or revoke elevated roles.
							</p>
						</div>
					</div>
				</Tabs.Content>

				<Tabs.Content class="tabs-panel" value="settings">
					<div class="list">
						<div class="product-card">
							<strong>Global tax defaults</strong>
							<p class="muted">
								Versioned tax and service-charge templates can be propagated per store.
							</p>
						</div>
						<div class="product-card">
							<strong>Brand system</strong>
							<p class="muted">
								Shared receipt footer, theme tokens, and shared copy live under platform settings.
							</p>
						</div>
						<div class="product-card">
							<strong>Deployment posture</strong>
							<p class="muted">
								Frontend ships as static files to `public_html`; Laravel API lives in `/api`.
							</p>
						</div>
					</div>
				</Tabs.Content>
			</Tabs.Root>
		</Card>

		<Card>
			<div class="section-header">
				<p class="kicker">Network status</p>
				<h2 class="section-title">Rollout notes</h2>
			</div>

			<div class="list">
				<div class="product-card">
					<strong>Store onboarding</strong>
					<p class="muted">
						Create a store, seed an admin, then scope product and pricing rules before go-live.
					</p>
				</div>
				<div class="product-card">
					<strong>Hostinger readiness</strong>
					<p class="muted">
						Static frontend build artifacts are ready for direct upload; API uses standard
						shared-host PHP entry points.
					</p>
				</div>
				<div class="product-card">
					<strong>Observability</strong>
					<p class="muted">
						Add structured request logging and error reporting before production launch.
					</p>
				</div>
			</div>
		</Card>
	</section>
</div>

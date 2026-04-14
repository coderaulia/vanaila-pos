<script lang="ts">
	import PageIntro from '$components/layout/PageIntro.svelte';
	import Badge from '$components/ui/Badge.svelte';
	import Card from '$components/ui/Card.svelte';
	import { superadminSettings } from '$mocks/pos';
</script>

<svelte:head>
	<title>Settings | Vanaila POS</title>
</svelte:head>

<div class="page">
	<PageIntro
		compact={true}
		kicker="Global settings"
		title="Shared platform controls live on their own page instead of hiding inside overview cards."
		description="That separation makes future forms for taxes, brand defaults, and access policy much easier to extend."
		badges={[
			{ label: 'Platform defaults', tone: 'accent' },
			{ label: 'Restricted controls', tone: 'sun' }
		]}
	/>

	<section class="dashboard-grid">
		<Card>
			<div class="section-header">
				<p class="kicker">Control groups</p>
				<h2 class="section-title">Prepared settings domains</h2>
			</div>

			<div class="list">
				{#each superadminSettings as setting (setting.id)}
					<div class="product-card">
						<div class="list-row">
							<div>
								<strong>{setting.title}</strong>
								<div class="meta">{setting.detail}</div>
							</div>
							<Badge tone="accent">{setting.state}</Badge>
						</div>
					</div>
				{/each}
			</div>
		</Card>

		<Card>
			<div class="section-header">
				<p class="kicker">Change control</p>
				<h2 class="section-title">Why this page matters</h2>
			</div>

			<div class="list">
				<div class="product-card">
					<strong>Safer edits</strong>
					<p class="muted">
						High-impact defaults stay away from day-to-day store operations screens.
					</p>
				</div>
				<div class="product-card">
					<strong>Future auditability</strong>
					<p class="muted">This layout can grow into version history and approval trails later.</p>
				</div>
			</div>
		</Card>
	</section>
</div>

<script lang="ts">
	import PageIntro from '$components/layout/PageIntro.svelte';
	import Badge from '$components/ui/Badge.svelte';
	import Card from '$components/ui/Card.svelte';
	import { superadminAdminRoster } from '$mocks/pos';
</script>

<svelte:head>
	<title>Admins | Vanaila POS</title>
</svelte:head>

<div class="page">
	<PageIntro
		compact={true}
		kicker="Admins"
		title="Admin management now has a dedicated surface for roster and access reviews."
		description="This route prepares the frontend for invitations, role changes, and store assignments without crowding the overview page."
		badges={[
			{ label: 'Access control', tone: 'accent' },
			{ label: 'Roster view', tone: 'sun' }
		]}
	/>

	<section class="dashboard-grid">
		<Card>
			<div class="section-header">
				<p class="kicker">Assigned users</p>
				<h2 class="section-title">Admin roster</h2>
			</div>

			<div class="list">
				{#each superadminAdminRoster as admin (admin.id)}
					<div class="product-card">
						<div class="list-row">
							<div>
								<strong>{admin.name}</strong>
								<div class="meta">{admin.email}</div>
							</div>
							<Badge tone={admin.status === 'Active' ? 'success' : 'sun'}>{admin.status}</Badge>
						</div>
						<div class="list-row">
							<span class="muted">{admin.role}</span>
							<span class="muted">{admin.stores}</span>
						</div>
					</div>
				{/each}
			</div>
		</Card>

		<Card>
			<div class="section-header">
				<p class="kicker">Governance notes</p>
				<h2 class="section-title">Policy reminders</h2>
			</div>

			<div class="list">
				<div class="product-card">
					<strong>Least privilege</strong>
					<p class="muted">
						Store admins should see only their assigned location unless promoted explicitly.
					</p>
				</div>
				<div class="product-card">
					<strong>2FA readiness</strong>
					<p class="muted">
						Pending setup states belong here so superadmins can clean them up before launch.
					</p>
				</div>
			</div>
		</Card>
	</section>
</div>

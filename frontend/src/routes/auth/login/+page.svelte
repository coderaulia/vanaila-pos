<script lang="ts">
	import { goto } from '$app/navigation';
	import { resolve } from '$app/paths';
	import { Shield, ShieldCheck, Smartphone } from 'lucide-svelte';
	import ShellFrame from '$components/layout/ShellFrame.svelte';
	import Badge from '$components/ui/Badge.svelte';
	import Card from '$components/ui/Card.svelte';
	import { publicNavigationItems } from '$config/app';
	import { demoUsers } from '$mocks/pos';
	import { session } from '$stores/session.svelte';
	import type { UserRole } from '$types/pos';

	let email = $state('cashier@vanaila.test');
	let password = $state('password');
	let deviceName = $state('cashier-tablet');

	const roleTargets = {
		cashier: '/cashier',
		admin: '/admin',
		superadmin: '/superadmin'
	} satisfies Record<UserRole, '/cashier' | '/admin' | '/superadmin'>;

	const roleMeta = [
		{
			role: 'cashier' as const,
			title: 'Cashier',
			description: 'Fast transaction flow and payment handoff on a handheld or tablet.',
			icon: Smartphone
		},
		{
			role: 'admin' as const,
			title: 'Admin',
			description: 'Catalog operations and daily reporting from a desktop workspace.',
			icon: Shield
		},
		{
			role: 'superadmin' as const,
			title: 'Superadmin',
			description: 'Network governance, store configuration, and global visibility.',
			icon: ShieldCheck
		}
	];

	async function continueAs(role: UserRole) {
		session.loginAs(role);
		await goto(resolve(roleTargets[role]));
	}
</script>

<svelte:head>
	<title>Login | Vanaila POS</title>
</svelte:head>

<ShellFrame
	contextLabel="Role access"
	contextTitle="Choose the workspace you want to simulate before the real API login lands."
	contextDescription="This page stays aligned with the future Sanctum login contract, while the demo buttons keep frontend flows moving during the frontend-first build."
	navItems={publicNavigationItems}
	badges={[{ label: 'Sanctum-ready', tone: 'accent' }]}
	showSessionBadge={true}
	allowSignOut={true}
	variant="public"
>
	<div class="page">
		<section class="hero-card">
			<div class="spotlight">
				<Badge tone="accent">Static frontend auth shell</Badge>
				<p class="eyebrow">Prepared for `POST /api/v1/auth/login`</p>
				<h1 class="display-title">
					Choose a role, then swap the demo session for the real API call later.
				</h1>
				<p class="lead">
					The structure is already set up for email, password, and device name, but role shortcuts
					keep the cashier and backoffice areas testable while we build the frontend first.
				</p>
			</div>
		</section>

		<section class="split-grid">
			<Card>
				<div class="section-header">
					<p class="kicker">Login contract</p>
					<h2 class="section-title">Prepared request payload</h2>
				</div>

				<div class="field-grid">
					<label class="field">
						<span>Email</span>
						<input bind:value={email} type="email" placeholder="cashier@vanaila.test" />
					</label>

					<label class="field">
						<span>Password</span>
						<input bind:value={password} type="password" placeholder="password" />
					</label>

					<label class="field">
						<span>Device name</span>
						<input bind:value={deviceName} type="text" placeholder="cashier-tablet" />
					</label>
				</div>

				<div class="empty-state">
					<strong>Boilerplate note</strong>
					<p class="muted">
						The real submission step should call the API client in `src/lib/api/client.ts`, persist
						the returned bearer token, and replace this local session shortcut.
					</p>
				</div>
			</Card>

			<Card>
				<div class="section-header">
					<p class="kicker">Demo entry points</p>
					<h2 class="section-title">Jump into each role shell</h2>
				</div>

				<div class="list">
					{#each roleMeta as entry (entry.role)}
						<div class="product-card">
							<div class="list-row">
								<div class="cluster">
									<entry.icon size={18} />
									<strong>{entry.title}</strong>
								</div>
								<Badge>{demoUsers[entry.role].email}</Badge>
							</div>
							<p class="muted">{entry.description}</p>
							<button class="button" onclick={() => continueAs(entry.role)}>
								Continue as {entry.title}
							</button>
						</div>
					{/each}
				</div>
			</Card>
		</section>
	</div>
</ShellFrame>

<script lang="ts">
	import { goto } from '$app/navigation';
	import { resolve } from '$app/paths';
	import { Shield, ShieldCheck, Smartphone } from 'lucide-svelte';
	import Badge from '$components/ui/Badge.svelte';
	import Card from '$components/ui/Card.svelte';
	import { demoUsers } from '$mocks/pos';
	import { session } from '$stores/session.svelte';
	import type { UserRole } from '$types/pos';

	let email = $state('cashier@vanaila.test');
	let password = $state('password');
	let deviceName = $state('cashier-tablet');

	const roleTargets: Record<UserRole, string> = {
		cashier: '/cashier',
		admin: '/admin',
		superadmin: '/superadmin'
	};

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

<div class="page">
	<section class="hero-card">
		<div class="spotlight">
			<Badge tone="accent">Sanctum-ready token login</Badge>
			<p class="eyebrow">Static frontend authentication shell</p>
			<h1 class="display-title">
				Choose a role, then swap the demo session for the real API call later.
			</h1>
			<p class="lead">
				This screen is structured to mirror the future Laravel login payload. For now, role
				shortcuts write a local demo session so we can validate the route architecture and
				responsive shells without waiting on full backend integration.
			</p>
		</div>
	</section>

	<section class="split-grid">
		<Card>
			<div class="section-header">
				<p class="kicker">Login contract</p>
				<h2 class="section-title">Prepared for `POST /api/v1/auth/login`</h2>
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

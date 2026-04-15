<script lang="ts">
	import { goto } from '$app/navigation';
	import { resolve } from '$app/paths';
	import { Shield, ShieldCheck, Smartphone, KeyRound } from 'lucide-svelte';
	import ShellFrame from '$components/layout/ShellFrame.svelte';
	import Badge from '$components/ui/Badge.svelte';
	import Card from '$components/ui/Card.svelte';
	import { publicNavigationItems } from '$config/app';
	import { demoUsers } from '$mocks/pos';
	import { session } from '$stores/session.svelte';
	import { apiRequest } from '$lib/api/client';
	import type { AppUser, UserRole } from '$types/pos';

	let email = $state('cashier@vanaila.test');
	let password = $state('password');
	let deviceName = $state('cashier-tablet');
	let isSubmitting = $state(false);
	let errorMessage = $state('');

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

	async function submitLogin() {
		try {
			isSubmitting = true;
			errorMessage = '';

			const payload = {
				email,
				password,
				device_name: deviceName
			};

			const response = await apiRequest<{ token: string; user: AppUser }>('/auth/login', {
				method: 'POST',
				body: JSON.stringify(payload)
			});

			session.setSession(response.token, response.user);
			await goto(resolve(roleTargets[response.user.role]));
		} catch (e) {
			const err = e as Error;
			errorMessage = err.message || 'Login failed. Please check your credentials.';
		} finally {
			isSubmitting = false;
		}
	}

	async function continueAsMock(role: UserRole) {
		session.setSession(`demo-${role}-token`, demoUsers[role]);
		await goto(resolve(roleTargets[role]));
	}
</script>

<svelte:head>
	<title>Login | Vanaila POS</title>
</svelte:head>

<ShellFrame
	contextLabel="Login"
	contextTitle="Welcome to Vanaila POS"
	contextDescription="Please sign in to access your assigned workspace."
	navItems={publicNavigationItems}
	showSessionBadge={false}
	allowSignOut={false}
	variant="public"
>
	<div class="page" style="margin: 0 auto; max-width: 480px; padding-top: 2rem;">
		<Card>
			<div class="section-header">
				<p class="kicker">Secure Access</p>
				<h2 class="section-title">Sign In</h2>
			</div>

			<form
				class="field-grid"
				onsubmit={(e) => {
					e.preventDefault();
					submitLogin();
				}}
			>
				{#if errorMessage}
					<div class="empty-state" style="border-color: var(--danger); color: var(--danger);">
						<strong>Error</strong>
						<p>{errorMessage}</p>
					</div>
				{/if}

				<label class="field">
					<span>Email</span>
					<input bind:value={email} type="email" placeholder="cashier@vanaila.test" required />
				</label>

				<label class="field">
					<span>Password</span>
					<input bind:value={password} type="password" placeholder="password" required />
				</label>

				<label class="field" style="display: none;">
					<span>Device name</span>
					<input bind:value={deviceName} type="text" placeholder="cashier-tablet" required />
				</label>

				<button type="submit" class="button" disabled={isSubmitting}>
					<KeyRound size={18} />
					{isSubmitting ? 'Verifying credentials...' : 'Sign In'}
				</button>
			</form>
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
						<button class="button secondary" onclick={() => continueAsMock(entry.role)}>
							Mock {entry.title} Token
						</button>
					</div>
				{/each}
			</div>
		</Card>
	</div>
</ShellFrame>

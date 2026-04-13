<script lang="ts">
	import { goto } from '$app/navigation';
	import { resolve } from '$app/paths';
	import { page } from '$app/state';
	import Badge from '$components/ui/Badge.svelte';
	import ThemeToggle from '$components/ui/ThemeToggle.svelte';
	import { navigationItems } from '$config/app';
	import { session } from '$stores/session.svelte';

	function isActive(href: string) {
		if (href === '/') {
			return page.url.pathname === '/';
		}

		return page.url.pathname === href || page.url.pathname.startsWith(`${href}/`);
	}

	async function signOut() {
		session.logout();
		await goto(resolve('/auth/login'));
	}
</script>

<header class="app-header">
	<div class="brand">
		<div class="brand-kicker">Vanaila POS</div>
		<div class="brand-title">Static storefront, API backbone</div>
	</div>

	<div class="app-header-top">
		<div class="cluster">
			{#if session.current}
				<Badge tone="accent">{session.current.user.role}</Badge>
				<Badge>{session.current.user.email}</Badge>
			{:else}
				<Badge>Guest mode</Badge>
			{/if}
		</div>

		<div class="cluster">
			<ThemeToggle />
			{#if session.current}
				<button class="button ghost" onclick={signOut}>Sign out</button>
			{/if}
		</div>
	</div>

	<nav class="nav-links" aria-label="Primary">
		{#each navigationItems as item (item.href)}
			<a class={`nav-link ${isActive(item.href) ? 'active' : ''}`} href={resolve(item.href)}>
				{item.label}
			</a>
		{/each}
	</nav>
</header>

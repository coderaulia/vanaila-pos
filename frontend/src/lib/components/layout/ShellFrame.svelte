<script lang="ts">
	import { goto } from '$app/navigation';
	import { resolve } from '$app/paths';
	import { page } from '$app/state';
	import type { Snippet } from 'svelte';
	import ThemeToggle from '$components/ui/ThemeToggle.svelte';
	import Badge from '$components/ui/Badge.svelte';
	import { session } from '$stores/session.svelte';
	import type { AppHref, AppNavItem, ShellBadge } from '$types/navigation';

	type Props = {
		children: Snippet;
		contextLabel: string;
		contextTitle: string;
		contextDescription: string;
		navItems: AppNavItem[];
		badges?: ShellBadge[];
		brandKicker?: string;
		brandTitle?: string;
		homeHref?: AppHref;
		showSessionBadge?: boolean;
		allowSignOut?: boolean;
		variant?: 'public' | 'workspace' | 'customer';
	};

	let {
		children,
		contextLabel,
		contextTitle,
		contextDescription,
		navItems,
		badges = [],
		brandKicker = 'Vanaila POS',
		brandTitle = 'Frontend foundation',
		homeHref = '/',
		showSessionBadge = false,
		allowSignOut = false,
		variant = 'workspace'
	}: Props = $props();

	const activeHref = $derived.by(() => {
		const currentPath = page.url.pathname;
		const matches = navItems.filter((item) => {
			if (item.href === '/') {
				return currentPath === '/';
			}

			return currentPath === item.href || currentPath.startsWith(`${item.href}/`);
		});

		return matches.sort((left, right) => right.href.length - left.href.length)[0]?.href ?? null;
	});

	const displayBadges = $derived.by(() => {
		const shellBadges = [...badges];

		if (showSessionBadge) {
			if (session.current) {
				shellBadges.unshift({ label: session.current.user.role, tone: 'accent' });
				shellBadges.push({ label: session.current.user.email });
			} else {
				shellBadges.push({ label: 'Guest mode' });
			}
		}

		return shellBadges;
	});

	async function signOut() {
		session.logout();
		await goto(resolve('/auth/login'));
	}
</script>

<section class={`shell shell-${variant}`}>
	<header class="shell-header surface">
		<div class="shell-header-top">
			<a class="brand shell-brand-link" href={resolve(homeHref)}>
				<div class="brand-kicker">{brandKicker}</div>
				<div class="brand-title">{brandTitle}</div>
			</a>

			<div class="cluster">
				{#each displayBadges as badge (badge.label)}
					<Badge tone={badge.tone ?? 'neutral'}>{badge.label}</Badge>
				{/each}
				<ThemeToggle />
				{#if allowSignOut && session.current}
					<button class="button ghost" onclick={signOut}>Sign out</button>
				{/if}
			</div>
		</div>

		<div class="shell-context">
			<p class="kicker">{contextLabel}</p>
			<h1 class="shell-title">{contextTitle}</h1>
			<p class="lead shell-description">{contextDescription}</p>
		</div>

		<nav class="shell-nav" aria-label={contextTitle}>
			{#each navItems as item (item.href)}
				<a
					class={`shell-link ${item.href === activeHref ? 'active' : ''}`}
					href={resolve(item.href)}
				>
					{item.label}
				</a>
			{/each}
		</nav>
	</header>

	<div class="shell-page">
		{@render children()}
	</div>
</section>

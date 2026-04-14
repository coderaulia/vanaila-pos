<script lang="ts">
	import { goto } from '$app/navigation';
	import { resolve } from '$app/paths';
	import { page } from '$app/state';
	import {
		ArrowLeft,
		BookOpenText,
		ChartNoAxesColumn,
		CircleHelp,
		ClipboardList,
		CreditCard,
		HandCoins,
		House,
		LayoutGrid,
		LogIn,
		Package2,
		ReceiptText,
		Settings2,
		ShieldCheck,
		ShoppingCart,
		Store,
		Users,
		UtensilsCrossed
	} from 'lucide-svelte';
	import type { Snippet } from 'svelte';
	import ThemeToggle from '$components/ui/ThemeToggle.svelte';
	import Badge from '$components/ui/Badge.svelte';
	import { session } from '$stores/session.svelte';
	import type { AppHref, AppNavIcon, AppNavItem, ShellBadge } from '$types/navigation';

	type Props = {
		children: Snippet;
		contextLabel: string;
		contextTitle: string;
		contextDescription: string;
		navItems: AppNavItem[];
		badges?: ShellBadge[];
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

	const activeItem = $derived(navItems.find((item) => item.href === activeHref) ?? navItems[0]);

	const iconMap: Record<AppNavIcon, typeof House> = {
		home: House,
		login: LogIn,
		table: UtensilsCrossed,
		cart: ShoppingCart,
		checkout: CreditCard,
		help: CircleHelp,
		cashier: HandCoins,
		held: BookOpenText,
		transactions: ReceiptText,
		shift: ClipboardList,
		admin: LayoutGrid,
		catalog: Package2,
		orders: ShoppingCart,
		reports: ChartNoAxesColumn,
		superadmin: ShieldCheck,
		stores: Store,
		admins: Users,
		settings: Settings2
	};

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
	<header class="shell-header">
		<div class="shell-topbar">
			<a class="shell-back" href={resolve(homeHref)} aria-label="Back to section home">
				<ArrowLeft size={18} />
			</a>

			<div class="shell-heading">
				<p class="shell-heading-kicker">{contextLabel}</p>
				<h1 class="shell-heading-title">{activeItem?.label ?? brandTitle}</h1>
				<p class="shell-heading-subtitle">{contextDescription}</p>
			</div>

			<div class="shell-actions">
				<ThemeToggle />
				{#if allowSignOut && session.current}
					<button class="shell-icon-button" onclick={signOut} aria-label="Sign out">
						<LogIn size={18} />
					</button>
				{/if}
			</div>
		</div>

		{#if displayBadges.length > 0}
			<div class="shell-badges">
				{#each displayBadges as badge (badge.label)}
					<Badge tone={badge.tone ?? 'neutral'}>{badge.label}</Badge>
				{/each}
			</div>
		{/if}
	</header>

	<div class="shell-page">
		{@render children()}
	</div>

	<nav class="shell-footer" aria-label={contextTitle}>
		{#each navItems as item (item.href)}
			{@const Icon = iconMap[item.icon]}
			<a
				class={`shell-footer-link ${item.href === activeHref ? 'active' : ''}`}
				href={resolve(item.href)}
			>
				<Icon size={18} />
				<span>{item.label}</span>
			</a>
		{/each}
	</nav>
</section>

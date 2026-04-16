<script lang="ts">
	import { onMount } from 'svelte';
	import type { Snippet } from 'svelte';
	import ShellFrame from '$components/layout/ShellFrame.svelte';
	import { adminNavigationItems } from '$config/app';
	import { requireAuth } from '$lib/guards/auth';
	import { session } from '$stores/session.svelte';

	type Props = {
		children: Snippet;
	};

	let { children }: Props = $props();

	onMount(() => {
		requireAuth(session, 'admin');
	});
</script>

<ShellFrame
	contextLabel="Admin workspace"
	contextTitle="Manage store operations from a desktop-first control surface."
	contextDescription="Catalog, orders, and reports each get a dedicated route so day-to-day admin work feels clearer and less crowded."
	navItems={adminNavigationItems}
	badges={[
		{ label: 'Desktop-first', tone: 'accent' },
		{ label: 'Store operations', tone: 'sun' }
	]}
	brandTitle="Admin dashboard"
	showSessionBadge={true}
	allowSignOut={true}
>
	{@render children()}
</ShellFrame>

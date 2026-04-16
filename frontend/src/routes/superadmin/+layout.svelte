<script lang="ts">
	import { onMount } from 'svelte';
	import type { Snippet } from 'svelte';
	import ShellFrame from '$components/layout/ShellFrame.svelte';
	import { superadminNavigationItems } from '$config/app';
	import { requireAuth } from '$lib/guards/auth';
	import { session } from '$stores/session.svelte';

	type Props = {
		children: Snippet;
	};

	let { children }: Props = $props();

	onMount(() => {
		requireAuth(session, 'superadmin');
	});
</script>

<ShellFrame
	contextLabel="Superadmin workspace"
	contextTitle="Coordinate stores, admins, and platform policy from one governance layer."
	contextDescription="This shell is intentionally broader than store admin, with space for network health, permissions, and shared platform defaults."
	navItems={superadminNavigationItems}
	badges={[
		{ label: 'Global governance', tone: 'accent' },
		{ label: 'Network visibility', tone: 'sun' }
	]}
	brandTitle="Superadmin hub"
	showSessionBadge={true}
	allowSignOut={true}
>
	{@render children()}
</ShellFrame>

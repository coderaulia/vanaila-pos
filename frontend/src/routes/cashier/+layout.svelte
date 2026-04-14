<script lang="ts">
	import type { Snippet } from 'svelte';
	import ShellFrame from '$components/layout/ShellFrame.svelte';
	import { cashierNavigationItems } from '$config/app';

	type Props = {
		children: Snippet;
	};

	let { children }: Props = $props();
</script>

<ShellFrame
	contextLabel="Cashier workspace"
	contextTitle="Move fast on the active sale without mixing in backoffice tasks."
	contextDescription="The cashier shell stays optimized for taps, quick cart review, and the next payment action. Held orders, transactions, and shift tools now live in their own routes."
	navItems={cashierNavigationItems}
	badges={[
		{ label: 'Mobile-first', tone: 'accent' },
		{ label: 'Staff POS', tone: 'sun' }
	]}
	brandTitle="Cashier terminal"
	showSessionBadge={true}
	allowSignOut={true}
>
	{@render children()}
</ShellFrame>

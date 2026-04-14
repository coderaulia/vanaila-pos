<script lang="ts">
	import type { Snippet } from 'svelte';
	import { page } from '$app/state';
	import ShellFrame from '$components/layout/ShellFrame.svelte';
	import { getTableNavigationItems, tableDemoCode } from '$config/app';
	import { tableDemoProfile } from '$mocks/pos';

	type Props = {
		children: Snippet;
	};

	let { children }: Props = $props();
	const tableCode = $derived(page.params.tableCode?.toUpperCase() ?? tableDemoProfile.code);
	const navItems = $derived(getTableNavigationItems(page.params.tableCode ?? tableDemoCode));
</script>

<ShellFrame
	contextLabel="Table self-checkout"
	contextTitle={`Order and pay from table ${tableCode} without opening the staff POS.`}
	contextDescription={tableDemoProfile.hostNote}
	{navItems}
	badges={[
		{ label: tableDemoProfile.store, tone: 'accent' },
		{ label: tableDemoProfile.zone, tone: 'sun' },
		{ label: `Table ${tableCode}` }
	]}
	brandTitle="Table checkout"
	variant="customer"
>
	{@render children()}
</ShellFrame>

<script lang="ts">
	import { onMount } from 'svelte';
	import '../app.css';
	import favicon from '$lib/assets/favicon.svg';
	import { session } from '$stores/session.svelte';

	let { children } = $props();

	onMount(() => {
		void session.validate();
	});
</script>

<svelte:head>
	<link rel="icon" href={favicon} />
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
	<meta
		name="description"
		content="Vanaila POS frontend boilerplate for cashier, admin, and superadmin workflows."
	/>
</svelte:head>

<div class="app-frame">
	<div class="ambient-orb a"></div>
	<div class="ambient-orb b"></div>

	<div class="page-shell">
		{@render children()}
	</div>
</div>

<script lang="ts">
	import { onMount } from 'svelte';
	import { goto } from '$app/navigation';
	import { resolve } from '$app/paths';

	onMount(() => {
		goto(resolve('/auth/login'), { replaceState: true });
	});
</script>

<svelte:head>
	<title>Vanaila POS</title>
</svelte:head>

<div
	class="app-frame"
	style="display: flex; align-items: center; justify-content: center; min-height: 100vh;"
>
	<p class="muted">Loading workspace...</p>
</div>

<script lang="ts">
	import type { Snippet } from 'svelte';

	type Props = {
		children: Snippet;
		className?: string;
	};

	let { children, className = '' }: Props = $props();
</script>

<section class={`surface ${className}`}>
	{@render children()}
</section>

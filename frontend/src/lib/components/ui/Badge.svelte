<script lang="ts">
	import type { Snippet } from 'svelte';

	type Tone = 'neutral' | 'accent' | 'sun' | 'success' | 'danger';

	type Props = {
		children: Snippet;
		tone?: Tone;
	};

	let { children, tone = 'neutral' }: Props = $props();

	const toneClass = $derived(
		tone === 'accent'
			? 'badge-accent'
			: tone === 'sun'
				? 'badge-sun'
				: tone === 'success'
					? 'badge-success'
					: tone === 'danger'
						? 'badge-danger'
						: ''
	);
</script>

<span class={`badge ${toneClass}`}>
	{@render children()}
</span>

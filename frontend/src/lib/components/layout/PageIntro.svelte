<script lang="ts">
	import Badge from '$components/ui/Badge.svelte';
	import type { ShellBadge } from '$types/navigation';

	type Props = {
		kicker: string;
		title: string;
		description: string;
		badges?: ShellBadge[];
		compact?: boolean;
	};

	let { kicker, title, description, badges = [], compact = false }: Props = $props();
</script>

<section class={compact ? 'surface section-intro' : 'hero-card'}>
	{#if badges.length > 0}
		<div class="cluster">
			{#each badges as badge (badge.label)}
				<Badge tone={badge.tone ?? 'neutral'}>{badge.label}</Badge>
			{/each}
		</div>
	{/if}

	<div class="spotlight">
		<p class="eyebrow">{kicker}</p>
		<h1 class="display-title shell-display">{title}</h1>
		<p class="lead">{description}</p>
	</div>
</section>

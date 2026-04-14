<script lang="ts">
	import { Search } from 'lucide-svelte';
	import Card from '$components/ui/Card.svelte';

	type Props = {
		categories: string[];
		search?: string;
		activeCategory?: string;
		title?: string;
		description?: string;
		searchLabel?: string;
		placeholder?: string;
	};

	let {
		categories,
		search = $bindable(),
		activeCategory = $bindable(),
		title = 'Catalog search',
		description = 'Filter by keyword or category.',
		searchLabel = 'Search the menu',
		placeholder = 'Search by product name'
	}: Props = $props();
</script>

<Card className="catalog-toolbar">
	<div class="section-header">
		<h2 class="section-title">{title}</h2>
		<p class="muted">{description}</p>
	</div>

	<label class="field">
		<span class="muted">{searchLabel}</span>
		<div class="cluster toolbar-search">
			<Search size={18} />
			<input bind:value={search} type="search" {placeholder} />
		</div>
	</label>

	<div class="chip-row">
		{#each categories as category (category)}
			<button
				class={`chip ${category === activeCategory ? 'active' : ''}`}
				onclick={() => (activeCategory = category)}
			>
				{category}
			</button>
		{/each}
	</div>
</Card>

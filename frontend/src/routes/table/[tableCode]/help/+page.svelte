<script lang="ts">
	import { BellRing, HandPlatter, Receipt } from 'lucide-svelte';
	import PageIntro from '$components/layout/PageIntro.svelte';
	import Card from '$components/ui/Card.svelte';

	const helpOptions = [
		{
			title: 'Call staff',
			detail: 'Ask a server to stop by the table for assistance.',
			icon: BellRing
		},
		{
			title: 'Need utensils',
			detail: 'Request extra cutlery, napkins, or serving plates.',
			icon: HandPlatter
		},
		{
			title: 'Question about the bill',
			detail: 'Flag an issue before the guest confirms payment.',
			icon: Receipt
		}
	];
</script>

<svelte:head>
	<title>Table Help | Vanaila POS</title>
</svelte:head>

<div class="page">
	<PageIntro
		compact={true}
		kicker="Help"
		title="Guests should be able to ask for help without leaving the checkout flow."
		description="This route gives the table journey a clear escape hatch for service and billing support."
		badges={[
			{ label: 'Service assist', tone: 'accent' },
			{ label: 'Table-safe', tone: 'sun' }
		]}
	/>

	<Card>
		<div class="section-header">
			<p class="kicker">Service actions</p>
			<h2 class="section-title">What would you like help with?</h2>
		</div>

		<div class="product-grid">
			{#each helpOptions as option (option.title)}
				<div class="product-card">
					<div class="cluster">
						<option.icon size={18} />
						<strong>{option.title}</strong>
					</div>
					<p class="muted">{option.detail}</p>
					<button class="button secondary">Request</button>
				</div>
			{/each}
		</div>
	</Card>
</div>

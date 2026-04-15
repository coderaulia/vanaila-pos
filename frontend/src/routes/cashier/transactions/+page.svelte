<script lang="ts">
	import { Download, RefreshCw } from 'lucide-svelte';
	import Badge from '$components/ui/Badge.svelte';
	import Card from '$components/ui/Card.svelte';
	import { cashierTransactions } from '$mocks/pos';
</script>

<svelte:head>
	<title>Transactions | Vanaila POS</title>
</svelte:head>

<div class="page">
	<section class="split-grid">
		<Card>
			<div class="section-header">
				<p class="kicker">Latest activity</p>
				<h2 class="section-title">Transaction timeline</h2>
			</div>

			<div class="list">
				{#each cashierTransactions as transaction (transaction.id)}
					<div class="product-card">
						<div class="list-row">
							<div>
								<strong>{transaction.title}</strong>
								<div class="meta">{transaction.detail}</div>
							</div>
							<Badge tone={transaction.status === 'Voided' ? 'danger' : 'success'}>
								{transaction.status}
							</Badge>
						</div>
						<div class="list-row">
							<span class="muted">Register A1</span>
							<strong>{transaction.total}</strong>
						</div>
					</div>
				{/each}
			</div>
		</Card>

		<Card>
			<div class="section-header">
				<p class="kicker">Actions</p>
				<h2 class="section-title">End-of-shift tools</h2>
			</div>

			<div class="button-row">
				<button class="button">
					<Download size={16} />
					Export summary
				</button>
				<button class="button secondary">
					<RefreshCw size={16} />
					Refresh feed
				</button>
			</div>

			<div class="list">
				<div class="product-card">
					<strong>Voids need review</strong>
					<p class="muted">Anything marked voided should be acknowledged before register close.</p>
				</div>
				<div class="product-card">
					<strong>Channel breakdown</strong>
					<p class="muted">
						This layout leaves room for cash, card, and QRIS totals once the API wiring lands.
					</p>
				</div>
			</div>
		</Card>
	</section>
</div>

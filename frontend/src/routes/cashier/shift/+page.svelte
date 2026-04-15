<script lang="ts">
	import { CheckCircle2, Wallet } from 'lucide-svelte';
	import Card from '$components/ui/Card.svelte';
	import MetricCard from '$components/ui/MetricCard.svelte';
	import { cashierSummary } from '$mocks/pos';
</script>

<svelte:head>
	<title>Shift | Vanaila POS</title>
</svelte:head>

<div class="page">
	<div class="metric-grid">
		<MetricCard
			label="Store"
			value={cashierSummary.store}
			detail="Assigned cashier register and service zone."
		/>
		<MetricCard
			label="Open tickets"
			value={cashierSummary.openTickets}
			detail="Must be reviewed before shift close."
		/>
		<MetricCard
			label="Sales pace"
			value={cashierSummary.sales}
			detail="Current gross total for the active shift."
		/>
	</div>

	<section class="dashboard-grid">
		<Card>
			<div class="section-header">
				<p class="kicker">Checklist</p>
				<h2 class="section-title">Before ending the shift</h2>
			</div>

			<div class="list">
				<div class="product-card">
					<div class="cluster">
						<CheckCircle2 size={18} />
						<strong>Review held tickets</strong>
					</div>
					<p class="muted">
						Confirm whether parked orders should be resumed, transferred, or canceled.
					</p>
				</div>
				<div class="product-card">
					<div class="cluster">
						<CheckCircle2 size={18} />
						<strong>Verify void approvals</strong>
					</div>
					<p class="muted">
						Any reversal needs manager visibility before the shift is handed over.
					</p>
				</div>
				<div class="product-card">
					<div class="cluster">
						<CheckCircle2 size={18} />
						<strong>Count drawer variance</strong>
					</div>
					<p class="muted">Prepare the final cash note here once backend totals are connected.</p>
				</div>
			</div>
		</Card>

		<Card>
			<div class="section-header">
				<p class="kicker">Cash note</p>
				<h2 class="section-title">Register summary</h2>
			</div>

			<div class="empty-state">
				<div class="cluster">
					<Wallet size={18} />
					<strong>Prepared for reconciliation</strong>
				</div>
				<p class="muted">
					The final closeout form will plug into backend totals later. For now, the UI shape is
					ready for cash counts, notes, and manager signoff.
				</p>
			</div>
		</Card>
	</section>
</div>

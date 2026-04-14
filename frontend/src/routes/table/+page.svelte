<script lang="ts">
	import { QrCode, UtensilsCrossed } from 'lucide-svelte';
	import { resolve } from '$app/paths';
	import ShellFrame from '$components/layout/ShellFrame.svelte';
	import PageIntro from '$components/layout/PageIntro.svelte';
	import Card from '$components/ui/Card.svelte';
	import { publicNavigationItems, tableDemoCode, tableDemoPath } from '$config/app';
	import { tableDemoProfile } from '$mocks/pos';
</script>

<svelte:head>
	<title>Table Self-Checkout | Vanaila POS</title>
</svelte:head>

<ShellFrame
	contextLabel="Guest checkout preview"
	contextTitle="The table flow is a separate customer experience, not a simplified cashier screen."
	contextDescription="Guests should see a calm, self-explanatory menu and checkout journey while the staff POS stays optimized for operations."
	navItems={publicNavigationItems}
	badges={[
		{ label: 'Customer-first', tone: 'accent' },
		{ label: 'QR entry', tone: 'sun' }
	]}
	variant="public"
>
	<div class="page">
		<PageIntro
			compact={true}
			kicker="Table routing"
			title="Start from a scanned table code and land guests in their own session-aware menu flow."
			description="For this phase we’re using a demo table route, but the structure is ready for real QR codes that resolve into `/table/[tableCode]`."
			badges={[
				{ label: `Demo table ${tableDemoProfile.code}`, tone: 'accent' },
				{ label: tableDemoProfile.store, tone: 'sun' }
			]}
		/>

		<section class="split-grid">
			<Card>
				<div class="section-header">
					<p class="kicker">Demo entry</p>
					<h2 class="section-title">Launch the customer flow</h2>
				</div>

				<div class="list">
					<div class="product-card">
						<div class="cluster">
							<QrCode size={18} />
							<strong>QR target</strong>
						</div>
						<p class="muted">
							`{tableDemoPath}` opens the guest menu, cart, checkout, and help routes for table {tableDemoProfile.code}.
						</p>
						<a class="button" href={resolve('/table/[tableCode]', { tableCode: tableDemoCode })}>
							Open table demo
							<UtensilsCrossed size={18} />
						</a>
					</div>
				</div>
			</Card>

			<Card>
				<div class="section-header">
					<p class="kicker">Guest expectations</p>
					<h2 class="section-title">What this flow should feel like</h2>
				</div>

				<div class="list">
					<div class="product-card">
						<strong>Minimal decisions</strong>
						<p class="muted">
							Menu browsing, cart review, and payment should be obvious on mobile in one hand.
						</p>
					</div>
					<div class="product-card">
						<strong>Table-aware</strong>
						<p class="muted">
							Guests should always know which table they’re ordering from and how to request help.
						</p>
					</div>
					<div class="product-card">
						<strong>Staff-safe</strong>
						<p class="muted">
							None of this navigation should leak backoffice actions into the guest experience.
						</p>
					</div>
				</div>
			</Card>
		</section>
	</div>
</ShellFrame>

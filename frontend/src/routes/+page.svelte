<script lang="ts">
	import { resolve } from '$app/paths';
	import {
		ArrowRight,
		Building2,
		ShieldCheck,
		ShoppingBag,
		Smartphone,
		UtensilsCrossed
	} from 'lucide-svelte';
	import ShellFrame from '$components/layout/ShellFrame.svelte';
	import Card from '$components/ui/Card.svelte';
	import MetricCard from '$components/ui/MetricCard.svelte';
	import Badge from '$components/ui/Badge.svelte';
	import {
		featureChecklist,
		launchRoutes,
		publicNavigationItems,
		tableDemoCode
	} from '$config/app';
</script>

<svelte:head>
	<title>Vanaila POS</title>
</svelte:head>

<ShellFrame
	contextLabel="Frontend-first rollout"
	contextTitle="Build the staff POS and table self-checkout as separate experiences from day one."
	contextDescription="Phase 1 sets up role-scoped shells, cleaner menus, and reusable commerce blocks so the cashier, backoffice, and guest flows can evolve independently without changing the visual language you already like."
	navItems={publicNavigationItems}
	badges={[
		{ label: 'Static-ready', tone: 'accent' },
		{ label: 'Frontend foundation', tone: 'sun' }
	]}
	variant="public"
>
	<div class="page">
		<section class="hero-card">
			<div class="spotlight">
				<div class="cluster">
					<Badge tone="accent">SvelteKit static SPA</Badge>
					<Badge tone="sun">Role-scoped shells</Badge>
				</div>
				<p class="eyebrow">Architecture-ready boilerplate</p>
				<h1 class="display-title">
					A <span class="text-gradient">mobile-first</span> cashier flow, a desktop backoffice, and a
					guest-friendly table checkout.
				</h1>
				<p class="lead">
					The frontend is now organized as three distinct experiences instead of one shared demo
					shell: staff operations, management workspaces, and customer self-ordering at the table.
				</p>
			</div>

			<div class="metric-grid">
				<MetricCard
					label="Cashier mode"
					value="1-hand flow"
					detail="Optimized for fast product search, cart building, and payment handoff."
				/>
				<MetricCard
					label="Admin mode"
					value="Desktop analytics"
					detail="Catalog, orders, and reporting now have clearer navigation paths."
				/>
				<MetricCard
					label="Superadmin mode"
					value="Network control"
					detail="Stores, admins, and settings are split into dedicated work areas."
				/>
				<MetricCard
					label="Table checkout"
					value="Guest-first flow"
					detail="Menu, cart, checkout, and help now have their own customer-facing shell."
				/>
			</div>
		</section>

		<section class="role-grid">
			<Card>
				<div class="route-card">
					<div class="cluster">
						<Smartphone size={18} />
						<h2 class="section-title">Cashier terminal</h2>
					</div>
					<p class="muted">
						Touch-friendly order taking, held tickets, transaction review, and shift awareness.
					</p>
					<a class="button" href={resolve('/cashier')}>
						Open cashier UI
						<ArrowRight size={18} />
					</a>
				</div>
			</Card>

			<Card>
				<div class="route-card">
					<div class="cluster">
						<Building2 size={18} />
						<h2 class="section-title">Admin dashboard</h2>
					</div>
					<p class="muted">
						Catalog, orders, and reports now live in separate desktop-first areas.
					</p>
					<a class="button secondary" href={resolve('/admin')}>
						Open admin workspace
						<ArrowRight size={18} />
					</a>
				</div>
			</Card>

			<Card>
				<div class="route-card">
					<div class="cluster">
						<ShieldCheck size={18} />
						<h2 class="section-title">Superadmin hub</h2>
					</div>
					<p class="muted">
						Global oversight is split into overview, stores, admins, and settings surfaces.
					</p>
					<a class="button ghost" href={resolve('/superadmin')}>
						Open control tower
						<ArrowRight size={18} />
					</a>
				</div>
			</Card>

			<Card>
				<div class="route-card">
					<div class="cluster">
						<UtensilsCrossed size={18} />
						<h2 class="section-title">Table self-checkout</h2>
					</div>
					<p class="muted">
						Guests can browse the menu, review their cart, check out, and ask for help at the table.
					</p>
					<a
						class="button secondary"
						href={resolve('/table/[tableCode]', { tableCode: tableDemoCode })}
					>
						Open table demo
						<ArrowRight size={18} />
					</a>
				</div>
			</Card>
		</section>

		<section class="split-grid">
			<Card>
				<div class="section-header">
					<p class="kicker">Launch map</p>
					<h2 class="section-title">Experience routes</h2>
				</div>

				<div class="list">
					{#each launchRoutes as item (item.href)}
						<div class="list-row">
							<div>
								<strong>{item.label}</strong>
								<div class="meta">{item.description}</div>
							</div>
							<a class="badge" href={resolve(item.href)}>{item.href}</a>
						</div>
					{/each}
				</div>
			</Card>

			<Card>
				<div class="section-header">
					<p class="kicker">Foundation</p>
					<h2 class="section-title">Included in this pass</h2>
				</div>

				<div class="pill-grid">
					{#each featureChecklist as feature (feature)}
						<div class="badge badge-accent">{feature}</div>
					{/each}
				</div>
			</Card>
		</section>

		<section class="surface">
			<div class="section-header">
				<p class="kicker">Next step</p>
				<h2 class="section-title">Use the role login or jump straight into the table demo</h2>
				<p class="muted">
					The current frontend stays mock-driven, but the navigation and layouts now match the shape
					of the app we’ll wire into the backend later.
				</p>
			</div>

			<div class="button-row">
				<a class="button" href={resolve('/auth/login')}>
					<ShoppingBag size={18} />
					Go to login
				</a>
				<a
					class="button secondary"
					href={resolve('/table/[tableCode]', { tableCode: tableDemoCode })}
				>
					<UtensilsCrossed size={18} />
					Open table demo
				</a>
			</div>
		</section>
	</div>
</ShellFrame>

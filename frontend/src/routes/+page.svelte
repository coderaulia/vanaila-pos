<script lang="ts">
	import { resolve } from '$app/paths';
	import { ArrowRight, Building2, ShoppingBag, ShieldCheck, Smartphone } from 'lucide-svelte';
	import Card from '$components/ui/Card.svelte';
	import MetricCard from '$components/ui/MetricCard.svelte';
	import Badge from '$components/ui/Badge.svelte';
	import { featureChecklist, navigationItems } from '$config/app';
</script>

<svelte:head>
	<title>Vanaila POS</title>
</svelte:head>

<div class="page">
	<section class="hero-card">
		<div class="spotlight">
			<div class="cluster">
				<Badge tone="accent">SvelteKit static SPA</Badge>
				<Badge tone="sun">Laravel 11 + Sanctum API</Badge>
			</div>
			<p class="eyebrow">Architecture-ready boilerplate</p>
			<h1 class="display-title">
				A <span class="text-gradient">mobile-first</span> cashier flow with desktop command centers behind
				it.
			</h1>
			<p class="lead">
				This starter splits the customer-facing POS terminal from the operational dashboards while
				keeping one shared design language, one API contract, and one deployment story for Hostinger
				shared hosting.
			</p>
		</div>

		<div class="metric-grid">
			<MetricCard
				label="Cashier mode"
				value="1-hand flow"
				detail="Optimized for quick taps, product search, and cart review on smaller screens."
			/>
			<MetricCard
				label="Admin mode"
				value="Desktop analytics"
				detail="Product operations, reporting snapshots, and daily store oversight."
			/>
			<MetricCard
				label="Superadmin mode"
				value="Multi-store control"
				detail="Global settings, admin governance, and network health visibility."
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
					Touch-friendly order taking, payment confirmation, and live cart monitoring.
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
					Product lifecycle management, sales summaries, and staff-aware store reporting.
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
					Store orchestration, admin governance, and platform-level settings across the POS network.
				</p>
				<a class="button ghost" href={resolve('/superadmin')}>
					Open control tower
					<ArrowRight size={18} />
				</a>
			</div>
		</Card>
	</section>

	<section class="split-grid">
		<Card>
			<div class="section-header">
				<p class="kicker">Launch map</p>
				<h2 class="section-title">Primary routes</h2>
			</div>

			<div class="list">
				{#each navigationItems as item (item.href)}
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
				<h2 class="section-title">Included in this scaffold</h2>
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
			<h2 class="section-title">Use the login route to simulate role entry</h2>
			<p class="muted">
				The demo session store seeds a local role so the static frontend can exercise protected
				flows before the Laravel API is wired into real authentication calls.
			</p>
		</div>

		<div class="button-row">
			<a class="button" href={resolve('/auth/login')}>
				<ShoppingBag size={18} />
				Go to login
			</a>
		</div>
	</section>
</div>

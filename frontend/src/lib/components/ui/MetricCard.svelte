<script lang="ts">
	type Props = {
		label: string;
		value: string;
		detail: string;
	};

	let { label, value, detail }: Props = $props();
</script>

<article class="metric-card">
	<div class="metric-label">{label}</div>
	<div class="metric-value">{value}</div>
	<p class="muted">{detail}</p>
</article>

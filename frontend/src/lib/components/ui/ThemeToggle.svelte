<script lang="ts">
	import { MoonStar, SunMedium } from 'lucide-svelte';
	import { theme } from '$stores/theme.svelte';
</script>

<button class="button secondary theme-button" onclick={() => theme.toggle()}>
	{#if theme.current === 'dark'}
		<SunMedium size={16} />
		Light mode
	{:else}
		<MoonStar size={16} />
		Dark mode
	{/if}
</button>

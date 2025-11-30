<script lang="ts">
  import SectionCard from '$components/UI/SectionCard.svelte';
  import { Link } from '@inertiajs/svelte';

  interface Props {
    class?: string;
  }

  let { class: className }: Props = $props();
</script>

<SectionCard
  title="AI Voice Assistant"
  subtitle="Set up and manage your personal AI voice assistant for hands-free workspace interaction."
  class={className}
>
  {#snippet content()}
    <div>
      <Link
        href={route('voice-assistant.show')}
        class="inline-flex items-center gap-2 rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600"
      >
        <svg
          class="h-4 w-4"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"
          />
        </svg>
        Manage Voice Assistant
      </Link>
    </div>
  {/snippet}
</SectionCard>

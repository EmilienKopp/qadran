<script lang="ts">
  interface Props {
    class?: string;
  }

  let { class: className = '' }: Props = $props();
</script>

<div class={className}>
  <h3 class="text-2xl font-bold mb-4">Effortless Time Tracking</h3>
  
  <div class="space-y-4">
    <p class="text-base-content/80">
      Track your time with precision and ease. Our intuitive interface makes clocking in and out 
      as simple as a single click, while providing powerful features for detailed time management.
    </p>

    <div class="space-y-3">
      <div class="flex gap-3 items-start">
        <div class="badge badge-neutral mt-1">✓</div>
        <div>
          <p class="font-semibold">One-Click Clock In/Out</p>
          <p class="text-sm text-base-content/60">Start and stop time tracking instantly from anywhere in the app</p>
        </div>
      </div>

      <div class="flex gap-3 items-start">
        <div class="badge badge-neutral mt-1">✓</div>
        <div>
          <p class="font-semibold">Multi-Project Support</p>
          <p class="text-sm text-base-content/60">Track time across multiple projects and organizations simultaneously</p>
        </div>
      </div>

      <div class="flex gap-3 items-start">
        <div class="badge badge-neutral mt-1">✓</div>
        <div>
          <p class="font-semibold">Automatic Duration Calculation</p>
          <p class="text-sm text-base-content/60">Real-time tracking with automatic time calculations and summaries</p>
        </div>
      </div>

      <div class="flex gap-3 items-start">
        <div class="badge badge-neutral mt-1">✓</div>
        <div>
          <p class="font-semibold">Timezone Aware</p>
          <p class="text-sm text-base-content/60">Accurate time tracking regardless of your location or timezone</p>
        </div>
      </div>
    </div>
  </div>
</div>

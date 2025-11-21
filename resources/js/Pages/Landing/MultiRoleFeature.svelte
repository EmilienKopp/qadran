<script lang="ts">
  interface Props {
    class?: string;
  }

  let { class: className = '' }: Props = $props();
</script>

<div class={className}>
  <h3 class="text-2xl font-bold mb-4">Multi-Role Switching</h3>
  
  <div class="space-y-4">
    <p class="text-base-content/80">
      Work smarter when you wear multiple hats. Our advanced context-switching system adapts the 
      interface to match your current role, making it seamless to work across different organizations 
      and responsibilities.
    </p>

    <div class="space-y-3">
      <div class="flex gap-3 items-start">
        <div class="badge badge-neutral mt-1">✓</div>
        <div>
          <p class="font-semibold">Context-Aware Interface</p>
          <p class="text-sm text-base-content/60">UI automatically adapts based on your active role using strategy patterns</p>
        </div>
      </div>

      <div class="flex gap-3 items-start">
        <div class="badge badge-neutral mt-1">✓</div>
        <div>
          <p class="font-semibold">Instant Role Switching</p>
          <p class="text-sm text-base-content/60">Switch between consultant, developer, manager, or any custom role instantly</p>
        </div>
      </div>

      <div class="flex gap-3 items-start">
        <div class="badge badge-neutral mt-1">✓</div>
        <div>
          <p class="font-semibold">Organization Isolation</p>
          <p class="text-sm text-base-content/60">Keep data and contexts separate across different organizations</p>
        </div>
      </div>

      <div class="flex gap-3 items-start">
        <div class="badge badge-neutral mt-1">✓</div>
        <div>
          <p class="font-semibold">Persistent Context</p>
          <p class="text-sm text-base-content/60">Your role preferences are remembered across sessions</p>
        </div>
      </div>
    </div>
  </div>
</div>

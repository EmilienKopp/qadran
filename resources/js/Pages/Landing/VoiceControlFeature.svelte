<script lang="ts">
  interface Props {
    class?: string;
  }

  let { class: className = '' }: Props = $props();
</script>

<div class={className}>
  <h3 class="text-2xl font-bold mb-4">Look Mommy, No Hands! <span class="badge badge-sm">Beta</span></h3>
  
  <div class="space-y-4">
    <p class="text-base-content/80">
      Control your time tracking hands-free with voice commands. Perfect for when you're deep in code 
      or away from your desk. Just speak naturally to manage your time entries.
    </p>

    <div class="space-y-3">
      <div class="flex gap-3 items-start">
        <div class="badge badge-neutral mt-1">✓</div>
        <div>
          <p class="font-semibold">Natural Language Commands</p>
          <p class="text-sm text-base-content/60">Clock in, out, or log time using conversational voice commands</p>
        </div>
      </div>

      <div class="flex gap-3 items-start">
        <div class="badge badge-neutral mt-1">✓</div>
        <div>
          <p class="font-semibold">Hands-Free Operation</p>
          <p class="text-sm text-base-content/60">Manage time entries without interrupting your workflow</p>
        </div>
      </div>

      <div class="flex gap-3 items-start">
        <div class="badge badge-neutral mt-1">✓</div>
        <div>
          <p class="font-semibold">Voice Transcription</p>
          <p class="text-sm text-base-content/60">Add notes and descriptions to time entries via voice</p>
        </div>
      </div>

      <div class="flex gap-3 items-start">
        <div class="badge badge-neutral mt-1">✓</div>
        <div>
          <p class="font-semibold">Smart Context Recognition</p>
          <p class="text-sm text-base-content/60">Automatically infers project and activity from your commands</p>
        </div>
      </div>
    </div>

    <div class="alert alert-info">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
      <span class="text-sm">Currently in beta. Features may change as we refine the experience.</span>
    </div>
  </div>
</div>

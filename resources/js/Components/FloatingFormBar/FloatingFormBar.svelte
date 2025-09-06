<script lang="ts">
  import { router } from '@inertiajs/svelte';
  import { onMount } from 'svelte';
  
  interface Props {
    errors?: Record<string, string> | null;
    cancelUrl?: string | null;
    submitLabel?: string;
    loading?: boolean;
    progress?: number | null;
    onCancel?: () => void;
    children?: import('svelte').Snippet;
  }

  let { 
    errors = null, 
    cancelUrl = null, 
    submitLabel = 'Submit', 
    loading = false,
    progress = null,
    onCancel = () => {},
    children 
  }: Props = $props();

  // Progress simulation state
  let simulatedProgress = $state(0);
  let progressTimeout: number | null = null;
  let trickleInterval: number | null = null;
  let isSimulating = $state(false);

  onMount(() => {
    // Only set up simulation if no external progress is provided
    if (progress === null) {
      router.on('start', () => {
        startProgressSimulation();
      });

      router.on('finish', (event: any) => {
        finishProgressSimulation(event.detail.visit);
      });
    }

    return () => {
      clearProgressSimulation();
    };
  });

  function startProgressSimulation() {
    if (isSimulating) return;
    
    isSimulating = true;
    simulatedProgress = 0.08; // Start with minimum like NProgress
    
    // Start trickling after 250ms delay (like the example)
    progressTimeout = setTimeout(() => {
      trickleProgress();
    }, 250);
  }

  function trickleProgress() {
    if (!isSimulating) return;
    
    trickleInterval = setInterval(() => {
      if (simulatedProgress < 0.9) {
        // Increment by smaller amounts as we get closer to completion
        let increment;
        if (simulatedProgress < 0.2) increment = 0.1;
        else if (simulatedProgress < 0.5) increment = 0.04;
        else if (simulatedProgress < 0.8) increment = 0.02;
        else increment = 0.005;
        
        simulatedProgress = Math.min(simulatedProgress + increment, 0.9);
      }
    }, 200);
  }

  function finishProgressSimulation(visit: any) {
    clearProgressSimulation();
    
    if (visit.completed) {
      // Complete the progress quickly
      simulatedProgress = 1;
      setTimeout(() => {
        simulatedProgress = 0;
        isSimulating = false;
      }, 300);
    } else {
      // Reset immediately for interrupted/cancelled
      simulatedProgress = 0;
      isSimulating = false;
    }
  }

  function clearProgressSimulation() {
    if (progressTimeout) {
      clearTimeout(progressTimeout);
      progressTimeout = null;
    }
    if (trickleInterval) {
      clearInterval(trickleInterval);
      trickleInterval = null;
    }
  }

  // Calculate error count
  const errorCount = $derived(errors ? Object.keys(errors).length : 0);

  // Get error messages as array
  const errorMessages = $derived(errors ? Object.entries(errors).map(([field, message]) => `${field}: ${message}`) : []);

  // Use external progress if provided, otherwise use simulated progress
  const currentProgress = $derived(progress !== null ? progress : (isSimulating ? simulatedProgress : 0));
  
  // Calculate progress percentage and background style
  const progressPercentage = $derived(currentProgress ? Math.min(100, Math.max(0, currentProgress * 100)) : 0);
  const progressStyle = $derived(`linear-gradient(to right, hsl(var(--su)) ${progressPercentage}%, transparent ${progressPercentage}%)`);

  function handleCancel() {
    if (onCancel) {
      onCancel();
    } else if (cancelUrl) {
      router.visit(cancelUrl);
    } else {
      window.history.back();
    }
  }

  function focusOnError() {
    // Find the first form element with an error
    let firstErrorElement = document.querySelector('[data-error="true"], [data-invalid], .input-error, .textarea-error, .select-error, [aria-invalid], .invalid');
    if(errors && !firstErrorElement) {
      const firstErrorKey = Object.keys(errors)[0];
      console.log('First error key:', firstErrorKey);
      if (firstErrorKey) {
        firstErrorElement = document.querySelector(`[name="${firstErrorKey}"], [id="${firstErrorKey}"]`);
      }
    }
    console.log('Focusing on first error element:', firstErrorElement);

    
    if (firstErrorElement) {
      firstErrorElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
      (firstErrorElement as HTMLElement).focus();
    }
  }
</script>

<div 
  class="card fixed! bottom-12 left-1/2 -translate-x-1/2 max-w-[600px] w-[calc(100%-2rem)] z-[999] bg-transparent dark:border dark:border-zinc-300 shadow-lg dark:shadow-slate-600/20 transition-all duration-300 relative overflow-hidden"
>
  <!-- Progress overlay -->
  {#if currentProgress > 0}
    <div 
      class="absolute top-0 left-0 h-full bg-green-500/30 transition-all duration-300 ease-out z-0"
      style="width: {progressPercentage}%"
    ></div>
  {/if}
  
  <div class="card-body p-3 flex flex-row justify-between items-center relative z-10">
    <!-- Error Display -->
    <div class="pl-2">
      {#if errorCount > 0}
        <button 
          type="button" 
          class="text-left cursor-pointer btn btn-ghost btn-sm p-1 h-auto min-h-0"
          onclick={focusOnError}
          title={errorMessages.join('\n')}
        >
          <div class="flex flex-col items-start">
            <span class="text-error text-sm font-medium">
              {errorCount} error{errorCount !== 1 ? 's' : ''} found
            </span>
            <span class="text-base-content/60 text-xs">
              Click here to view
            </span>
          </div>
        </button>
      {/if}
    </div>

    <!-- Action Buttons -->
    <div class="flex shrink-0 space-x-3">
      {#if children}
        {@render children()}
      {:else}
        <button 
          type="button" 
          class="btn btn-ghost"
          onclick={handleCancel}
          disabled={loading}
        >
          Cancel
        </button>
        
        <button 
          type="submit" 
          class="btn btn-primary"
          disabled={loading}
        >
          {#if loading}
            <span class="loading loading-spinner loading-sm mr-2"></span>
            Saving...
          {:else}
            {submitLabel}
          {/if}
        </button>
      {/if}
    </div>
  </div>
</div>

<style>
  /* Custom styles for better floating appearance */
  .card {
    backdrop-filter: blur(8px);
    border: 1px solid hsl(var(--border-color, var(--b3)));
  }
  
  @media (prefers-color-scheme: dark) {
    .card {
      background-color: hsl(var(--b2)) !important;
    }
  }
</style>
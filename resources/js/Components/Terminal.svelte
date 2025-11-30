<script lang="ts">
  import { onDestroy, onMount } from 'svelte';

  let { output = '' }: { output?: string } = $props();
  let command = $state('project:list');
  let isRunning = $state(false);
  let eventSource: EventSource | null = $state(null);
  let outputContainer: HTMLDivElement;
  let cmdInput: HTMLInputElement;

  export function focusInput() {
    setTimeout(() => {
      cmdInput?.focus();
    }, 100);
  }

  onMount(() => {
    if (outputContainer) {
      outputContainer.scrollTop = outputContainer.scrollHeight;
    }
  });

  onDestroy(() => {
    if (eventSource) {
      eventSource.close();
    }
  });

  function closeEventSource() {
    if (eventSource) {
      eventSource.close();
      eventSource = null;
    }
  }

  async function submit(e: Event) {
    e.preventDefault();

    if (!command.trim()) return;

    closeEventSource();

    isRunning = true;
    output = ''; // Reset output as string

    try {
      const url = new URL(route('api.artisan'), window.location.origin);
      url.searchParams.set('command', command);
      url.searchParams.set('use_stream', 'true');
      command = ''; // Clear command input

      eventSource = new EventSource(url.toString());

      eventSource.onopen = () => {
        console.log('✅ EventSource connection opened');
      };

      eventSource.onmessage = (event) => {
        try {
          const data = JSON.parse(event.data);

          if (data.message) {
            output += data.message;
            // Auto-scroll to bottom
            setTimeout(() => {
              if (outputContainer) {
                outputContainer.scrollTop = outputContainer.scrollHeight;
              }
            }, 10);
          }

          if (data.done) {
            isRunning = false;
            closeEventSource();

            if (data.error || data.exitCode !== 0) {
              output += '\n❌ Command failed!';
            } else {
              output += '\n✅ Command completed!';
            }
            // Refocus:
            setTimeout(() => {
              cmdInput?.focus();
            }, 100);
          }
        } catch (err) {
          console.error('Failed to parse event data:', err);
        }
      };

      eventSource.onerror = (err) => {
        console.error('❌ EventSource error:', err);
        isRunning = false;
        output += '\n❌ Connection error occurred';
        closeEventSource();
      };
    } catch (error) {
      console.error('Error:', error);
      isRunning = false;
      output = `❌ Error: ${error}`;
    }
  }
</script>

<style>
  pre {
    white-space: pre-wrap;
    word-wrap: break-word;
  }
</style>

<form onsubmit={submit}>
  <div class="flex gap-2 mb-4">
    <button
      class="btn btn-primary"
      type="submit"
      disabled={isRunning || !command.trim()}
    >
      {#if isRunning}
        <span class="loading loading-spinner loading-sm"></span>
        Running...
      {:else}
        Run Command
      {/if}
    </button>

    {#if isRunning}
      <button
        class="btn btn-error"
        type="button"
        onclick={() => {
          closeEventSource();
          isRunning = false;
          output += '\n⚠️ Cancelled by user';
        }}
      >
        Cancel
      </button>
    {/if}
  </div>

  <div
    class="mockup-code max-h-96 overflow-y-auto pb-4"
    bind:this={outputContainer}
    onclick={() => cmdInput?.focus()}
    role="textbox"
    tabindex="-1"
    onkeydown={(e) => {
      if (e.key === 'Escape') {
        cmdInput?.blur();
      }
    }}
  >
    {#if output}
      {@const lines = output.split('\n')}
      {@const formattedOutput = lines
        .map((line) => (line.trim() ? line : ' '))
        .join('\n')}
      {@const p = formattedOutput}
      {#each lines as line}
        <pre data-prefix="> "><code>{line}</code></pre>
      {/each}
    {:else}
      <pre data-prefix="$"><code class=""
          >Output will appear here...</code
        ></pre>
    {/if}
    <pre data-prefix="$" class="flex"><input
        bind:this={cmdInput}
        class="border-0 w-full inline-block"
        type="text"
        bind:value={command}
        disabled={isRunning}
      /></pre>
  </div>
</form>

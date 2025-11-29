<script lang="ts">
  import { onMount } from 'svelte';
  import MiniButton from '$components/Buttons/MiniButton.svelte';
  import { twMerge } from 'tailwind-merge';
  import clsx from 'clsx';

  interface Props {
    title?: string;
    opened?: boolean;
    class?: string;
    onsubmit?: (e: SubmitEvent) => void;
    children?: import('svelte').Snippet;
    'title-right'?: import('svelte').Snippet;
    buttons?: import('svelte').Snippet;
    transitionName?: string;
    [key: string]: any;
  }

  let {
    title = '',
    opened = $bindable(false),
    class: className = '',
    onsubmit,
    children,
    'title-right': titleRight,
    buttons,
    transitionName = '',
    ...rest
  }: Props = $props();

  let dialog = $state<HTMLDialogElement | undefined>();

  $effect(() => {
    if (opened) {
      dialog?.showModal();
    } else {
      dialog?.close();
    }
  });

  export function open() {
    dialog?.showModal();
    opened = true;
  }

  export function close() {
    dialog?.close();
    opened = false;
  }
</script>

<dialog class="modal" bind:this={dialog}>
  <form
    onsubmit={(e) => {
      e.preventDefault();
      onsubmit?.(e);
    }}
    class={'modal-box w-5/6 max-w-5xl ' +
      twMerge(
        clsx(className, transitionName && `transition-${transitionName}`)
      )}
    {...rest}
  >
    <h3 class="font-bold text-lg flex items-center justify-between">
      {title}
      {#if titleRight}
        {@render titleRight()}
      {/if}
    </h3>
    <div class="py-2 text-xs flex items-center justify-between">
      Press ESC key or click the button to the right to close
      <MiniButton color="warning" onclick={close}>close</MiniButton>
    </div>
    <div class="my-4">
      {#if children}
        {@render children()}
      {/if}
    </div>
    {#if buttons}
      {@render buttons()}
    {/if}
  </form>
  <form method="dialog" class="modal-backdrop">
    <button>close</button>
  </form>
</dialog>

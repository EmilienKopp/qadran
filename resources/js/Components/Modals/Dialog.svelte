<script lang="ts">
    import { onMount } from 'svelte';
    import MiniButton from '$components/Buttons/MiniButton.svelte';
    import { twMerge } from 'tailwind-merge';

    interface Props {
        title?: string;
        open?: boolean;
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
        open = $bindable(false),
        class: className = "",
        onsubmit,
        children,
        'title-right': titleRight,
        buttons,
        transitionName = "",
        ...rest
    }: Props = $props();

    let dialog = $state<HTMLDialogElement | undefined>();

    onMount(() => {
        dialog?.addEventListener('click', (e: Event) => {
            // Detect click outside of dialog (in the modal overlay/backdrop)
            const target = e.target as HTMLElement;
            if(target == dialog && !target.classList.contains('modal-box')) {
                close(e);
            }
        });
    });

    function close(e: Event) {
        if(e.type == 'click' || (e.type == 'keypress' && (e as KeyboardEvent).key == 'Escape')) {
            dialog?.close();
            open = false;
        }
    }

    $effect(() => {
        if(open) {
            dialog?.showModal();
        } else {
            dialog?.close();
        }
    });
</script>

<dialog class="modal" bind:this={dialog}>
    <form 
        onsubmit={(e) => {
            e.preventDefault();
            onsubmit?.(e);
        }} 
        class={twMerge("modal-box border border-gray-400 flex flex-col w-full", className)} 
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
</dialog>

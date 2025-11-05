<script lang="ts">
    import BaseButton from "./BaseButton.svelte";
    import type { ButtonSize, ButtonShape, ButtonVariant } from "$types/index";
    
    interface Props {
        href?: string;
        loading?: boolean;
        size?: ButtonSize;
        variant?: ButtonVariant;
        shape?: ButtonShape;
        onclick?: (e: MouseEvent) => void;
        children?: import('svelte').Snippet;
        [key: string]: any;
    }

    let {
        href = undefined,
        loading = false,
        size = "md",
        variant = "outline",
        shape = undefined,
        onclick,
        children,
        ...rest
    }: Props = $props();
</script>

<BaseButton {size} {shape} {variant} {loading} {href} {onclick} {...rest}>
    {#if children}
        {@render children()}
    {/if}
</BaseButton>

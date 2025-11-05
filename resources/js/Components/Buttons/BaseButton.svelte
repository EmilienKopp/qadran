<script lang="ts">
    import { twMerge } from "tailwind-merge";
    import { Link } from '@inertiajs/svelte';
    import type { ButtonShape, ButtonVariant, ButtonSize } from "$types/index";

    interface Props {
        href?: string;
        loading?: boolean;
        size?: ButtonSize;
        variant?: ButtonVariant;
        shape?: ButtonShape;
        class?: string;
        onclick?: (e: MouseEvent) => void;
        children?: import('svelte').Snippet;
        [key: string]: any;
    }

    let {
        href = undefined,
        loading = false,
        size = "md",
        variant = "primary",
        shape = undefined,
        class: className = "",
        onclick,
        children,
        ...rest
    }: Props = $props();

    const sizes: Record<ButtonSize, string> = {
        xs: "btn-xs",
        sm: "btn-sm",
        md: "",
        lg: "btn-lg",
        xl: "btn-xl",
    };

    const variants: Record<ButtonVariant, string> = {
        primary: "btn-primary",
        secondary: "btn-secondary",
        danger: "btn-danger",
        ghost: "btn-ghost",
        outline: "btn-outline",
        link: "btn-link",
        glass: "glass",
        success: "btn-success",
        warning: "btn-warning",
        info: "btn-info",
        accent: "btn-accent",
        error: "btn-error",
    }

    const shapes: Record<string, string> = {
        wide: "btn-wide",
        circle: "btn-circle",
        square: "btn-square",
        block: "btn-block",
    }

    const classes = $derived(twMerge(
        "btn",
        variants[variant],
        sizes[size],
        shape ? shapes[shape] : '',
        "min-h-fit h-8",
        className
    ));
</script>

{#if !href}
    <button
        type="button"
        {onclick}
        {...rest}
        class={classes}
    >
        {#if !loading}
            {#if children}
                {@render children()}
            {/if}
        {:else}
            <span class="loading loading-dots loading-sm"></span>
        {/if}
    </button>
{:else}
    <Link {href} {...rest} class={classes} {onclick}>
        {#if !loading}
            {#if children}
                {@render children()}
            {/if}
        {:else}
            <span class="loading loading-dots loading-lg"></span>
        {/if}
    </Link>
{/if}

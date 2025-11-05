<script lang="ts">
    import { twMerge } from "tailwind-merge";

    interface Props {
        color?: "blue" | "red" | "green" | "yellow" | "gray" | "indigo" | "purple" | "pink" | "orange" | "teal" | "cyan" | "white" | "black" | "ghost"
            | "primary" | "secondary" | "accent" | "neutral" | "info" | "success" | "warning" | "error" | "base-100" | "base-200";
        type?: "button" | "submit" | "reset";
        href?: string;
        class?: string;
        onclick?: (e: MouseEvent) => void;
        children?: import('svelte').Snippet;
        [key: string]: any;
    }

    let {
        color = "primary",
        type = "button",
        href = "",
        class: className = "",
        onclick,
        children,
        ...rest
    }: Props = $props();

    const colorMap: Record<string, string> = {
        primary: "btn-primary",
        secondary: "btn-secondary",
        accent: "btn-accent",
        neutral: "btn-neutral",
        info: "btn-info",
        success: "btn-success",
        warning: "btn-warning",
        error: "btn-error",
        ghost: "btn-ghost",
        blue: "bg-blue-500 text-white hover:bg-blue-600",
        red: "bg-red-500 text-white hover:bg-red-600",
        green: "bg-green-500 text-white hover:bg-green-600",
        yellow: "bg-yellow-500 text-white hover:bg-yellow-600",
        gray: "bg-gray-500 text-white hover:bg-gray-600",
        indigo: "bg-indigo-500 text-white hover:bg-indigo-600",
        purple: "bg-purple-500 text-white hover:bg-purple-600",
        pink: "bg-pink-500 text-white hover:bg-pink-600",
        orange: "bg-orange-500 text-white hover:bg-orange-600",
        teal: "bg-teal-500 text-white hover:bg-teal-600",
        cyan: "bg-cyan-500 text-white hover:bg-cyan-600",
        white: "bg-white text-black hover:bg-gray-100",
        black: "bg-black text-white hover:bg-gray-800",
        "base-100": "bg-base-100",
        "base-200": "bg-base-200",
    };

    const css = $derived(twMerge(
        "border rounded px-2 max-h-6 text-sm capitalize",
        colorMap[color],
        className
    ));
</script>

{#if !href}
    <button {type} class={css} {onclick} {...rest}>
        {#if children}
            {@render children()}
        {/if}
    </button>
{:else}
    <a role="button" {href} class={css} {onclick} {...rest}>
        {#if children}
            {@render children()}
        {/if}
    </a>
{/if}

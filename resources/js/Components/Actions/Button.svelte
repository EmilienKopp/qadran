<script lang="ts">
  import clsx from 'clsx';
  import { twMerge } from 'tailwind-merge';

  interface Props {
    variant?: string;
    children?: import('svelte').Snippet;
    href?: string;
    onclick?: (e: MouseEvent) => void;
    loading?: boolean;
    [key: string]: any;
  }

  let {
    variant = 'primary',
    children,
    onclick,
    href,
    loading,
    ...rest
  }: Props = $props();
</script>

{#if href}
  <a
    {href}
    class={twMerge(
      'btn',
      clsx({
        'btn-error': variant === 'danger',
        'btn-primary': variant === 'primary',
        'btn-secondary': variant === 'secondary',
        'btn-accent': variant === 'accent',
        'btn-outline': variant === 'outline-solid',
        'btn-link': variant === 'link',
      }),
      rest.class
    )}
  >
    {#if loading}
      <span class="loading loading-spinner"></span>
    {/if}
    {@render children?.()}
  </a>
{:else}
  <button
    disabled={loading}
    {...rest}
    {onclick}
    class={twMerge(
      'btn',
      clsx({
        'btn-error': variant === 'danger',
        'btn-primary': variant === 'primary',
        'btn-secondary': variant === 'secondary',
        'btn-accent': variant === 'accent',
        'btn-outline': variant === 'outline-solid',
        'btn-link': variant === 'link',
      }),
      rest.class
    )}
  >
    {#if loading}
      <span class="loading loading-spinner"></span>
    {/if}
    {@render children?.()}
  </button>
{/if}

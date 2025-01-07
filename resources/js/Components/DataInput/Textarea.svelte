<script lang="ts">
    import { twMerge } from 'tailwind-merge'
    export let label: string = ''
    export let required: boolean = false
    export let value: string = ''
</script>

<div class="form-control w-full mb-4">
    {#if label}
    <label class="label" for={$$restProps.id}>
        <span class="label-text flex items-center">
            {label}
            {#if required}
                <span class="text-error
                ml-1">*</span>
            {/if}
        </span>
    </label>
    {/if}
    <textarea
        class={twMerge('textarea textarea-bordered', $$restProps.class)}
        bind:value
        on:change
        on:input
        {...$$restProps}
    ></textarea>
</div>
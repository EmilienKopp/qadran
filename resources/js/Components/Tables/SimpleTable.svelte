<script lang="ts">
    import Button from "$components/Actions/Button.svelte";
    import { resolveNestedValue } from "$lib/utils/objects";
    import { twMerge } from "tailwind-merge";
    import type { Header, PopoverList, DropdownAction } from './types';
    
    interface Props {
        data?: any[];
        title?: string;
        headers?: Header[];
        popovers?: PopoverList;
        classes?: { [key: string]: string };
        actions?: DropdownAction[];
    }

    let {
        data = [],
        title = "Data Table",
        headers = [{ label: "Item", key: "name" }],
        popovers = {},
        classes = {},
        actions = []
    }: Props = $props();
</script>

<div class="overflow-x-auto">
    <table class="table table-hover w-full table-md">
        <thead>
            <tr>
                {#each headers as header}
                    <th>{header.label}</th>
                {/each}
                {#if actions.length > 0}
                    <th>Actions</th>
                {/if}
            </tr>
        </thead>
        <tbody>
            {#each data ?? [] as item}
                <tr>
                    {#each headers as header}
                        {@const unformatted = resolveNestedValue(item, header.key) ?? ""}
                        {@const transformed = header.transform ? header.transform(unformatted) : unformatted}
                        {@const value = header.format ? header.format(transformed) : transformed}
                        
                        <td>
                            <span class={twMerge(popovers[header.key] ? "cursor-pointer" : "", classes[header.key])}>
                                {#if header.route && item.id}
                                    <Button variant="link" href={`/${header.route}/${item.id}`}>
                                        {value}
                                    </Button>
                                {:else if header.href}
                                    <Button variant="link" href={header.href}>
                                        {value}
                                    </Button>
                                {:else if header.asBadge}
                                    <span class="badge">{value}</span>
                                {:else}
                                    {value}
                                {/if}
                            </span>
                            
                            {#if popovers[header.key] != undefined}
                                {@const PopoverComponent = popovers[header.key].component}
                                <!-- Popover functionality can be implemented when needed -->
                                <!-- For now, we'll leave this as a placeholder -->
                                <div class="tooltip" data-tip="Details available">
                                    <PopoverComponent 
                                        data={resolveNestedValue(item, popovers[header.key].prop ?? "")}
                                    />
                                </div>
                            {/if}
                        </td>
                    {/each}
                    
                    {#if actions.length > 0}
                        <td>
                            <div class="flex gap-2">
                                {#each actions as action}
                                    <Button 
                                        variant="secondary" 
                                        href={action.href} 
                                        onclick={() => action.action?.(item)}
                                        disabled={action.disabled}
                                        class={action.classes}
                                    >
                                        {action.label}
                                    </Button>
                                {/each}
                            </div>
                        </td>
                    {/if}
                </tr>
            {/each}
        </tbody>
    </table>
</div>
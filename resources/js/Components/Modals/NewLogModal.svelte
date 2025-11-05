<script lang="ts">
    import PrimaryButton from "$components/Buttons/PrimaryButton.svelte";
    import SecondaryButton from "$components/Buttons/SecondaryButton.svelte";
    import InputLabel from "$components/DataInput/InputLabel.svelte";
    import Select from "$components/DataInput/Select.svelte";
    import { toast } from "$lib/stores";
    import { page, useForm } from "@inertiajs/svelte";
    import dayjs from "dayjs";
    import timezone from 'dayjs/plugin/timezone';
    import utc from 'dayjs/plugin/utc';
    import Dialog from "./Dialog.svelte";
    
    dayjs.extend(utc);
    dayjs.extend(timezone);

    interface Props {
        open?: boolean;
    }

    let {
        open = $bindable(false),
    }: Props = $props();

    const form = useForm({
        user_id: page.auth.user.id,
        project_id: null,
        in_time: dayjs(page.date).format('YYYY-MM-DD') + 'T10:00:00',
        out_time: dayjs(page.date).format('YYYY-MM-DD') + 'T18:00:00',
        date: dayjs(page.query?.date).format('YYYY-MM-DD'),
        timezone: Intl.DateTimeFormat().resolvedOptions().timeZone
    });

    function handleSubmit() {
        console.log($form);
        $form.post('/timelog/store', {
            onSuccess: () => {
                open = false;
                toast.success('Log saved successfully.');
            },
            onError: () => {
                toast.error('Error saving log.');
                console.log($form.errors);
            }
        });
    }
</script>

<Dialog title="Create a new log" onsubmit={handleSubmit} bind:open>
    <div class="flex flex-col space-y-4">
        <div class="flex flex-col space-y-2">
            <InputLabel value="In Time" for="in_time">
                <input class="bg-base-100 form-control" type="datetime-local" id="in_time" name="in_time" bind:value={$form.in_time} />
            </InputLabel>
            {#if $form.errors.in_time}
                <p class="text-red-500 text-sm">{$form.errors.in_time}</p>
            {/if}
        </div>
        <div class="flex flex-col space-y-2">
            <InputLabel value="Out Time" for="out_time">
                <input class="bg-base-100 form-control" type="datetime-local" id="out_time" name="out_time" bind:value={$form.out_time} />
            </InputLabel>
            {#if $form.errors.out_time}
                <p class="text-red-500 text-sm">
                    {$form.errors.out_time}
                </p>
            {/if}
        </div>
        <div class="flex flex-col space-y-2">
            <InputLabel value="Project" for="project_id">
                <Select required id="project_id" name="project_id" bind:value={$form.project_id}>
                    {#each page?.projects ?? [] as project}
                        <option value={project.id}>{project.name}</option>
                    {/each}
                </Select>
            </InputLabel>
            {#if $form.errors.project_id}
                <p class="text-red-500 text-sm">{$form.errors.project_id}</p>
            {/if}
        </div>
        <div class="flex flex-row space-x-4">
            <PrimaryButton type="submit" loading={$form.processing}>Save</PrimaryButton>
            <SecondaryButton type="button" onclick={() => open = false}>Cancel</SecondaryButton>
        </div>
    </div>
</Dialog>

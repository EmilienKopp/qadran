<script lang="ts">
    import PrimaryButton from "$components/Buttons/PrimaryButton.svelte";
    import OutlineButton from "$components/Buttons/OutlineButton.svelte";
    import InputLabel from "$components/DataInput/InputLabel.svelte";
    import Select from "$components/DataInput/Select.svelte";
    import { toast } from "$lib/stores";
    import { useForm } from "@inertiajs/svelte";
    import dayjs from "dayjs";
    import timezone from 'dayjs/plugin/timezone';
    import utc from 'dayjs/plugin/utc';
    import Dialog from "./Dialog.svelte";
  import { getPage } from "$lib/inertia";

    
    dayjs.extend(utc);
    dayjs.extend(timezone);

    interface Props {
        open?: boolean;
    }

    let {
        open = $bindable(false),
    }: Props = $props();

    const page = getPage();

    const form = useForm({
        user_id: page.props.auth.user.id,
        project_id: null,
        in: dayjs(page.props.date as string).format('YYYY-MM-DD') + 'T10:00:00',
        out: dayjs(page.props.date as string).format('YYYY-MM-DD') + 'T18:00:00',
        date: dayjs(page.props.query?.date as string).format('YYYY-MM-DD'),
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
            <InputLabel value="In Time" for="in">
                <input class="bg-base-100 form-control" type="datetime-local" id="in" name="in" bind:value={$form.in} />
            </InputLabel>
            {#if $form.errors.in}
                <p class="text-red-500 text-sm">{$form.errors.in}</p>
            {/if}
        </div>
        <div class="flex flex-col space-y-2">
            <InputLabel value="Out Time" for="out">
                <input class="bg-base-100 form-control" type="datetime-local" id="out" name="out" bind:value={$form.out} />
            </InputLabel>
            {#if $form.errors.out}
                <p class="text-red-500 text-sm">
                    {$form.errors.out}
                </p>
            {/if}
        </div>
        <div class="flex flex-col space-y-2">
            <InputLabel value="Project" for="project_id">
                <Select required id="project_id" name="project_id" bind:value={$form.project_id}>
                    {#each page?.props?.projects ?? [] as project}
                        <option value={project.id}>{project.name}</option>
                    {/each}
                </Select>
            </InputLabel>
            {#if $form.errors.project_id}
                <p class="text-red-500 text-sm">{$form.errors.project_id}</p>
            {/if}
        </div>
        <div class="grid grid-cols-2 gap-4 pt-4">
          <OutlineButton type="button" onclick={() => open = false}>Cancel</OutlineButton>
          <PrimaryButton type="submit" loading={$form.processing}>Save</PrimaryButton>
        </div>
    </div>
</Dialog>

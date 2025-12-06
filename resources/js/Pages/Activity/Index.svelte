<script lang="ts">
    import MonthTable from "$components/Tables/MonthTable.svelte";
    import AuthenticatedLayout from "$layouts/AuthenticatedLayout.svelte";
    import type { DailyLog } from "$models";

    interface Props {
        dailyLogs?: Record<string, DailyLog[]>;
        date?: Date | string;
    }

    let {
        dailyLogs = {},
        date = new Date(),
    }: Props = $props();

    

</script>

<AuthenticatedLayout>
    <div class="w3/4 mx-auto">
        <MonthTable headers={["Date", "Project", "Activity", "Duration"]} data={dailyLogs} {date} />
    </div>
</AuthenticatedLayout>


<script lang="ts">
    import { Duration } from "$lib/utils/duration";
    import { twMerge } from "tailwind-merge";

    interface Props {
        activity: any;
    }

    let { activity }: Props = $props();

</script>

<div class="flex flex-row gap-2">
    <div>&nbsp;</div>
    {activity.task_category?.name} - {Duration.toHrMinString(activity.duration)}
</div>


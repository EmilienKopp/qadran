<script lang="ts">
    import { Duration } from "$lib/utils/duration";

    interface Props {
        activity: any;
        max?: number;
        parentTotal?: number;
        safetyOn?: boolean;
        onhourkeydown?: (detail: { key: string }) => void;
        onminutekeydown?: (detail: { key: string }) => void;
    }

    let {
        activity = $bindable(),
        max = Number.MAX_SAFE_INTEGER,
        parentTotal = 0,
        safetyOn = true,
        onhourkeydown,
        onminutekeydown,
    }: Props = $props();

    let hours = $state(Duration.getHours(activity.duration));
    let minutes = $state(Duration.getMinutes(activity.duration));

    let hoursInput = $state<HTMLInputElement | undefined>();
    let minutesInput = $state<HTMLInputElement | undefined>();

    // Update hours and minutes when activity.duration changes
    $effect(() => {
        hours = Duration.getHours(activity.duration);
        minutes = Duration.getMinutes(activity.duration);
    });

    function hoursChangeHandler(e: Event | KeyboardEvent) {
        const target = e.target as HTMLInputElement;
        hours = parseInt(target.value);
        
        if(target.value.toString().length > 2) {
            hours = target.value.toString().slice(0,2) == '00' ? 0 : parseInt(target.value.toString().slice(0,2));
            minutesInput?.focus();
            if (minutesInput) {
                minutesInput.value = (e as InputEvent).data ?? '0';
                minutesInput.dispatchEvent(new Event('input'));
            }
        } else if (hours > 23) {
            console.log("hours", hours, "minutes", minutes);
            hours = 23;
            minutesInput?.focus();
            minutesInput?.dispatchEvent(new Event('input'));
        } else if (hours < 0) {
            console.log("hours negative");
            hours = 0;
        }
        activity.duration = hours * 3600 + minutes * 60;
    }

    function minutesChangeHandler(e: Event | KeyboardEvent) {
        const target = e.target as HTMLInputElement;
        minutes = parseInt(target.value);
        
        if(target.value.toString().length > 2 || minutes > 59) {
            hours += Math.floor(minutes / 60);
            minutes = minutes % 60;
        }
        activity.duration = hours * 3600 + minutes * 60;
    }

    const isOverLimit = $derived(safetyOn && parentTotal > max);
</script>

<div class="flex gap-1 items-center">
    <label class="flex items-center gap-1">
        <input 
            bind:this={hoursInput} 
            type="number" 
            class="input input-bordered" 
            class:border-red-500={isOverLimit}
            min="0" 
            max="23" 
            step="1" 
            name="hours"
            value={hours}
            onfocus={(e) => hoursInput?.select()}
            onkeydown={(e) => onhourkeydown?.({ key: e.key })}
            oninput={hoursChangeHandler}
        /> hr
    </label>

    <label class="flex items-center gap-1">
        <input 
            bind:this={minutesInput} 
            type="number" 
            class="input input-bordered" 
            class:border-red-500={isOverLimit}
            min="0" 
            max="60" 
            step="1" 
            name="minutes"
            value={minutes}
            onfocus={(e) => minutesInput?.select()}
            onkeydown={(e) => onminutekeydown?.({ key: e.key })}
            oninput={minutesChangeHandler}
        /> min
    </label>
</div>

<script lang="ts">
  import Pikaday from 'pikaday';
  import dayjs from 'dayjs';
  import { onMount } from 'svelte';

  interface CalendarShortEntry {
    date: string;
    title: string;
    entry_count?: number;
  }

  interface Props {
    value: string | Date;
    daysWithEntries?: CalendarShortEntry[];
    onchange?: (date: string) => void;
    class?: string;
  }

  let {
    value,
    daysWithEntries = [],
    onchange,
    class: className = '',
  }: Props = $props();

  let inputElement: HTMLInputElement;
  let picker: Pikaday | null = null;

  function highlightDaysWithEntries() {
    if (!picker?.el) {
      return;
    }

    daysWithEntries.forEach((obj: CalendarShortEntry) => {
      const dateObj = dayjs(obj.date);
      const day = dateObj.date();
      const month = dateObj.month();
      const year = dateObj.year();

      const dayElement = picker?.el?.querySelector(
        `[data-pika-day="${day}"][data-pika-month="${month}"][data-pika-year="${year}"]`
      ) as HTMLButtonElement;

      if (dayElement) {
        dayElement.classList.add('pika-has-entries');
        dayElement.title = obj.title;
      }
    });
  }

  onMount(() => {
    if (inputElement) {
      picker = new Pikaday({
        field: inputElement,
        onSelect: (date: Date) => {
          const formattedDate = dayjs(date).format('YYYY-MM-DD');
          onchange?.(formattedDate);
        },
        onDraw: () => {
          highlightDaysWithEntries();
        },
      });

      return () => {
        picker?.destroy();
      };
    }
  });

  let formattedValue = $derived(dayjs(value).format('YYYY-MM-DD'));
</script>

<input
  type="text"
  class="input pika-single mt-1 {className}"
  bind:this={inputElement}
  value={formattedValue}
  readonly
/>


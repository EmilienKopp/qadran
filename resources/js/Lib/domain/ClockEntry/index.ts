import { ClockEntryBase } from '$lib/models/ClockEntry';
import { InertiaForm } from '$lib/inertia';
import { router } from '@inertiajs/svelte';
import { toaster } from '$components/Feedback/Toast/ToastHandler.svelte';

export class ClockEntry extends ClockEntryBase {

  static async delete(entry: ClockEntry) {
    router.delete(route('clock-entry.destroy', entry.id), {
      onSuccess: () => {
        toaster.success('Clock entry deleted successfully');
      },
      onError: (errors: Record<string, string>) => {
        toaster.error('An error occurred while deleting the clock entry');
        console.log(errors);
      },
    });
  }

  static async push(form: InertiaForm<Partial<ClockEntry>>) {
      form.post(route('clock-entry.store'), {
        onSuccess: () => {
          toaster.success('Clock entry created successfully');
          form.reset();
        },
        onError: (errors: Record<string, string>) => {
          toaster.error('An error occurred while creating the clock entry');
          console.log(errors);
        },
      });
  }
}
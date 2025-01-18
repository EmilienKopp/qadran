import { ClockEntryBase } from '$lib/models/ClockEntry';
import { ClockInParams } from './types';
import { InertiaForm } from '$lib/inertia';
import { router } from '@inertiajs/svelte';

export class ClockEntry extends ClockEntryBase {

  static async clockIn(form: InertiaForm<ClockEntry>) {
    return form.post(route('clock-in'), {
      onSuccess: () => router.replace('/dashboard'),
    });
  }
}
import { router } from "@inertiajs/svelte";
import { toaster } from "$components/Feedback/Toast/ToastHandler.svelte";
import { ReportBase } from "$lib/models/Report";

export class Report extends ReportBase {
  
  static delete(report: Report) {
    if(confirm('Are you sure you want to delete this report?')) {
      router.delete(route('report.destroy', report.id), {
        preserveScroll: true,
        onSuccess: () => {
          toaster.success('Report deleted successfully');
        },
        onError: () => {
          toaster.error('Failed to delete report');
        }
      });
    }
  }
  
}
import { ProjectBase } from "$lib/models/Project";
import { router } from "@inertiajs/svelte";
import { toaster } from "$components/Feedback/Toast/ToastHandler.svelte";

export class Project extends ProjectBase {
  
  static delete(project: Project) {
    if(confirm('Are you sure you want to delete this project?')) {
      router.delete(route('project.destroy', project.id), {
        preserveScroll: true,
        onSuccess: () => {
          toaster.success('Project deleted successfully');
        },
        onError: () => {
          toaster.error('Failed to delete project');
        }
      });
    }
  }
  
}
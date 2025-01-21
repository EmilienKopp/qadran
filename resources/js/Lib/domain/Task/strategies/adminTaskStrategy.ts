import { TableAction, TableStrategy } from "$types/components/Table";
import { Task } from "$models";
import { date } from "$lib/utils/formatting";

export class AdminTaskTableStrategy implements TableStrategy<Task> {
  getHeaders() {
    return [
      { label: "Name", key: "name" },
      { label: "Created At", key: "created_at", formatter: date },
      { label: "Updated At", key: "updated_at", formatter: date },
    ];
  }

  getActions(): TableAction<Task>[] {
    return [
      { 
        label: 'View', 
        href: (row: Task) => route('task.show', row.id)
      },
      { 
        label: 'Edit', 
        href: (row: Task) => route('task.edit', row.id)
      }
    ];
  }
}
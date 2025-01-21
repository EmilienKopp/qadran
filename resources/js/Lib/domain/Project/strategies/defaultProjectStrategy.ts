import { TableAction, TableStrategy } from "$types/components/Table";

import { Project } from "$models";
import { date } from "$lib/utils/formatting";

export class DefaultProjectTableStrategy implements TableStrategy<Project> {
  getHeaders() {
    return [
      { label: "Name", key: "name" },
      { label: "Created At", key: "created_at", formatter: date },
      { label: "Updated At", key: "updated_at", formatter: date },
    ];
  }

  getActions(): TableAction<Project>[] {
    return [
      { 
        label: 'View', 
        href: (row: Project) => route('project.show', row.id)
      },
      { 
        label: 'Edit', 
        href: (row: Project) => route('project.edit', row.id)
      }
    ];
  }
}
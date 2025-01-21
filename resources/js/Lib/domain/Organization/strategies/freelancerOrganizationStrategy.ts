import { TableAction, TableStrategy } from "$types/components/Table";

import { Organization } from "$models";
import { Trash2 } from "lucide-svelte";
import { date } from "$lib/utils/formatting";

export class FreelancerOrganizationTableStrategy implements TableStrategy<Organization>{

  getHeaders() {
    return [
      { label: "Name", key: "name" },
      { label: "Description", key: "description" },
      { label: "Type", key: "type" },
      { label: "Updated At", key: "updated_at", formatter: date },
    ];
  }

  getActions(h: any[] | undefined): TableAction<Organization>[] {
    return [
      { 
        label: 'View',
        callback: (row: Organization) => console.log(row)
      },
      { 
        label: 'Edit', 
        href: (row: Organization) => route('organization.edit', row.id)
      },
      {
        label: 'Delete',
        css: () => 'text-red-500',
        icon: () => Trash2,
      }
    ]
  }
}
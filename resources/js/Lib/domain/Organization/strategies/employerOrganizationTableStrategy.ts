import { TableAction, TableStrategy } from "$types/components/Table";

import { Organization } from "$models";
import { date } from "$lib/utils/formatting";

export class EmployerOrganizationTableStrategy implements TableStrategy<Organization>{

  getHeaders() {
    return [
      { label: "Name", key: "name" },
    ];
  }

  getActions(h: any[] | undefined): TableAction<Organization>[] {
    return [
      { 
        label: 'View', 
        // href: (row: Organization) => route('organization.show', row.id)
      },
      { 
        label: 'Edit', 
        // href: (row: Organization) => route('organization.edit', row.id)
      }
    ]
  }
}
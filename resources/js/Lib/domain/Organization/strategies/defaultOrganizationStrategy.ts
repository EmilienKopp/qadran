import { TableAction, TableStrategy } from "$types/components/Table";

import { Organization } from "$models";
import { date } from "$lib/utils/formatting";

export class DefaultOrganizationTableStrategy implements TableStrategy<Organization> {
  getHeaders() {
    return [
      { label: "Name", key: "name" },
      { label: "Created At", key: "created_at", formatter: date },
      { label: "Updated At", key: "updated_at", formatter: date },
    ];
  }

  getActions(): TableAction<Organization>[] {
    return [];
  }
}
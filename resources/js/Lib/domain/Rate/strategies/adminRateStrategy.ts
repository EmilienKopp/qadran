import { BaseRateTableStrategy } from "./baseRateStrategy";
import { DataAction } from "$types/common/dataDisplay";
import { Rate } from "$models";
import { Trash2 } from "lucide-svelte";
import { router } from "@inertiajs/svelte";

export class AdminRateTableStrategy extends BaseRateTableStrategy {
  protected defaultActions(): DataAction<Rate>[] {
    return [
      ...super.defaultActions(),
      {
        label: "Delete",
        icon: () => Trash2,
        css: () => "text-red-500",
        callback: (row: Rate) => 
          router.delete(route("rate.destroy", row.id)),
      }
    ];
  }
}
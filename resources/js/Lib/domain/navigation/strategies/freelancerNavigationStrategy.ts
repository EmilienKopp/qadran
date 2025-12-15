import { DefaultNavigationStrategy } from "$lib/core/strategies/navigationStrategy";
import activities from "../../../../routes/activities";
import organization from "../../../../routes/organization";
import project from "../../../../routes/project";
import rate from "../../../../routes/rate";
import report from "../../../../routes/report";

export class FreelancerNavigationStrategy extends DefaultNavigationStrategy {
  navigationElements() {
    return [
      // {
      //   name: 'Dashboard',
      //   href: route('dashboard'),
      //   active: false,
      // },
      {
        name: 'Daily',
        href: route('activities.show', { date: (new Date()).toISOString().split('T')[0] }),
        active: false,
      },
      {
        name: 'Montly',
        href: route('activities.index'),
        active: false,
      },
      {
        name: 'Projects',
        href: route('project.index'),
        active: false,
      },
      {
        name: 'Organizations',
        href: route('organization.index'),
        active: false,
      },
      {
        name: 'Rates',
        href: route('rate.index'),
        active: false,
      },
      {
        name: 'Reports',
        href: route('report.index'),
      },
    ];
  }
}


import { DefaultNavigationStrategy } from "$lib/core/strategies/navigationStrategy";

export class FreelancerNavigationStrategy extends DefaultNavigationStrategy {
  navigationElements() {
    return [
      {
        name: 'Dashboard',
        href: route('dashboard'),
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
      }
    ];
  }
}


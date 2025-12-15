<script lang="ts">
  import Dropdown from '$components/Actions/Dropdown.svelte';
  import Toast from '$components/Feedback/Toast/Toast.svelte';
  import NavLink from '$components/Navigation/NavLink.svelte';
  import ResponsiveNavLink from '$components/Navigation/ResponsiveNavLink.svelte';
  import type { DropdownAction, HTTPMethod } from '$types/index';
  import { inertia, Link, page } from '@inertiajs/svelte';
  import { fade } from 'svelte/transition';
  import RoleSwitcher from '$components/UI/RoleSwitcher.svelte';
  import Clock from '$components/UI/Clock.svelte';
  import { NavigationContext } from '$lib/core/contexts/navigationContext';
  import { RoleContext } from '$lib/stores/global/roleContext.svelte';
  import { appUser } from '$lib/stores/global/user.svelte';
  import ThemeToggle from '$components/UI/ThemeToggle.svelte';
  import TerminalDialog from '$components/TerminalDialog.svelte';
  import VoiceInput from '$components/DataInput/VoiceInput.svelte';
  import Timezone from '$components/UI/Timezone.svelte';
  import Button from '$components/Actions/Button.svelte';
  import { features } from '$lib/stores/global/features.svelte';
  import { edit } from '../routes/profile';
  import { dashboard, logout } from '../routes';

  interface Props {
    header?: import('svelte').Snippet;
    children?: import('svelte').Snippet;
  }

  let { header, children }: Props = $props();

  let showingNavigationDropdown = $state(false);

  let navigationContext = $derived(new NavigationContext(RoleContext.selected));
  let navigationElements = $derived(
    navigationContext.strategy.navigationElements()
  );

  $inspect(navigationElements, navigationContext, RoleContext.selected);

  const settingsDropdownActions: DropdownAction[] = [
    { text: 'Profile', href: route('profile.edit') },
    {
      text: 'Log Out',
      href: route('logout'),
      method: 'post' as HTTPMethod,
      as: 'button',
    },
  ];
</script>

<Toast show={false} />
<div>
  <div class="min-h-screen">
    <!-- daisyUI Navbar -->
    <nav class="navbar border-b border-base-300">
      <div class="navbar-start gap-2">
        <!-- Logo with Clock -->
        <div class="flex shrink-0 items-center w-36">
          <Link href={route('dashboard')} class="flex items-center gap-2">
            <Clock />
          </Link>
        </div>

        <!-- Theme Toggle -->
        <ThemeToggle />

        <!-- Navigation Links - Desktop -->
        <div class="hidden lg:flex ms-10">
          <ul class="menu menu-horizontal px-1">
            <!-- Other Navigation Links -->
            {#each navigationElements.filter((nav) => !['Projects', 'Organizations', 'Rates'].includes(nav.name)) as nav}
              <li>
                <a href={nav.href} class="text-sm font-medium" use:inertia>
                  {nav.name}
                </a>
              </li>
            {/each}

            <!-- Records Dropdown -->
            <li>
              <details>
                <summary class="text-sm font-medium">Records</summary>
                <ul class="p-2 bg-base-100 rounded-box w-52 z-[1] shadow">
                  {#each navigationElements.filter( (nav) => ['Projects', 'Organizations', 'Rates'].includes(nav.name) ) as nav}
                    <li>
                      <a href={nav.href} use:inertia>
                        {nav.name}
                      </a>
                    </li>
                  {/each}
                </ul>
              </details>
            </li>
          </ul>
        </div>

        <!-- Mobile Hamburger Dropdown -->
        <div class="dropdown lg:hidden">
          <button
            aria-label="Toggle Navigation"
            class="btn btn-ghost btn-circle"
            onclick={() =>
              (showingNavigationDropdown = !showingNavigationDropdown)}
          >
            <svg
              class="h-6 w-6"
              stroke="currentColor"
              fill="none"
              viewBox="0 0 24 24"
            >
              <path
                class:hidden={showingNavigationDropdown}
                class:inline-flex={!showingNavigationDropdown}
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16"
              />
              <path
                class:hidden={!showingNavigationDropdown}
                class:inline-flex={showingNavigationDropdown}
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </button>
          {#if showingNavigationDropdown}
            <div
              class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow"
            >
              <ul class="menu-title">Records</ul>
              {#each navigationElements.filter( (nav) => ['Projects', 'Organizations', 'Rates'].includes(nav.name) ) as nav}
                <li>
                  <ResponsiveNavLink
                    href={nav.href}
                    active={route().current(nav.href)}
                  >
                    {nav.name}
                  </ResponsiveNavLink>
                </li>
              {/each}

              {#each navigationElements.filter((nav) => !['Projects', 'Organizations', 'Rates'].includes(nav.name)) as nav}
                <li>
                  <ResponsiveNavLink
                    href={nav.href}
                    active={route().current(nav.href)}
                  >
                    {nav.name}
                  </ResponsiveNavLink>
                </li>
              {/each}

              <li class="menu-title">Account</li>
              <li>
                <div class="text-sm">
                  <div class="font-medium">{appUser('name')}</div>
                  <div class="opacity-60">{appUser('email')}</div>
                </div>
              </li>
              <li>
                <ResponsiveNavLink href={route('profile.edit')}>
                  Profile
                </ResponsiveNavLink>
              </li>
              <li>
                <ResponsiveNavLink
                  href={route('logout')}
                  method="post"
                  as="button"
                >
                  Log Out
                </ResponsiveNavLink>
              </li>
            </div>
          {/if}
        </div>
      </div>

      <div class="navbar-end gap-2">
        <RoleSwitcher />

        {#if features.terminalAccess}
          <TerminalDialog />
        {/if}

        <div class="hidden lg:flex items-center gap-2">
          <Timezone />

          <!-- Settings Dropdown -->
          <Dropdown actions={settingsDropdownActions}>
            {#snippet trigger()}
              <span class="btn btn-ghost btn-sm gap-1">
                {appUser('shortName')}
                <svg
                  class="h-4 w-4"
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 20 20"
                  fill="currentColor"
                >
                  <path
                    fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd"
                  />
                </svg>
              </span>
            {/snippet}
          </Dropdown>
        </div>
      </div>
    </nav>

    <!-- Page Heading -->
    {#if header}
      <header class="shadow-sm sticky top-0 z-10">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
          {@render header?.()}
        </div>
      </header>
    {/if}
    <!-- Page Content -->
    <main class="py-10 px-4 sm:px-8 lg:px-14" transition:fade>
      <Toast show={false} />
      {@render children?.()}
    </main>
  </div>

  <!-- Voice Input Component (always visible) -->
  <svelte:boundary>
    {#snippet failed(_error, reset)}
      <Button on:click={reset} class="btn btn-error">Reload Voice Input</Button>
    {/snippet}
    <VoiceInput />
  </svelte:boundary>
</div>

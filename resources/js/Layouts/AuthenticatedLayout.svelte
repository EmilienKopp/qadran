<script setup lang="ts">
  import Dropdown from '$components/Actions/Dropdown.svelte';
  import Toast from '$components/Feedback/Toast/Toast.svelte';
  import NavLink from '$components/Navigation/NavLink.svelte';
  import ResponsiveNavLink from '$components/Navigation/ResponsiveNavLink.svelte';
  import type { DropdownAction, HTTPMethod } from '$types/index';
  import { Link, page } from '@inertiajs/svelte';
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

  interface Props {
    header?: import('svelte').Snippet;
    children?: import('svelte').Snippet;
  }

  let { header, children }: Props = $props();

  let showingNavigationDropdown = $state(false);

  let context = $derived(new NavigationContext(RoleContext.selected));
  let navigationElements = $derived(context.strategy.navigationElements());

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
    <nav class="border-b border-gray-100">
      <!-- Primary Navigation Menu -->
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 justify-between items-center">
          <div class="flex">
            <!-- Logo -->
            <div class="flex shrink-0 items-center w-36">
              <Link href={route('dashboard')}>
                <Clock />
              </Link>
            </div>
            <ThemeToggle />

            <!-- Navigation Links -->
            <div
              class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex items-center"
            >
              {#each navigationElements as nav}
                <NavLink href={nav.href} active={route().current(nav.href)}>
                  {nav.name}
                </NavLink>
              {/each}
            </div>
          </div>

          <RoleSwitcher />

          {#if features.terminalAccess}
            <TerminalDialog />
          {/if}

          <div class="hidden sm:ms-6 sm:flex sm:items-center">
            <Timezone />
            <!-- Settings Dropdown -->
            <div class="ms-3">
              <Dropdown actions={settingsDropdownActions}>
                {#snippet trigger()}
                  <span class="inline-flex rounded-md items-center">
                    {appUser('shortName')}
                    <svg
                      class="-me-0.5 ms-2 h-4 w-4"
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

          <!-- Hamburger -->
          <div class="-me-2 flex items-center sm:hidden">
            <button
              aria-label="Toggle Navigation"
              onclick={() =>
                (showingNavigationDropdown = !showingNavigationDropdown)}
              class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-hidden"
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
          </div>
        </div>
      </div>

      <!-- Responsive Navigation Menu -->
      <div
        class="sm:hidden"
        class:block={showingNavigationDropdown}
        class:hidden={!showingNavigationDropdown}
      >
        <div class="space-y-1 pb-3 pt-2">
          {#each navigationElements as nav}
            <ResponsiveNavLink
              href={nav.href}
              active={route().current(nav.href)}
            >
              {nav.name}
            </ResponsiveNavLink>
          {/each}
        </div>

        <!-- Responsive Settings Options -->
        <div class="border-t border-gray-200 pb-1 pt-4">
          <div class="px-4">
            <div class="text-base font-medium text-gray-800">
              {appUser('name')}
            </div>
            <div class="text-sm font-medium text-gray-500">
              {appUser('email')}
            </div>
          </div>

          <div class="mt-3 space-y-1">
            <ResponsiveNavLink href={route('profile.edit')}>
              Profile
            </ResponsiveNavLink>
            <ResponsiveNavLink href={route('logout')} method="post" as="button">
              Log Out
            </ResponsiveNavLink>
          </div>
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
    {#snippet failed(error, reset)}
      <Button on:click={reset} class="btn btn-error">Reload Voice Input</Button>
    {/snippet}
    <VoiceInput />
  </svelte:boundary>
</div>

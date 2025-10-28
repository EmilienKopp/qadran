<script lang="ts">
  import { Link, router, page, inertia } from '@inertiajs/svelte';

  interface Props {
    canLogin?: boolean;
    canRegister?: boolean;
    domain?: string | null;
    auth?: any;
    errors?: Record<string, string>;
  }

  let {
    canLogin = false,
    canRegister = false,
    domain = null,
    auth = { user: null },
    errors = {},
  }: Props = $props();

  let spaceInput = $state('');
  let loading = $state(false);

  // Reactive error message from backend or local validation
  let errorMessage = $derived(errors?.space || '');

  function findTenant() {
    if (!spaceInput.trim()) {
      return;
    }

    loading = true;

    // Use Inertia router to find the tenant
    router.post(route('find-tenant'), {
      space: spaceInput.trim()
    }, {
      preserveState: false,
      preserveUrl: true,
      onError: () => {
        loading = false;
      },
      onFinish: () => {
        loading = false;
        console.log('Finished tenant lookup');
        // Inertia.location will handle the redirect
        // Keep loading true if successful
      }
    });
  }

</script>

<div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
  <div
    class="relative flex min-h-screen flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white"
  >
    <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
      <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
        {#if domain}
          <p>
            Welcome to your <strong>{domain}</strong> space
          </p>
        {:else}
          <p>Welcome to QADRAN</p>
        {/if}
        <div class="flex lg:col-start-2 lg:justify-center">
          <img
            src="/images/QADRAN_logoonly_alpha.png"
            alt="Logo"
            class="h-10 w-auto"
          />
        </div>
        <div class="mx-3 flex flex-1 justify-end">
          {#if canLogin}
            {#if auth.user}
              <!-- <Link
                href={"route('dashboard')"}
                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-hidden focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
              >
                Dashboard
              </Link> -->
            {/if}

            <Link
              href={route('login')}
              class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-hidden focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
            >
              Log in
            </Link>

            {#if canRegister}
              <Link
                href={route('register')}
                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-hidden focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
              >
                Register
              </Link>
            {/if}
          {/if}
        </div>
      </header>

      <main class="mt-6">
        {#if !domain}
          <div class="flex flex-col items-center justify-center gap-6">
            <div class="w-full max-w-md">
              <h2 class="text-2xl font-bold text-center mb-6 dark:text-white">
                Find Your Space
              </h2>
              <form onsubmit={(e) => { e.preventDefault(); findTenant(); }} class="space-y-4">
                <div>
                  <label for="space" class="block text-sm font-medium mb-2 dark:text-white">
                    Enter your space name
                  </label>
                  <input
                    id="space"
                    type="text"
                    bind:value={spaceInput}
                    placeholder="yourspace"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#FF2D20] focus:border-transparent dark:bg-gray-800 dark:border-gray-700 dark:text-white"
                    disabled={loading}
                  />
                  {#if errorMessage}
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{errorMessage}</p>
                  {/if}
                </div>
                <button
                  type="submit"
                  disabled={loading}
                  class="w-full px-4 py-2 bg-[#FF2D20] text-white rounded-md hover:bg-[#FF2D20]/90 disabled:opacity-50 disabled:cursor-not-allowed transition"
                >
                  {loading ? 'Finding...' : 'Continue'}
                </button>
              </form>
            </div>
          </div>
        {:else}
          <div class="grid gap-6 lg:grid-cols-2 lg:gap-8"></div>
        {/if}
      </main>

      <footer
        class="py-16 text-center text-sm text-black dark:text-white/70"
      ></footer>
    </div>
  </div>
</div>

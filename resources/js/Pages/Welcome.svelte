<script lang="ts">
  import { Link } from '@inertiajs/svelte';

  interface Props {
    canLogin?: boolean;
    canRegister?: boolean;
    laravelVersion?: string;
    phpVersion?: string;
    auth?: any;
  }

  let {
    canLogin = false,
    canRegister = false,
    laravelVersion = '',
    phpVersion = '',
    auth = { user: null }
  }: Props = $props();

  console.log(canLogin, canRegister, laravelVersion, phpVersion, auth);
</script>

<div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">

  <div
    class="relative flex min-h-screen flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white"
  >
    <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
      <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
        <div class="flex lg:col-start-2 lg:justify-center">
          <img src="/images/QADRAN_logoonly_alpha.png" alt="Logo" class="h-10 w-auto" />
        </div>
        <div class="mx-3 flex flex-1 justify-end">
          {#if canLogin}
            {#if auth.user}
              <Link
                href="route('dashboard')"
                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-hidden focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
              >
                Dashboard
              </Link>
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
        <div class="grid gap-6 lg:grid-cols-2 lg:gap-8">
         
        </div>
      </main>

      <footer class="py-16 text-center text-sm text-black dark:text-white/70">
        
      </footer>
    </div>
  </div>
</div>

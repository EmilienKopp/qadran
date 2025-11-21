<script lang="ts">
  import Dialog from '$components/Modals/Dialog.svelte';
  import TimeTrackingFeature from './Landing/TimeTrackingFeature.svelte';
  import MultiRoleFeature from './Landing/MultiRoleFeature.svelte';
  import DeveloperFeature from './Landing/DeveloperFeature.svelte';
  import VoiceControlFeature from './Landing/VoiceControlFeature.svelte';
  import McpFeature from './Landing/McpFeature.svelte';
  import ReportsFeature from './Landing/ReportsFeature.svelte';
  import MorphCard from '$components/Display/MorphCard.svelte';
  import { setContext } from 'svelte';

  interface Props {
    canLogin?: boolean;
    canRegister?: boolean;
    authenticated?: boolean;
    tenant?: any;
  }

  let {
    canLogin = false,
    canRegister = false,
    authenticated = false,
    tenant = null,
  }: Props = $props();

  const features = [
    {
      title: 'Effortless Time Tracking',
      description:
        'Clock in and out with a single click. Track your time across multiple projects and organizations with intuitive controls.',
      component: TimeTrackingFeature,
    },
    {
      title: 'Multi-Role Switching',
      description:
        'Seamlessly switch between multiple roles across different organizations. Built-in context strategies adapt the interface to match your current role.',
      component: MultiRoleFeature,
    },
    {
      title: 'Tailored for Developers',
      description:
        'GitHub integration generates detailed reports from your commits. Connect your repositories and let your code tell the story of your work.',
      component: DeveloperFeature,
    },
    {
      title: 'Look Mommy, No Hands!',
      description:
        'Control your time tracking hands-free with voice commands. Start, stop, and log entries without touching your mouse.',
      component: VoiceControlFeature,
    },
    {
      title: 'MCP Integration',
      description:
        'Model Context Protocol support enables AI assistants to interact with your workspace data, streamlining your workflow automation.',
      component: McpFeature,
    },
    {
      title: 'Reports & Analytics',
      description:
        'Generate comprehensive reports with detailed breakdowns of time spent, project progress, and team productivity metrics.',
      component: ReportsFeature,
    },
  ];

  let selectedFeature = $state<(typeof features)[0] | null>(null);
  let modalOpen = $state(false);

  function openFeatureModal(feature: (typeof features)[0]) {
    selectedFeature = feature;

    if (document.startViewTransition) {
      document.startViewTransition(() => {
        modalOpen = true;
      });
    } else {
      modalOpen = true;
    }
  }

  let expandedCardId = $state({ current: null });
  setContext('morph-cards', expandedCardId);
</script>

<svelte:head>
  <title>Qadran - Manage Your Time, Empower Your Team</title>
  <meta
    name="description"
    content="Qadran is the modern workspace for time tracking, project management, and team collaboration. Built for teams that value productivity."
  />
</svelte:head>

<div class="min-h-screen bg-base-100">
  <!-- Navigation -->
  <div
    class="navbar bg-base-100 border-b border-base-300 fixed top-0 z-50 backdrop-blur-md bg-opacity-90"
  >
    <div class="navbar-start">
      <div class="flex items-center gap-3 ml-4">
        <img
          src="/images/QADRAN_logoonly_alpha.png"
          alt="Qadran Logo"
          class="h-8 w-auto"
        />
        <span class="text-xl font-bold">QADRAN</span>
      </div>
    </div>

    <div class="navbar-end gap-2 mr-4">
      {#if canLogin}
        <a href="/welcome/login" class="btn btn-ghost btn-sm">
          {authenticated && tenant ? 'Dashboard' : 'Login'}
        </a>
        {#if canRegister}
          <a href="/welcome/register" class="btn btn-primary btn-sm">
            Get Started
          </a>
        {/if}
      {/if}
    </div>
  </div>

  <!-- Hero Section -->
  <div class="hero min-h-screen bg-base-100">
    <div class="hero-content text-center max-w-5xl">
      <div>
        <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold mb-6">
          Manage Your Time,<br />
          <span class="text-base-content/70">Empower Your Team</span>
        </h1>
        <p
          class="text-xl md:text-2xl text-base-content/60 mb-12 max-w-3xl mx-auto"
        >
          The modern workspace for time tracking, project management, and team
          collaboration. Built for teams that value productivity.
        </p>

        <div
          class="flex flex-col sm:flex-row gap-4 justify-center items-center"
        >
          {#if canRegister}
            <a href="/welcome/register" class="btn btn-primary btn-lg">
              Start Free Trial
            </a>
          {/if}
          <a href="/welcome/login" class="btn btn-outline btn-lg">
            Access Your Workspace
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Features Grid -->
  <section class="py-20 px-4 sm:px-6 lg:px-8 bg-base-200">
    <div class="max-w-7xl mx-auto">
      <h2 class="text-3xl md:text-4xl font-bold text-center mb-4">
        Built for Modern Teams
      </h2>
      <p
        class="text-lg text-base-content/60 text-center mb-16 max-w-2xl mx-auto"
      >
        Powerful features designed for developers and professionals who work
        across multiple contexts.
      </p>

      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        {#each features as feature}
          <MorphCard id={feature.title.toLowerCase().replace(/\s+/g, '-')} class="cursor-pointer">
            {#snippet small()}
              <div class="card-body">
                <h3
                  class="card-title text-lg group-hover:text-primary transition-colors"
                >
                  {feature.title}
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 opacity-0 group-hover:opacity-100 transition-opacity"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M9 5l7 7-7 7"
                    />
                  </svg>
                </h3>
                <p class="text-base-content/60 text-sm">
                  {feature.description}
                </p>
                <div class="card-actions justify-end mt-2">
                  <span
                    class="text-xs text-base-content/40 group-hover:text-base-content/60 transition-colors"
                  >
                    Learn more →
                  </span>
                </div>
              </div>
            {/snippet}
            {#snippet expanded()}
              {@const FeatureComponent = feature.component}
              <div class="p-4">
                <FeatureComponent />
              </div>
            {/snippet}
          </MorphCard>
        {/each}
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="py-20 px-4 sm:px-6 lg:px-8 bg-base-100">
    <div class="max-w-4xl mx-auto text-center">
      <h2 class="text-3xl md:text-4xl font-bold mb-6">Ready to Get Started?</h2>
      <p class="text-lg text-base-content/60 mb-8">
        Join teams around the world who trust Qadran to manage their time and
        projects.
      </p>

      {#if canRegister}
        <a href="/welcome/register" class="btn btn-primary btn-lg">
          Create Your Workspace
        </a>
      {/if}
    </div>
  </section>

  <!-- Footer -->
  <footer
    class="footer footer-center p-10 bg-base-200 text-base-content border-t border-base-300"
  >
    <aside>
      <img
        src="/images/QADRAN_logoonly_alpha.png"
        alt="Qadran Logo"
        class="h-8 w-auto"
      />
      <p class="font-bold">QADRAN</p>
      <p class="text-sm opacity-60">© 2024 Qadran. All rights reserved.</p>
    </aside>
    <nav>
      <div class="grid grid-flow-col gap-4">
        <button type="button" class="link link-hover">Privacy</button>
        <button type="button" class="link link-hover">Terms</button>
        <button type="button" class="link link-hover">Contact</button>
      </div>
    </nav>
  </footer>

  <!-- Feature Detail Modal -->
  {#if selectedFeature}
    <Dialog
      bind:open={modalOpen}
      class="modal-box max-w-2xl z-30"
      transitionName="feature-{selectedFeature.title
        .toLowerCase()
        .replace(/\s+/g, '-')}"
    >
      {@const FeatureComponent = selectedFeature.component}
      <FeatureComponent />
    </Dialog>
  {/if}
</div>

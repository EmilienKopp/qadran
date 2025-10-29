<script lang="ts">
  import { Link } from '@inertiajs/svelte';

  interface Issue {
    id: number;
    jira_key: string;
    summary: string;
    description: string | null;
    status: string;
    status_category: string | null;
    status_category_name: string | null;
    status_color: string | null;
    priority: string | null;
    priority_icon_url: string | null;
    issue_type: string | null;
    issue_type_icon_url: string | null;
    first_reported_at: string | null;
    first_reported_human: string;
    last_updated_at: string | null;
    current_status_since: string | null;
    current_status_duration: number;
    current_status_duration_human: string;
  }

  interface Props {
    issues: Issue[];
  }

  let { issues }: Props = $props();

  function getStatusColor(statusColor: string | null, statusCategory: string | null): string {
    if (statusColor) {
      const colorMap: Record<string, string> = {
        'blue-gray': 'bg-slate-100 text-slate-800 border-slate-300',
        'yellow': 'bg-yellow-100 text-yellow-800 border-yellow-300',
        'green': 'bg-green-100 text-green-800 border-green-300',
      };
      return colorMap[statusColor] || 'bg-gray-100 text-gray-800 border-gray-300';
    }

    // Fallback to category-based colors
    const categoryMap: Record<string, string> = {
      'new': 'bg-blue-100 text-blue-800 border-blue-300',
      'indeterminate': 'bg-yellow-100 text-yellow-800 border-yellow-300',
      'done': 'bg-green-100 text-green-800 border-green-300',
    };
    return categoryMap[statusCategory || ''] || 'bg-gray-100 text-gray-800 border-gray-300';
  }

  function getPriorityColor(priority: string | null): string {
    if (!priority) return 'text-gray-500';

    const priorityLower = priority.toLowerCase();
    if (priorityLower.includes('highest') || priorityLower.includes('critical')) {
      return 'text-red-600 font-semibold';
    } else if (priorityLower.includes('high')) {
      return 'text-orange-600 font-semibold';
    } else if (priorityLower.includes('medium')) {
      return 'text-yellow-600';
    } else if (priorityLower.includes('low')) {
      return 'text-blue-600';
    } else if (priorityLower.includes('lowest')) {
      return 'text-gray-500';
    }
    return 'text-gray-600';
  }

  // Group issues by status category
  let groupedIssues = $derived.by(() => {
    const groups: Record<string, Issue[]> = {
      'To Do': [],
      'In Progress': [],
      'Done': [],
    };

    issues.forEach(issue => {
      const category = issue.status_category_name || 'To Do';
      if (!groups[category]) {
        groups[category] = [];
      }
      groups[category].push(issue);
    });

    return groups;
  });
</script>

<svelte:head>
  <title>Known Issues - Qadran</title>
</svelte:head>

<div class="bg-gray-50 min-h-screen dark:bg-gray-900">
  <!-- Header -->
  <header class="bg-white shadow dark:bg-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
          <img src="/images/QADRAN_logoonly_alpha.png" alt="Logo" class="h-10 w-auto" />
          <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
              Known Issues
            </h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
              Current status of reported issues tracked in Jira
            </p>
          </div>
        </div>
        <Link
          href={route('login')}
          class="rounded-md px-4 py-2 text-sm font-medium bg-primary text-white hover:bg-primary-focus transition"
        >
          Login
        </Link>
      </div>
    </div>
  </header>

  <!-- Main Content -->
  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {#if issues.length === 0}
      <div class="bg-white rounded-lg shadow p-8 text-center dark:bg-gray-800">
        <p class="text-gray-600 dark:text-gray-400 text-lg">
          No known issues at this time. Check back later!
        </p>
      </div>
    {:else}
      <div class="space-y-8">
        {#each Object.entries(groupedIssues) as [categoryName, categoryIssues]}
          {#if categoryIssues.length > 0}
            <div>
              <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <span
                  class="inline-block w-3 h-3 rounded-full {categoryName === 'Done'
                    ? 'bg-green-500'
                    : categoryName === 'In Progress'
                      ? 'bg-yellow-500'
                      : 'bg-blue-500'}"
                ></span>
                {categoryName}
                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                  ({categoryIssues.length})
                </span>
              </h2>

              <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                {#each categoryIssues as issue}
                  <div
                    class="bg-white rounded-lg shadow hover:shadow-md transition-shadow p-5 dark:bg-gray-800"
                  >
                    <!-- Issue Header -->
                    <div class="flex items-start justify-between mb-3">
                      <div class="flex items-center gap-2">
                        {#if issue.issue_type_icon_url}
                          <img
                            src={issue.issue_type_icon_url}
                            alt={issue.issue_type}
                            class="w-5 h-5"
                          />
                        {/if}
                        <span class="text-sm font-mono text-gray-600 dark:text-gray-400">
                          {issue.jira_key}
                        </span>
                      </div>
                      <span
                        class="px-2 py-1 text-xs rounded border {getStatusColor(
                          issue.status_color,
                          issue.status_category
                        )}"
                      >
                        {issue.status}
                      </span>
                    </div>

                    <!-- Issue Summary -->
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-3 line-clamp-2">
                      {issue.summary}
                    </h3>

                    <!-- Issue Details -->
                    <div class="space-y-2 text-sm">
                      {#if issue.priority}
                        <div class="flex items-center gap-2">
                          {#if issue.priority_icon_url}
                            <img
                              src={issue.priority_icon_url}
                              alt={issue.priority}
                              class="w-4 h-4"
                            />
                          {/if}
                          <span class={getPriorityColor(issue.priority)}>
                            {issue.priority}
                          </span>
                        </div>
                      {/if}

                      <div class="text-gray-600 dark:text-gray-400">
                        <span class="font-medium">Reported:</span>
                        {issue.first_reported_human}
                      </div>

                      <div class="text-gray-600 dark:text-gray-400">
                        <span class="font-medium">In {issue.status}:</span>
                        {issue.current_status_duration_human}
                      </div>
                    </div>
                  </div>
                {/each}
              </div>
            </div>
          {/if}
        {/each}
      </div>

      <!-- Summary Stats -->
      <div class="mt-8 bg-white rounded-lg shadow p-6 dark:bg-gray-800">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
          Summary
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div class="text-center">
            <div class="text-3xl font-bold text-blue-600">
              {groupedIssues['To Do']?.length || 0}
            </div>
            <div class="text-sm text-gray-600 dark:text-gray-400">To Do</div>
          </div>
          <div class="text-center">
            <div class="text-3xl font-bold text-yellow-600">
              {groupedIssues['In Progress']?.length || 0}
            </div>
            <div class="text-sm text-gray-600 dark:text-gray-400">In Progress</div>
          </div>
          <div class="text-center">
            <div class="text-3xl font-bold text-green-600">
              {groupedIssues['Done']?.length || 0}
            </div>
            <div class="text-sm text-gray-600 dark:text-gray-400">Resolved</div>
          </div>
        </div>
      </div>
    {/if}
  </main>

  <!-- Footer -->
  <footer class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-center text-sm text-gray-600 dark:text-gray-400">
    <p>Last updated: {new Date().toLocaleString()}</p>
    <p class="mt-2">
      Issues are automatically synced from Jira. For support, please contact your administrator.
    </p>
  </footer>
</div>

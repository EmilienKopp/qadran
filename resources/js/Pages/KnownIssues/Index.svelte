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
    is_deployed: boolean;
    deployed_at: string | null;
    deployment_metadata: {
      pr?: {
        number: number;
        title: string;
        url: string;
        branch: string;
        merged_at: string | null;
      } | null;
      commits?: Array<{
        sha: string;
        message: string;
        url: string;
        date: string;
      }>;
    } | null;
  }

  interface Props {
    issues: Issue[];
  }

  let { issues }: Props = $props();

  // State for collapsible sections
  let collapsed = $state({
    reported: false,
    'In Progress': false,
    solved: false,
    deployed: false,
  });

  function toggleSection(section: string) {
    collapsed[section] = !collapsed[section];
  }

  function getStatusColor(statusColor: string | null, statusCategory: string | null): string {
    if (statusColor) {
      const colorMap: Record<string, string> = {
        'blue-gray': 'bg-slate-100 text-slate-800 border-slate-300',
        'yellow': 'bg-yellow-100 text-yellow-800 border-yellow-300',
        'green': 'bg-green-100 text-green-800 border-green-300',
      };
      return colorMap[statusColor] || 'bg-gray-100  border-gray-300';
    }

    // Fallback to category-based colors
    const categoryMap: Record<string, string> = {
      'new': 'bg-blue-100 text-blue-800 border-blue-300',
      'indeterminate': 'bg-yellow-100 text-yellow-800 border-yellow-300',
      'done': 'bg-green-100 text-green-800 border-green-300',
    };
    return categoryMap[statusCategory || ''] || 'bg-gray-100  border-gray-300';
  }

  function getPriorityColor(priority: string | null): string {
    if (!priority) return '';

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
      return '';
    }
    return '';
  }

  // Group issues by status category and deployment status
  let groupedIssues = $derived.by(() => {
    const groups: Record<string, Issue[]> = {
      'deployed': [],
      'reported': [],
      'In Progress': [],
      'solved': [],
    };

    issues.forEach(issue => {
      // Deployed issues go to their own category
      if (issue.is_deployed) {
        groups['deployed'].push(issue);
        return;
      }

      const category = issue.status_category_name || 'To Do';
      // Map the category names to our display names
      let displayCategory = category;
      if (category === 'To Do') {
        displayCategory = 'reported';
      } else if (category === 'Done') {
        displayCategory = 'solved';
      }

      if (!groups[displayCategory]) {
        groups[displayCategory] = [];
      }
      groups[displayCategory].push(issue);
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
            <h1 class="text-3xl font-bold  dark:text-white">
              Known Issues
            </h1>
            <p class="text-sm  dark: mt-1">
              Current status of reported issues
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
        <p class=" dark: text-lg">
          No known issues at this time. Check back later!
        </p>
      </div>
    {:else}
      <div class="space-y-6">
        {#each Object.entries(groupedIssues) as [categoryName, categoryIssues]}
          {#if categoryIssues.length > 0}
            <div class="bg-white rounded-lg shadow dark:bg-gray-800 overflow-hidden">
              <!-- Collapsible Header -->
              <button
                onclick={() => toggleSection(categoryName)}
                class="w-full px-6 py-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
              >
                <div class="flex items-center gap-3">
                  <span
                    class="inline-block w-3 h-3 rounded-full {categoryName === 'deployed'
                      ? 'bg-purple-500'
                      : categoryName === 'solved'
                        ? 'bg-green-500'
                        : categoryName === 'In Progress'
                          ? 'bg-yellow-500'
                          : 'bg-blue-500'}"
                  ></span>
                  <h2 class="text-xl font-semibold  dark:text-white capitalize">
                    {categoryName}
                  </h2>
                  <span class="text-sm font-normal  dark:">
                    ({categoryIssues.length})
                  </span>
                </div>
                <svg
                  class="w-5 h-5  transition-transform {collapsed[categoryName] ? '' : 'rotate-180'}"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>

              <!-- Collapsible Table Content -->
              {#if !collapsed[categoryName]}
                <div class="overflow-x-auto">
                  <table class="w-full table-fixed">
                    <thead class="bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                      <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium  dark: uppercase tracking-wider w-32">
                          Issue
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium  dark: uppercase tracking-wider w-96">
                          Summary
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium  dark: uppercase tracking-wider w-32">
                          Priority
                        </th>
                        {#if categoryName === 'deployed'}
                          <th class="px-6 py-3 text-left text-xs font-medium  dark: uppercase tracking-wider w-48">
                            Deployed
                          </th>
                          <th class="px-6 py-3 text-left text-xs font-medium  dark: uppercase tracking-wider w-32">
                            PR/Commits
                          </th>
                        {:else}
                          <th class="px-6 py-3 text-left text-xs font-medium  dark: uppercase tracking-wider w-36">
                            Reported
                          </th>
                          <th class="px-6 py-3 text-left text-xs font-medium  dark: uppercase tracking-wider w-32">
                            Duration
                          </th>
                        {/if}
                      </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                      {#each categoryIssues as issue}
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                          <td class="px-6 py-4 whitespace-nowrap w-32">
                            <div class="flex items-center gap-2">
                              {#if issue.issue_type_icon_url}
                                <img
                                  src={issue.issue_type_icon_url}
                                  alt={issue.issue_type}
                                  class="w-4 h-4"
                                />
                              {/if}
                              <span class="text-sm font-mono  dark:">
                                {issue.jira_key}
                              </span>
                            </div>
                          </td>
                          <td class="px-6 py-4 w-96">
                            <div class="text-sm  dark:text-white font-medium truncate">
                              {issue.summary}
                            </div>
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap w-32">
                            {#if issue.priority}
                              <div class="flex items-center gap-2">
                                {#if issue.priority_icon_url}
                                  <img
                                    src={issue.priority_icon_url}
                                    alt={issue.priority}
                                    class="w-4 h-4"
                                  />
                                {/if}
                                <span class="text-sm {getPriorityColor(issue.priority)}">
                                  {issue.priority}
                                </span>
                              </div>
                            {:else}
                              <span class="text-sm ">-</span>
                            {/if}
                          </td>
                          {#if categoryName === 'deployed'}
                            <td class="px-6 py-4 whitespace-nowrap w-48 text-sm  dark:">
                              {#if issue.deployed_at}
                                {new Date(issue.deployed_at).toLocaleDateString()}
                              {:else}
                                <span class="">-</span>
                              {/if}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap w-32">
                              {#if issue.deployment_metadata?.pr}
                                <a
                                  href={issue.deployment_metadata.pr.url}
                                  target="_blank"
                                  rel="noopener noreferrer"
                                  class="text-sm text-purple-600 hover:text-purple-800 dark:text-purple-400 dark:hover:text-purple-300 flex items-center gap-1"
                                >
                                  <span>PR #{issue.deployment_metadata.pr.number}</span>
                                  <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                  </svg>
                                </a>
                              {:else if issue.deployment_metadata?.commits && issue.deployment_metadata.commits.length > 0}
                                <span class="text-sm  dark:">
                                  {issue.deployment_metadata.commits.length} commit{issue.deployment_metadata.commits.length > 1 ? 's' : ''}
                                </span>
                              {:else}
                                <span class="text-sm ">-</span>
                              {/if}
                            </td>
                          {:else}
                            <td class="px-6 py-4 whitespace-nowrap w-36 text-sm  dark:">
                              {issue.first_reported_human}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap w-32 text-sm  dark:">
                              {issue.current_status_duration_human}
                            </td>
                          {/if}
                        </tr>
                      {/each}
                    </tbody>
                  </table>
                </div>
              {/if}
            </div>
          {/if}
        {/each}
      </div>
    {/if}
  </main>

  <!-- Footer -->
  <footer class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-center text-sm  dark:">
    <p>Last updated: {new Date().toLocaleString()}</p>
    <p class="mt-2">
      Issues are automatically synced from Jira. For support, please contact your administrator.
    </p>
  </footer>
</div>

<script lang="ts">
  import Button from '$components/Actions/Button.svelte';
  import Header from '$components/UI/Header.svelte';
  import AuthenticatedLayout from '$layouts/AuthenticatedLayout.svelte';
  import Select from '$components/DataInput/Select.svelte';
  import type { Report } from '$models';
  import { superUseForm } from '$lib/inertia';
  import Input from '$components/DataInput/Input.svelte';
  import Textarea from '$components/DataInput/Textarea.svelte';
  import { toaster } from '$components/Feedback/Toast/ToastHandler.svelte';
  import { router } from '@inertiajs/svelte';
  import { enums } from '$lib/stores';
  import FloatingFormBar from '$components/FloatingFormBar/FloatingFormBar.svelte';
  import {
    adjectives,
    animals,
    uniqueNamesGenerator,
  } from 'unique-names-generator';
  import { datetime } from '$lib/utils/formatting';

  interface Props {
    content?: string;
    repositories?: any[];
    branches?: any[];
  }

  let { content, repositories, branches }: Props = $props();
  const dummyTitle = uniqueNamesGenerator({
    dictionaries: [adjectives, animals],
    length: 2,
    separator: ' ',
  });

  const form = superUseForm<Report>({
    title: dummyTitle,
    content: content || '',
    repository: undefined,
    since: undefined,
    until: undefined,
    branch: undefined,
    report_type: 'task_based',
    original_log: '',
  });

  let loading = $state(false);
  let filteredBranches = $derived((branches?.[$form.repository] || []).map((branch) => ({
    value: branch.name,
    name: branch.name,
  })));
  let title = $derived.by(() => {
    if(!$form.repository) return dummyTitle;
    return `${$form.repository} - ${$form.branch || 'default branch'} Report (${new Date().toLocaleDateString()})`;
  });

  async function handleSubmit(e: Event) {
    e.preventDefault();
    $form.title = title;
    $form.post(route('report.store'), {
      onSuccess: (event: any) => {
        toaster.success('Report created successfully');
      },
      onError: (errors: Record<string, string>) => {
        toaster.error('Failed to create report');
        console.log(errors);
      },
    });
  }

  async function handleGenerate() {
    loading = true;
    console.log('Handling report generation with git log:', $form.original_log);
    $form.post(route('report.generate'), {
      onSuccess: (event: any) => {
        toaster.success('Summary generated successfully');
        console.log('Generated report:', event);
        $form.content = event.props?.content || '';
        console.log('Generated content:', $form?.content);
      },
      onError: (errors: Record<string, string>) => {
        toaster.error('Failed to generate report');
        console.log(errors);
      },
      onFinish: () => {
        loading = false;
      },
      preserveUrl: true,
      preserveState: true,
      preserveScroll: true,
    });
  }

  async function handleFetchCommits() {
    $form.post(route('report.fetch-commits'), {
      onSuccess: (event: any) => {
        toaster.success('Commits fetched successfully');
        console.log('Fetched commits:', event.props.logs.commits);
        $form.original_log = event.props.logs.commits.map((commit: any) => {
          return `${datetime(commit.date)} ${commit.message}`;
        }).join('\n');
      },
      onError: (errors: Record<string, string>) => {
        toaster.error('Failed to fetch commits');
        console.log(errors);
      },
      preserveUrl: true,
      preserveState: true,
      preserveScroll: true,
      only: ['logs'],
    });
  }

  async function bustCache() {
    router.post(route('report.bust-cache'));
  }
</script>

<AuthenticatedLayout>
  <div class="max-w-4xl mx-auto p-6 pb-12">
    <form onsubmit={handleSubmit} class="flex flex-col space-y-6">
      <!-- Header Section -->
      <div class="card">
        <div class="card-body">
          <Header title="Create Report"></Header>
        </div>
      </div>

      <!-- Report Details -->
      <div class="card">
        <div class="card-body">
          <h3 class="card-title text-lg mb-4">Report Details</h3>
          <Input
            label="Report Title"
            name="title"
            bind:value={title}
            placeholder="Enter a descriptive title for your report"
            class="input-lg"
            error={$form.errors.title}
          />

          <div class="flex flex-col md:flex-row gap-4 mt-4">
            <Select
              options={repositories?.map((repo) => ({
                value: repo.name,
                name: repo.name,
              }))}
              name="repository"
              placeholder="Select a repository"
              bind:value={$form.repository}
              class="select select-bordered w-full max-w-xs mt-4"
              error={$form.errors.repository}
            />
            <Select
              options={filteredBranches}
              name="branch"
              placeholder="Select a branch"
              bind:value={$form.branch}
              class="select select-bordered w-full max-w-xs mt-4"
              error={$form.errors.branch}
            />
          </div>

          <!-- Commit Range selection (since/until) -->
          <div class="form-control mt-4">
            <label class="label">
              <span class="label-text">Commit Range</span>
            </label>
            <div class="flex gap-4">
              <Input
                type="date"
                name="since"
                bind:value={$form.since}
                placeholder="Since (optional)"
                class="input input-bordered w-full max-w-xs"
              />
              <Input
                type="date"
                name="until"
                bind:value={$form.until}
                placeholder="Until (optional)"
                class="input input-bordered w-full max-w-xs"
              />
            </div>
            <button
              type="button"
              class="btn btn-secondary mt-2"
              onclick={handleFetchCommits}
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5 mr-2"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"
                />
              </svg>
              Fetch Commits
            </button>
          </div>
        </div>
      </div>

      <!-- Git Log Section -->
      <div class="card">
        <div class="card-body">
          <div class="flex items-center justify-between mb-4">
            <h3 class="card-title text-lg">Git Log Input</h3>
            <div class="badge badge-info badge-outline">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-3 w-3 mr-1"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                />
              </svg>
              Paste your git log here
            </div>
          </div>

          <div class="form-control flex flex-col gap-3">
            <Textarea
              class="h-64 w-full"
              placeholder="Paste your git log output here..."
              bind:value={$form.original_log}
              error={$form.errors.original_log}
            ></Textarea>
            <div class="label">
              <span class="label-text-alt"
                >Tip: Use 'git log --oneline -10 --author=$(git config user.email)' to get recent commits</span
              >
            </div>
          </div>

          <div class="card-actions justify-end items-center mt-4">
            <Select
              name="report_type"
              placeholder="Select report type"
              bind:value={$form.report_type}
              options={$enums.report_types}
              class="select select-bordered w-full max-w-xs"
              error={$form.errors.report_type}
            />

            <Button
              {loading}
              type="button"
              onclick={handleGenerate}
              class="btn-secondary btn-lg"
              disabled={!$form.original_log?.trim()}
            >
              {#if loading}
                Generating...
              {:else}
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-5 w-5 mr-2"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"
                  />
                </svg>
                Generate Report
              {/if}
            </Button>
          </div>
        </div>
      </div>

      <!-- Generated Content Section -->
      <div class="card">
        <div class="card-body">
          <div class="flex items-center justify-between mb-4">
            <h3 class="card-title text-lg">Generated Report Content</h3>
            {#if $form.content}
              <div class="badge badge-success badge-outline">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-3 w-3 mr-1"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M5 13l4 4L19 7"
                  />
                </svg>
                Content generated
              </div>
            {:else}
              <div class="badge badge-warning badge-outline">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-3 w-3 mr-1"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"
                  />
                </svg>
                Awaiting generation
              </div>
            {/if}
          </div>

          <div class="form-control flex flex-col gap-3">
            <Textarea
              class="h-64 leading-relaxed w-full font-mono text-sm"
              placeholder="Generated report content will appear here after clicking 'Generate Report'..."
              bind:value={$form.content}
              error={$form.errors.content}
            ></Textarea>
            <div class="label">
              <span class="label-text-alt"
                >You can edit the generated content before saving</span
              >
            </div>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <FloatingFormBar errors={$form.errors}>
        <Button type="button" class="btn-ghost" onclick={() => $form.reset()}
          >Cancel</Button
        >
        <Button
          type="submit"
          class="btn-primary"
          disabled={!$form?.title?.trim() ||
            !$form?.content?.trim() ||
            $form.processing}
        >
          Save Report
        </Button>
      </FloatingFormBar>
    </form>
  </div>
</AuthenticatedLayout>

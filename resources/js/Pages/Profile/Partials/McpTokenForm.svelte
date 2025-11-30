<script lang="ts">
  import Button from '$components/Actions/Button.svelte';
  import Modal from '$components/Actions/Modal.svelte';
  import Input from '$components/DataInput/Input.svelte';
  import InputError from '$components/DataInput/InputError.svelte';
  import InputLabel from '$components/DataInput/InputLabel.svelte';
  import SectionCard from '$components/UI/SectionCard.svelte';
  import { router, useForm } from '@inertiajs/svelte';

  interface Props {
    class?: string;
    mcpTokens?: McpToken[];
    mcpConnectionInfo?: ConnectionInfo | null;
    newMcpToken?: NewTokenData | null;
  }

  interface McpToken {
    id: number;
    name: string;
    abilities: string[];
    last_used_at: string | null;
    created_at: string;
    expires_at: string | null;
  }

  interface NewTokenData {
    token: string;
    token_name: string;
    tenant_host: string;
    formatted_token: string;
    expires_at: string | null;
    created_at: string;
  }

  interface ConnectionInfo {
    tenant_host: string;
    tenant_name: string;
    mcp_url: string;
    auth_format: string;
    example_config: {
      curl: {
        command: string;
        args: string[];
      };
      vscode: {
        mcpServers: {
          qadran: {
            command: string;
            args: string[];
          };
        };
      };
    };
  }

  let { 
    class: className, 
    mcpTokens = [], 
    mcpConnectionInfo = null,
    newMcpToken = null
  }: Props = $props();

  let createModal: { showModal: () => void; close: () => void };
  let tokenModal: { showModal: () => void; close: () => void };
  let connectionModal: { showModal: () => void; close: () => void };

  const createForm = useForm({
    name: '',
    expires_at: '',
  });

  // Show token modal when a new token is created
  $effect(() => {
    if (newMcpToken && tokenModal) {
      tokenModal.showModal();
    }
  });

  function openCreateModal() {
    $createForm.reset();
    createModal?.showModal();
  }

  function closeCreateModal() {
    createModal?.close();
  }

  function closeTokenModal() {
    tokenModal?.close();
  }

  function closeConnectionModal() {
    connectionModal?.close();
  }

  function createToken(e: Event) {
    e.preventDefault();
    $createForm.post(route('mcp-tokens.store'), {
      preserveScroll: true,
      onSuccess: () => {
        closeCreateModal();
      },
    });
  }

  function deleteToken(tokenId: number, tokenName: string) {
    if (!confirm(`Are you sure you want to delete the token "${tokenName}"?`)) {
      return;
    }

    router.delete(route('mcp-tokens.destroy', { tokenId }), {
      preserveScroll: true,
    });
  }

  function copyToClipboard(text: string) {
    navigator.clipboard.writeText(text).then(() => {
      alert('Copied to clipboard!');
    }).catch(() => {
      alert('Failed to copy to clipboard');
    });
  }

  function formatDate(dateString: string | null) {
    if (!dateString) return 'Never';
    return new Date(dateString).toLocaleDateString() + ' ' + new Date(dateString).toLocaleTimeString();
  }

  function showConnectionInfo() {
    connectionModal?.showModal();
  }
</script>

<SectionCard
  title="MCP Access Tokens"
  subtitle="Create and manage API tokens for Model Context Protocol (MCP) access. These tokens allow AI clients to interact with your Qadran data through the MCP server."
  class={className}
>
  {#snippet content()}
    <div class="space-y-6">
      <div class="flex gap-4">
        <Button type="button" onclick={openCreateModal}>
          Create
        </Button>

        <Button disabled={!mcpConnectionInfo} type="button" variant="secondary" onclick={showConnectionInfo}>
          View information
        </Button>
      </div>

      {#if mcpTokens.length > 0}
        <div class="overflow-hidden rounded-md border border-gray-200">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider ">
                  Name
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider ">
                  Last Used
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider ">
                  Created
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider ">
                  Expires
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider ">
                  Actions
                </th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
              {#each mcpTokens as token}
                <tr>
                  <td class="px-6 py-4 text-sm font-medium ">
                    {token.name.replace('MCP: ', '')}
                  </td>
                  <td class="px-6 py-4 text-sm ">
                    {formatDate(token.last_used_at)}
                  </td>
                  <td class="px-6 py-4 text-sm ">
                    {formatDate(token.created_at)}
                  </td>
                  <td class="px-6 py-4 text-sm ">
                    {token.expires_at ? formatDate(token.expires_at) : 'Never'}
                  </td>
                  <td class="px-6 py-4 text-sm ">
                    <Button
                      type="button"
                      variant="danger"
                      size="sm"
                      onclick={() => deleteToken(token.id, token.name)}
                    >
                      Delete
                    </Button>
                  </td>
                </tr>
              {/each}
            </tbody>
          </table>
        </div>
      {:else}
        <div class="text-center py-12">
          <p class="">No MCP tokens created yet.</p>
          <p class="text-sm  mt-1">Create your first token to get started with MCP integration.</p>
        </div>
      {/if}
    </div>
  {/snippet}
</SectionCard>

<!-- Create Token Modal -->
<Modal bind:this={createModal}>
  {#snippet title()}
    Create MCP Token
  {/snippet}

  {#snippet children()}
    <form onsubmit={createToken} class="space-y-6">
      <div>
        <InputLabel for="token_name" value="Token Name" />
        <Input
          name="name"
          id="token_name"
          type="text"
          bind:value={$createForm.name}
          class="mt-1 block w-full"
          placeholder="e.g., VS Code Integration"
          required
        />
        {#if $createForm.errors.name}
          <InputError message={$createForm.errors.name} />
        {/if}
        <p class="mt-1 text-sm ">
          Choose a descriptive name to help you identify this token later.
        </p>
      </div>

      <div>
        <InputLabel for="expires_at" value="Expiration Date (Optional)" />
        <Input
          name="expires_at"
          id="expires_at"
          type="datetime-local"
          bind:value={$createForm.expires_at}
          class="mt-1 block w-full"
        />
        {#if $createForm.errors.expires_at}
          <InputError message={$createForm.errors.expires_at} />
        {/if}
        <p class="mt-1 text-sm ">
          Leave empty for a token that never expires (not recommended for production).
        </p>
      </div>

      <div class="flex justify-end gap-3">
        <Button type="button" variant="secondary" onclick={closeCreateModal}>
          Cancel
        </Button>
        <Button type="submit" disabled={$createForm.processing}>
          {$createForm.processing ? 'Creating...' : 'Create Token'}
        </Button>
      </div>
    </form>
  {/snippet}
</Modal>

<!-- New Token Display Modal -->
{#if newMcpToken}
  <Modal bind:this={tokenModal} onclose={closeTokenModal}>
    {#snippet title()}
      MCP Token Created
    {/snippet}

    {#snippet children()}
      <div class="space-y-6">
        <div class="rounded-md bg-yellow-50 p-4">
          <div class="text-sm text-yellow-700">
            <strong>Important:</strong> Copy this token now. You won't be able to see it again!
          </div>
        </div>

        <div>
          <InputLabel value="Your Token" />
          <div class="mt-1 flex rounded-md">
            <Input
              name="token"
              type="text"
              readonly
              value={newMcpToken.token}
              class="flex-1 rounded-r-none border-r-0"
            />
            <Button
              type="button"
              variant="secondary"
              class="rounded-l-none"
              onclick={() => copyToClipboard(newMcpToken.token)}
            >
              Copy
            </Button>
          </div>
        </div>

        <div>
          <InputLabel value="Formatted for MCP (includes tenant)" />
          <div class="mt-1 flex rounded-md">
            <Input
              name="formatted_token"
              type="text"
              readonly
              value={newMcpToken.formatted_token}
              class="flex-1 rounded-r-none border-r-0"
            />
            <Button
              type="button"
              variant="secondary"
              class="rounded-l-none"
              onclick={() => copyToClipboard(newMcpToken.formatted_token)}
            >
              Copy
            </Button>
          </div>
          <p class="mt-2 text-sm ">
            Use this formatted token in your MCP client configuration.
          </p>
        </div>

        <div class="flex justify-end">
          <Button type="button" onclick={closeTokenModal}>
            I've Copied the Token
          </Button>
        </div>
      </div>
    {/snippet}
  </Modal>
{/if}

<!-- Connection Info Modal -->
{#if mcpConnectionInfo}
  <Modal bind:this={connectionModal} onclose={closeConnectionModal}>
    {#snippet title()}
      MCP Connection Information
    {/snippet}

    {#snippet children()}
      <div class="space-y-6">
        <div>
          <h4 class="font-medium ">Tenant Information</h4>
          <dl class="mt-2 space-y-1 text-sm">
            <div class="flex">
              <dt class="w-20 ">Host:</dt>
              <dd class="">{mcpConnectionInfo.tenant_host}</dd>
            </div>
            <div class="flex">
              <dt class="w-20 ">Name:</dt>
              <dd class="">{mcpConnectionInfo.tenant_name}</dd>
            </div>
            <div class="flex">
              <dt class="w-20 ">MCP URL:</dt>
              <dd class=" break-all">{mcpConnectionInfo.mcp_url}</dd>
            </div>
          </dl>
        </div>

        <div>
          <h4 class="font-medium ">VS Code Configuration</h4>
          <p class="mt-1 text-sm ">Add this to your VS Code settings:</p>
          <pre class="mt-2 rounded-md bg-gray-100 p-3 text-xs overflow-x-auto"><code>{JSON.stringify(mcpConnectionInfo.example_config.vscode, null, 2).replace(':YOUR_TOKEN', ':YOUR_ACTUAL_TOKEN')}</code></pre>
          <Button
            type="button"
            variant="secondary"
            class="mt-2"
            onclick={() => copyToClipboard(JSON.stringify(mcpConnectionInfo.example_config.vscode, null, 2))}
          >
            Copy VS Code Config
          </Button>
        </div>

        <div>
          <h4 class="font-medium ">Authentication Format</h4>
          <p class="mt-1 text-sm ">Use this format in the Authorization header:</p>
          <code class="mt-1 block rounded-md bg-gray-100 p-2 text-sm">
            Bearer {mcpConnectionInfo.auth_format.replace(':YOUR_TOKEN', ':YOUR_ACTUAL_TOKEN')}
          </code>
        </div>

        <div class="flex justify-end">
          <Button type="button" onclick={closeConnectionModal}>
            Close
          </Button>
        </div>
      </div>
    {/snippet}
  </Modal>
{/if}

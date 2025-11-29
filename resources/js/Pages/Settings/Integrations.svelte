<!-- Settings/Integrations.svelte -->
<script>
import { onMount } from 'svelte';
import { router } from '@inertiajs/svelte';
  import AuthenticatedLayout from '$layouts/AuthenticatedLayout.svelte';

// Props from Laravel controller
let { 
    flash = {}, 
    confirmReplace = null, 
    auth,
    githubStatus = {
        loading: false,
        connected: false,
        username: null,
        status: null,
        connected_at: null,
        token_expired: false
    }
} = $props();

let isDisconnecting = $state(false);

// Load GitHub status on mount using Inertia
onMount(() => {
    if (githubStatus.loading) {
        loadGitHubStatus();
    }
});

function loadGitHubStatus() {
    router.get('/settings/integrations', {}, {
        preserveState: true,
        preserveScroll: true,
        preserveUrl: true,
        only: ['githubStatus']
    });
}

function connectGitHub() {
    window.location.href = '/settings/github/connect';
}

function disconnectGitHub() {
    if (!confirm('Are you sure you want to disconnect your GitHub account?')) {
        return;
    }
    
    isDisconnecting = true;
    
    router.delete('/settings/github/disconnect', {
        onFinish: () => {
            isDisconnecting = false;
        },
        onSuccess: () => {
            loadGitHubStatus(); // Refresh status
        }
    });
}

function handleConfirmReplace(confirm) {
    router.post('/settings/github/confirm-replace', {
        confirm: confirm ? 'yes' : 'no',
        temp_data: confirmReplace.temp_data
    }, {
        onSuccess: () => {
            if (confirm) {
                loadGitHubStatus(); // Refresh status after replacement
            }
        }
    });
}

function getStatusBadge(status, connected, tokenExpired) {
    if (!connected) {
        return { class: 'badge-neutral', text: 'Not Connected' };
    }
    if (tokenExpired) {
        return { class: 'badge-warning', text: 'Token Expired' };
    }
    if (status === 'invalid_token') {
        return { class: 'badge-error', text: 'Invalid Token' };
    }
    return { class: 'badge-success', text: 'Connected' };
}

function formatDate(dateString) {
    if (!dateString) return '';
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}
</script>

<svelte:head>
    <title>Integrations - Settings</title>
</svelte:head>

<AuthenticatedLayout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold">Integrations</h1>
            <p class="mt-2 text-base-content/70">
                Connect your external accounts to enhance your workflow
            </p>
        </div>

        <!-- Flash Messages -->
        {#if flash.success}
            <div class="alert alert-success mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{flash.success}</span>
            </div>
        {/if}

        {#if flash.error}
            <div class="alert alert-error mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{flash.error}</span>
            </div>
        {/if}

        {#if flash.info}
            <div class="alert alert-info mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{flash.info}</span>
            </div>
        {/if}

        <!-- Confirm Replace Dialog -->
        {#if confirmReplace}
            <div class="alert alert-warning mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.232 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
                <div>
                    <h3 class="font-bold">{confirmReplace.message}</h3>
                    <div class="py-2">
                        <p><strong>Current:</strong> @{confirmReplace.current_username}</p>
                        <p><strong>New:</strong> @{confirmReplace.new_username}</p>
                    </div>
                    <div class="flex gap-2 mt-4">
                        <button
                            onclick={() => handleConfirmReplace(true)}
                            class="btn btn-warning btn-sm"
                        >
                            Yes, Replace
                        </button>
                        <button
                            onclick={() => handleConfirmReplace(false)}
                            class="btn btn-outline btn-sm"
                        >
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        {/if}

        <!-- Integrations Card -->
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <div class="card-title border-b border-base-300 pb-4 mb-6">
                    <h2 class="text-xl font-semibold">Connected Services</h2>
                    <p class="text-sm text-base-content/70 font-normal">
                        Manage your external service connections
                    </p>
                </div>

                <!-- GitHub Integration -->
                <div class="flex items-center justify-between py-4">
                    <div class="flex items-center gap-4">
                        <!-- GitHub Icon -->
                        <div class="avatar placeholder">
                            <div class="bg-neutral text-neutral-content rounded-full w-12">
                                <svg class="w-8 h-8 ml-2 mt-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 0C5.374 0 0 5.373 0 12 0 17.302 3.438 21.8 8.207 23.387c.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23A11.509 11.509 0 0112 5.803c1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576C20.566 21.797 24 17.3 24 12c0-6.627-5.373-12-12-12z"/>
                                </svg>
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-medium">GitHub</h3>
                            <p class="text-sm text-base-content/70">
                                Access your repositories and git logs
                            </p>
                        </div>
                    </div>

                    <!-- Status and Actions -->
                    <div class="flex items-center gap-4">
                        {#if githubStatus.loading}
                            <div class="flex items-center gap-2">
                                <span class="loading loading-spinner loading-sm"></span>
                                <span class="text-sm text-base-content/70">Loading...</span>
                            </div>
                        {:else if githubStatus.connected}
                        {@const badge = getStatusBadge(githubStatus.status, githubStatus.connected, githubStatus.tokenExpired)}
                            <div class="text-right">
                                <div class="flex items-center gap-2 justify-end">
                                    <span class="badge {badge.class}">
                                        {badge.text}
                                    </span>
                                    <span class="text-sm font-medium">@{githubStatus.username}</span>
                                </div>
                                {#if githubStatus.connectedAt}
                                    <p class="text-xs text-base-content/50 mt-1">
                                        Connected {formatDate(githubStatus.connectedAt)}
                                    </p>
                                {/if}
                            </div>
                            <button
                                onclick={disconnectGitHub}
                                disabled={isDisconnecting}
                                class="btn btn-outline btn-error btn-sm"
                                class:loading={isDisconnecting}
                            >
                                {#if isDisconnecting}
                                    Disconnecting...
                                {:else}
                                    Disconnect
                                {/if}
                            </button>
                        {:else}
                            <button
                                onclick={connectGitHub}
                                class="btn btn-primary"
                            >
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 0C5.374 0 0 5.373 0 12 0 17.302 3.438 21.8 8.207 23.387c.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23A11.509 11.509 0 0112 5.803c1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576C20.566 21.797 24 17.3 24 12c0-6.627-5.373-12-12-12z"/>
                                </svg>
                                Connect GitHub
                            </button>
                        {/if}
                    </div>
                </div>

                <!-- Future integrations can be added here -->
                <!-- 
                <div class="divider"></div>
                
                <div class="flex items-center justify-between py-4">
                    <div class="flex items-center gap-4">
                        <div class="avatar placeholder">
                            <div class="bg-blue-500 text-white rounded-full w-12">
                                // Other service icon
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium">Other Service</h3>
                            <p class="text-sm text-base-content/70">
                                Description of other service
                            </p>
                        </div>
                    </div>
                    <button class="btn btn-outline">
                        Connect
                    </button>
                </div>
                -->
            </div>
        </div>

        <!-- Help Section -->
        <div class="mt-8">
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h3 class="card-title">Need Help?</h3>
                    <p class="text-base-content/70">
                        Having trouble connecting your accounts? Check out our integration guide or contact support.
                    </p>
                    <div class="card-actions justify-end">
                        <button class="btn btn-outline btn-sm">View Guide</button>
                        <button class="btn btn-ghost btn-sm">Contact Support</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </AuthenticatedLayout>
<script>
  import { onMount } from 'svelte';
  import { settingsStore } from '../stores/settings.js';

  let apiUrl = $state('http://localhost:8000');
  let apiToken = $state('');
  let userId = $state('');
  let isSaving = $state(false);
  let message = $state('');

  export let onConfigured;

  onMount(async () => {
    const settings = await settingsStore.load();
    apiUrl = settings.apiUrl || 'http://localhost:8000';
    apiToken = settings.apiToken || '';
    userId = settings.userId || '';
  });

  async function saveSettings() {
    if (!apiUrl || !apiToken || !userId) {
      message = 'Please fill in all fields';
      return;
    }

    isSaving = true;
    message = '';

    try {
      await settingsStore.save({ apiUrl, apiToken, userId });
      message = 'Settings saved successfully!';
      
      setTimeout(() => {
        if (onConfigured) {
          onConfigured();
        }
      }, 1000);
    } catch (error) {
      message = 'Error saving settings: ' + error.message;
    } finally {
      isSaving = false;
    }
  }

  async function testConnection() {
    if (!apiUrl || !apiToken) {
      message = 'Please enter API URL and token first';
      return;
    }

    isSaving = true;
    message = 'Testing connection...';

    try {
      const response = await fetch(`${apiUrl}/api/users`, {
        headers: {
          'Authorization': `Bearer ${apiToken}`,
          'Accept': 'application/json',
        }
      });

      if (response.ok) {
        message = 'Connection successful!';
      } else {
        message = 'Connection failed: ' + response.statusText;
      }
    } catch (error) {
      message = 'Connection error: ' + error.message;
    } finally {
      isSaving = false;
    }
  }
</script>

<div class="settings">
  <h2>Extension Settings</h2>
  
  <div class="form-group">
    <label for="apiUrl">API URL</label>
    <input
      id="apiUrl"
      type="text"
      bind:value={apiUrl}
      placeholder="http://localhost:8000"
      class="input"
    />
    <small>The URL of your Qadran instance</small>
  </div>

  <div class="form-group">
    <label for="userId">User ID</label>
    <input
      id="userId"
      type="text"
      bind:value={userId}
      placeholder="1"
      class="input"
    />
    <small>Your user ID in Qadran</small>
  </div>

  <div class="form-group">
    <label for="apiToken">API Token</label>
    <input
      id="apiToken"
      type="password"
      bind:value={apiToken}
      placeholder="Your API token"
      class="input"
    />
    <small>Generate a token in your Qadran account settings</small>
  </div>

  {#if message}
    <div class="message" class:error={message.includes('Error') || message.includes('failed')}>
      {message}
    </div>
  {/if}

  <div class="actions">
    <button 
      class="btn btn-secondary" 
      onclick={testConnection}
      disabled={isSaving}
    >
      Test Connection
    </button>
    <button 
      class="btn btn-primary" 
      onclick={saveSettings}
      disabled={isSaving}
    >
      {isSaving ? 'Saving...' : 'Save Settings'}
    </button>
  </div>

  <div class="help">
    <h3>How to get your API token:</h3>
    <ol>
      <li>Log in to your Qadran account</li>
      <li>Go to Settings → API Tokens</li>
      <li>Create a new token with required permissions</li>
      <li>Copy the token and paste it here</li>
    </ol>
  </div>
</div>

<style>
  .settings {
    max-width: 100%;
  }

  h2 {
    font-size: 18px;
    font-weight: 600;
    margin: 0 0 20px 0;
    color: #1f2937;
  }

  h3 {
    font-size: 14px;
    font-weight: 600;
    margin: 0 0 8px 0;
    color: #1f2937;
  }

  .form-group {
    margin-bottom: 16px;
  }

  label {
    display: block;
    margin-bottom: 6px;
    font-size: 13px;
    font-weight: 500;
    color: #374151;
  }

  .input {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 13px;
    box-sizing: border-box;
  }

  .input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
  }

  small {
    display: block;
    margin-top: 4px;
    font-size: 11px;
    color: #6b7280;
  }

  .message {
    padding: 10px 12px;
    margin: 16px 0;
    border-radius: 6px;
    font-size: 13px;
    background: #d1fae5;
    color: #065f46;
  }

  .message.error {
    background: #fee2e2;
    color: #991b1b;
  }

  .actions {
    display: flex;
    gap: 8px;
    margin-top: 20px;
  }

  .btn {
    flex: 1;
    padding: 10px 16px;
    border: none;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
  }

  .btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
  }

  .btn-primary {
    background: #667eea;
    color: white;
  }

  .btn-primary:hover:not(:disabled) {
    background: #5568d3;
  }

  .btn-secondary {
    background: #e5e7eb;
    color: #374151;
  }

  .btn-secondary:hover:not(:disabled) {
    background: #d1d5db;
  }

  .help {
    margin-top: 24px;
    padding: 16px;
    background: white;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
  }

  ol {
    margin: 0;
    padding-left: 20px;
    font-size: 12px;
    color: #6b7280;
  }

  ol li {
    margin-bottom: 4px;
  }
</style>

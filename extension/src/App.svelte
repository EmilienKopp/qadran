<script>
  import { onMount } from 'svelte';
  import Settings from './components/Settings.svelte';
  import ClockPanel from './components/ClockPanel.svelte';
  import VoiceCommands from './components/VoiceCommands.svelte';
  import { apiService } from './services/api.js';
  import { settingsStore } from './stores/settings.js';

  let isConfigured = $state(false);
  let isLoading = $state(true);
  let currentView = $state('clock'); // 'clock', 'voice', 'settings'

  onMount(async () => {
    // Load settings from chrome storage
    const settings = await settingsStore.load();
    isConfigured = settings.apiUrl && settings.apiToken;
    isLoading = false;
  });

  function showSettings() {
    currentView = 'settings';
  }

  function showClock() {
    currentView = 'clock';
  }

  function showVoice() {
    currentView = 'voice';
  }

  function handleConfigured() {
    isConfigured = true;
    currentView = 'clock';
  }
</script>

<main class="container">
  <header class="header">
    <h1 class="title">Qadran</h1>
    {#if isConfigured}
      <nav class="nav">
        <button 
          class="nav-btn" 
          class:active={currentView === 'clock'}
          onclick={showClock}
        >
          Clock
        </button>
        <button 
          class="nav-btn" 
          class:active={currentView === 'voice'}
          onclick={showVoice}
        >
          Voice
        </button>
        <button 
          class="nav-btn" 
          class:active={currentView === 'settings'}
          onclick={showSettings}
        >
          Settings
        </button>
      </nav>
    {/if}
  </header>

  <div class="content">
    {#if isLoading}
      <div class="loading">Loading...</div>
    {:else if !isConfigured || currentView === 'settings'}
      <Settings onConfigured={handleConfigured} />
    {:else if currentView === 'clock'}
      <ClockPanel />
    {:else if currentView === 'voice'}
      <VoiceCommands />
    {/if}
  </div>
</main>

<style>
  .container {
    display: flex;
    flex-direction: column;
    width: 350px;
    min-height: 400px;
    background: #f9fafb;
  }

  .header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 16px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }

  .title {
    margin: 0 0 12px 0;
    font-size: 20px;
    font-weight: 600;
  }

  .nav {
    display: flex;
    gap: 8px;
  }

  .nav-btn {
    flex: 1;
    padding: 8px 12px;
    background: rgba(255, 255, 255, 0.2);
    border: none;
    border-radius: 6px;
    color: white;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.2s;
  }

  .nav-btn:hover {
    background: rgba(255, 255, 255, 0.3);
  }

  .nav-btn.active {
    background: white;
    color: #667eea;
    font-weight: 600;
  }

  .content {
    flex: 1;
    padding: 16px;
  }

  .loading {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 200px;
    color: #6b7280;
  }
</style>

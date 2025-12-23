<script>
  import { onMount, onDestroy } from 'svelte';
  import { apiService } from '../services/api.js';
  import { settingsStore } from '../stores/settings.js';
  import dayjs from 'dayjs';
  import duration from 'dayjs/plugin/duration';

  dayjs.extend(duration);

  let projects = $state([]);
  let selectedProjectId = $state('');
  let activeEntry = $state(null);
  let isLoading = $state(false);
  let message = $state('');
  let elapsedTime = $state('00:00:00');
  let timer = null;

  onMount(async () => {
    await loadProjects();
    await checkActiveEntry();
    
    // Start timer if there's an active entry
    if (activeEntry) {
      startTimer();
    }
  });

  onDestroy(() => {
    if (timer) {
      clearInterval(timer);
    }
  });

  async function loadProjects() {
    try {
      projects = await apiService.getProjects();
      if (projects.length > 0 && !selectedProjectId) {
        selectedProjectId = projects[0].id;
      }
    } catch (error) {
      message = 'Error loading projects: ' + error.message;
    }
  }

  async function checkActiveEntry() {
    try {
      const settings = await settingsStore.load();
      activeEntry = await apiService.getActiveClockEntry(settings.userId);
      
      if (activeEntry && activeEntry.project_id) {
        selectedProjectId = activeEntry.project_id;
      }
    } catch (error) {
      // No active entry is fine
      activeEntry = null;
    }
  }

  async function clockIn() {
    if (!selectedProjectId) {
      message = 'Please select a project';
      return;
    }

    isLoading = true;
    message = '';

    try {
      const settings = await settingsStore.load();
      activeEntry = await apiService.clockIn({
        user_id: parseInt(settings.userId),
        project_id: parseInt(selectedProjectId),
        in: new Date().toISOString(),
      });
      
      message = 'Clocked in successfully!';
      startTimer();
      setTimeout(() => { message = ''; }, 3000);
    } catch (error) {
      message = 'Error clocking in: ' + error.message;
    } finally {
      isLoading = false;
    }
  }

  async function clockOut() {
    isLoading = true;
    message = '';

    try {
      const settings = await settingsStore.load();
      await apiService.clockOut(settings.userId, new Date().toISOString());
      
      message = 'Clocked out successfully!';
      activeEntry = null;
      stopTimer();
      setTimeout(() => { message = ''; }, 3000);
    } catch (error) {
      message = 'Error clocking out: ' + error.message;
    } finally {
      isLoading = false;
    }
  }

  function startTimer() {
    if (timer) clearInterval(timer);
    
    timer = setInterval(() => {
      if (activeEntry && activeEntry.in) {
        const start = dayjs(activeEntry.in);
        const now = dayjs();
        const diff = now.diff(start, 'second');
        
        const hours = Math.floor(diff / 3600);
        const minutes = Math.floor((diff % 3600) / 60);
        const seconds = diff % 60;
        
        elapsedTime = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
      }
    }, 1000);
  }

  function stopTimer() {
    if (timer) {
      clearInterval(timer);
      timer = null;
    }
    elapsedTime = '00:00:00';
  }

  function getProjectName(projectId) {
    const project = projects.find(p => p.id == projectId);
    return project ? project.name : 'Unknown Project';
  }
</script>

<div class="clock-panel">
  <h2>Time Tracking</h2>

  {#if activeEntry}
    <div class="active-session">
      <div class="status-badge">
        <span class="pulse"></span>
        Active Session
      </div>
      
      <div class="timer">
        {elapsedTime}
      </div>

      <div class="project-info">
        <strong>Project:</strong> {getProjectName(activeEntry.project_id)}
      </div>

      <div class="session-start">
        Started: {dayjs(activeEntry.in).format('HH:mm:ss')}
      </div>

      <button 
        class="btn btn-danger btn-large" 
        onclick={clockOut}
        disabled={isLoading}
      >
        {isLoading ? 'Clocking Out...' : 'Clock Out'}
      </button>
    </div>
  {:else}
    <div class="clock-in-form">
      <div class="form-group">
        <label for="project">Select Project</label>
        <select 
          id="project"
          bind:value={selectedProjectId}
          class="select"
          disabled={isLoading || projects.length === 0}
        >
          {#if projects.length === 0}
            <option value="">No projects available</option>
          {:else}
            {#each projects as project}
              <option value={project.id}>{project.name}</option>
            {/each}
          {/if}
        </select>
      </div>

      <button 
        class="btn btn-success btn-large" 
        onclick={clockIn}
        disabled={isLoading || !selectedProjectId}
      >
        {isLoading ? 'Clocking In...' : 'Clock In'}
      </button>
    </div>
  {/if}

  {#if message}
    <div class="message" class:error={message.includes('Error')}>
      {message}
    </div>
  {/if}

  <div class="info">
    <p>Track your time by clocking in to a project. Your session will be recorded in Qadran.</p>
  </div>
</div>

<style>
  .clock-panel {
    max-width: 100%;
  }

  h2 {
    font-size: 18px;
    font-weight: 600;
    margin: 0 0 20px 0;
    color: #1f2937;
  }

  .active-session {
    background: white;
    border-radius: 8px;
    padding: 20px;
    border: 2px solid #10b981;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
  }

  .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 12px;
    background: #d1fae5;
    color: #065f46;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 16px;
  }

  .pulse {
    width: 8px;
    height: 8px;
    background: #10b981;
    border-radius: 50%;
    animation: pulse 2s ease-in-out infinite;
  }

  @keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.4; }
  }

  .timer {
    font-size: 48px;
    font-weight: 700;
    text-align: center;
    color: #1f2937;
    margin: 20px 0;
    font-variant-numeric: tabular-nums;
    letter-spacing: 0.05em;
  }

  .project-info,
  .session-start {
    text-align: center;
    margin: 12px 0;
    color: #6b7280;
    font-size: 14px;
  }

  .project-info strong {
    color: #374151;
  }

  .clock-in-form {
    background: white;
    border-radius: 8px;
    padding: 20px;
    border: 1px solid #e5e7eb;
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

  .select {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 14px;
    background: white;
    box-sizing: border-box;
  }

  .select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
  }

  .select:disabled {
    background: #f3f4f6;
    cursor: not-allowed;
  }

  .btn {
    width: 100%;
    padding: 12px 16px;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
  }

  .btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
  }

  .btn-large {
    padding: 14px 20px;
    font-size: 15px;
  }

  .btn-success {
    background: #10b981;
    color: white;
  }

  .btn-success:hover:not(:disabled) {
    background: #059669;
  }

  .btn-danger {
    background: #ef4444;
    color: white;
  }

  .btn-danger:hover:not(:disabled) {
    background: #dc2626;
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

  .info {
    margin-top: 16px;
    padding: 12px;
    background: #f3f4f6;
    border-radius: 6px;
  }

  .info p {
    margin: 0;
    font-size: 12px;
    color: #6b7280;
    line-height: 1.5;
  }
</style>

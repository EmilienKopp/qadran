import { settingsStore } from '../stores/settings.js';

class ApiService {
  async request(endpoint, options = {}) {
    const settings = await settingsStore.load();
    
    if (!settings.apiUrl || !settings.apiToken) {
      throw new Error('API not configured. Please set up your API URL and token in settings.');
    }

    const url = `${settings.apiUrl}${endpoint}`;
    const headers = {
      'Authorization': `Bearer ${settings.apiToken}`,
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      ...options.headers,
    };

    const response = await fetch(url, {
      ...options,
      headers,
    });

    if (!response.ok) {
      const errorText = await response.text();
      throw new Error(`API Error: ${response.status} - ${errorText}`);
    }

    return response.json();
  }

  async getProjects() {
    return this.request('/api/projects');
  }

  async getActiveClockEntry(userId) {
    return this.request(`/api/clock-entries/active-by-user/${userId}`);
  }

  async clockIn(data) {
    return this.request('/api/clock-entries', {
      method: 'POST',
      body: JSON.stringify(data),
    });
  }

  async clockOut(userId, outTime) {
    // Get active entry first
    const activeEntry = await this.getActiveClockEntry(userId);
    
    if (!activeEntry) {
      throw new Error('No active clock entry found');
    }

    // Update it with the out time
    return this.request(`/api/clock-entries/${activeEntry.id}`, {
      method: 'PUT',
      body: JSON.stringify({
        end_time: outTime,
      }),
    });
  }

  async sendVoiceCommand(audioBlob) {
    const settings = await settingsStore.load();
    
    if (!settings.apiUrl || !settings.apiToken) {
      throw new Error('API not configured');
    }

    const formData = new FormData();
    formData.append('audio', audioBlob, 'voice-command.webm');

    const url = `${settings.apiUrl}/voice-assistant/transcribe`;
    const response = await fetch(url, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${settings.apiToken}`,
        'Accept': 'application/json',
      },
      body: formData,
    });

    if (!response.ok) {
      const errorText = await response.text();
      throw new Error(`API Error: ${response.status} - ${errorText}`);
    }

    return response.json();
  }
}

export const apiService = new ApiService();

class SettingsStore {
  async load() {
    return new Promise((resolve) => {
      chrome.storage.local.get(['apiUrl', 'apiToken', 'userId'], (result) => {
        resolve({
          apiUrl: result.apiUrl || '',
          apiToken: result.apiToken || '',
          userId: result.userId || '',
        });
      });
    });
  }

  async save(settings) {
    return new Promise((resolve, reject) => {
      chrome.storage.local.set(settings, () => {
        if (chrome.runtime.lastError) {
          reject(new Error(chrome.runtime.lastError.message));
        } else {
          resolve();
        }
      });
    });
  }

  async clear() {
    return new Promise((resolve) => {
      chrome.storage.local.clear(() => {
        resolve();
      });
    });
  }
}

export const settingsStore = new SettingsStore();

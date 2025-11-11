// Background service worker for the extension
// This can be used for background tasks, notifications, etc.

chrome.runtime.onInstalled.addListener(() => {
  console.log('Qadran Time Tracker extension installed');
});

// Listen for messages from the popup
chrome.runtime.onMessage.addListener((request, sender, sendResponse) => {
  if (request.action === 'notification') {
    chrome.notifications.create({
      type: 'basic',
      iconUrl: 'icons/icon48.png',
      title: request.title || 'Qadran Time Tracker',
      message: request.message,
    });
  }
  
  return true;
});

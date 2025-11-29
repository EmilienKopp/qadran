# Qadran Chrome Extension

A Chrome extension for the Qadran time tracking application that allows you to:
- Clock in and out of projects
- Dispatch voice commands to the Qadran AI assistant

## Features

### Clock In/Out
- View all your projects
- Clock in to a project with one click
- See elapsed time for active sessions
- Clock out when done

### Voice Commands
- Record voice commands using your microphone
- Get transcriptions and AI assistant responses
- Type commands as an alternative to voice

## Installation

### Development Mode

1. Build the extension:
   ```bash
   cd extension
   npm install
   npm run build
   ```

2. Load the extension in Chrome:
   - Open Chrome and navigate to `chrome://extensions/`
   - Enable "Developer mode" in the top right
   - Click "Load unpacked"
   - Select the `extension/dist` folder

### Configuration

1. Click the extension icon in your Chrome toolbar
2. Enter your Qadran API details:
   - **API URL**: The URL of your Qadran instance (e.g., `http://localhost:8000`)
   - **User ID**: Your user ID in Qadran
   - **API Token**: A personal access token from Qadran

3. Click "Test Connection" to verify your settings
4. Click "Save Settings" to proceed

## Getting an API Token

1. Log in to your Qadran account
2. Navigate to Settings → API Tokens
3. Click "Create Token"
4. Give it a name (e.g., "Chrome Extension")
5. Select the required permissions
6. Copy the token and paste it in the extension settings

## Usage

### Clocking In/Out

1. Open the extension popup
2. Select a project from the dropdown
3. Click "Clock In" to start tracking time
4. Click "Clock Out" when you're done

The extension will show a live timer for your active session.

### Voice Commands

1. Open the extension popup
2. Navigate to the "Voice" tab
3. Click "Start Voice Recording"
4. Speak your command
5. Click "Stop Recording"
6. View the transcript and assistant response

Alternatively, type your command in the text area and click "Send Command".

## Development

### Prerequisites
- Node.js 18+
- npm or pnpm

### Build Commands

```bash
# Install dependencies
npm install

# Development build (watch mode)
npm run dev

# Production build
npm run build

# Preview build
npm run preview
```

### Project Structure

```
extension/
├── manifest.json          # Chrome extension manifest
├── package.json           # Node dependencies
├── vite.config.js         # Vite build configuration
├── src/
│   ├── App.svelte         # Main app component
│   ├── popup.html         # Extension popup HTML
│   ├── popup.js           # Extension popup entry point
│   ├── background.js      # Background service worker
│   ├── components/
│   │   ├── Settings.svelte      # Settings page
│   │   ├── ClockPanel.svelte    # Clock in/out interface
│   │   └── VoiceCommands.svelte # Voice commands interface
│   ├── services/
│   │   └── api.js         # API service
│   └── stores/
│       └── settings.js    # Settings store
└── dist/                  # Built extension (generated)
```

## Troubleshooting

### Connection Issues
- Verify your API URL is correct and accessible
- Check that CORS is properly configured on your Qadran server
- Ensure your API token has the required permissions

### Voice Commands Not Working
- Grant microphone permissions when prompted
- Check that your Qadran instance has the voice assistant feature enabled
- Verify the voice assistant API endpoint is accessible

### Extension Not Loading
- Check the Chrome DevTools console for errors
- Verify all files are present in the `dist` folder
- Try rebuilding the extension with `npm run build`

## Security Notes

- API tokens are stored securely in Chrome's local storage
- Tokens are never shared with third parties
- Voice recordings are sent directly to your Qadran instance
- No data is collected by the extension itself

## Support

For issues or questions:
1. Check the Qadran documentation
2. Open an issue on the Qadran GitHub repository
3. Contact your Qadran administrator

## License

This extension is part of the Qadran project. See the main repository for license information.

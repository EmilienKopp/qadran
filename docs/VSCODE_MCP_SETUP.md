# VS Code MCP Integration Setup Guide

This guide shows you how to set up VS Code to authenticate with your Qadran tenant's MCP server.

## Step 1: Generate Your MCP Token

1. **Log into your tenant**: Go to `https://YOUR_TENANT.qadran.io/login`
2. **Navigate to Settings**: Click your profile menu → "Profile"  
3. **Find MCP Tokens section**: Scroll down to "MCP Access Tokens"
4. **Create new token**:
   - Click "Create New MCP Token"
   - Enter a descriptive name (e.g., "VS Code Integration")
   - Optional: Set an expiration date
   - Click "Create Token"
5. **Copy the formatted token**: When the modal appears, copy the "Formatted for MCP" token

## Step 2: Install VS Code MCP Extension

Currently, the main MCP extensions for VS Code are:

```vscode-extensions
saoudrizwan.claude-dev,anthropic.claude-code,automatalabs.copilot-mcp
```

**Recommended**: Install **Cline** (formerly Claude Dev) or **Claude Code for VS Code** which have native MCP support.

## Step 3: Configure Your MCP Client

### Option A: Using Cline Extension

1. **Open Cline settings** in VS Code
2. **Add MCP Server Configuration**:
   ```json
   {
     "mcpServers": {
       "qadran": {
         "command": "curl",
         "args": [
           "-X", "POST",
           "https://api.qadran.io/mcp/qadran",
           "-H", "Content-Type: application/json",
           "-H", "Authorization: Bearer tenant:YOUR_TENANT:YOUR_TOKEN_HERE",
           "-d", "@-"
         ]
       }
     }
   }
   ```

3. **Replace placeholders**:
   - Replace `YOUR_TENANT` with your actual tenant host (e.g., `acme`)
   - Replace `YOUR_TOKEN_HERE` with the token you copied from Step 1
   - Or use the complete formatted token from the UI

### Option B: Using Claude Code Extension

1. **Open Command Palette**: `Ctrl+Shift+P` (Windows/Linux) or `Cmd+Shift+P` (Mac)
2. **Search**: "Claude Code: Configure MCP Servers"
3. **Add server configuration** using the same JSON format as above

### Option C: Manual VS Code Settings

1. **Open VS Code settings** (`Ctrl+,` or `Cmd+,`)
2. **Search**: "MCP" or find the extension's settings
3. **Add server configuration** in the extension's MCP servers setting

## Step 4: Environment-Specific URLs

Use the correct URL for your environment:

### Production (Non-Staging)
```
https://api.qadran.io/mcp/qadran
```

### Staging
```
https://qadran.io/mcp/qadran
```

### Local Development
```
http://localhost:8000/mcp/qadran
```

## Step 5: Test Your Connection

1. **Restart VS Code** after configuration
2. **Open the MCP extension** (Cline, Claude Code, etc.)
3. **Test connection**: Try asking the AI assistant to:
   - "List my projects"
   - "Show my recent time entries"
   - "Create a new task"

If successful, the assistant will be able to interact with your Qadran data!

## Example Complete Configuration

Here's a complete configuration example for Cline:

```json
{
  "mcpServers": {
    "qadran": {
      "command": "curl",
      "args": [
        "-X", "POST",
        "https://api.qadran.io/mcp/qadran",
        "-H", "Content-Type: application/json",
        "-H", "Authorization: Bearer tenant:acme:1|abc123def456ghi789...",
        "-d", "@-"
      ]
    }
  }
}
```

## Security Notes

- **Never share your MCP tokens** - they provide full access to your Qadran data
- **Set expiration dates** for tokens when possible
- **Regularly rotate tokens** and delete unused ones
- **Use descriptive names** to track which tokens are for which integrations
- **Delete tokens immediately** if you suspect they've been compromised

## Troubleshooting

### "Authentication failed" errors:
- Verify your token is correct and hasn't expired
- Check that you're using the right tenant host
- Ensure the token has `mcp:use` permissions

### "Tenant not found" errors:
- Verify your tenant host is correct
- Check that your tenant is active
- Make sure you're using the right environment URL

### Connection timeouts:
- Check your internet connection
- Verify the MCP server URL is correct for your environment
- Try refreshing your token

## Need Help?

1. **View Connection Info**: In your Qladran profile settings, click "View Connection Info" to get exact configuration details for your tenant
2. **Check Token Status**: Your active MCP tokens are listed in the profile settings with their last used dates
3. **Generate New Token**: If your token isn't working, create a new one and update your VS Code configuration

## Available MCP Tools

Once connected, your AI assistant can use these Qadran tools:

- **Project Management**: Create projects, list projects
- **Task Management**: Create tasks, list tasks  
- **Time Tracking**: Clock in/out, generate reports
- **Activity Logging**: Create activities, list activity types

Ask your AI assistant things like:
- "Create a new project called 'Website Redesign'"
- "Show me all my active tasks"
- "Clock me in to the Marketing project"
- "Generate a time report for today"
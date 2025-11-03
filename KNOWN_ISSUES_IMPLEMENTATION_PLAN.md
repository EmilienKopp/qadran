# Known Issues System Implementation Plan

## Overview
Implement a public-facing known issues tracking system that receives Jira issue data from n8n webhooks and displays them on a fully public page.

## Data Source
- n8n service queries Jira on a schedule
- Posts issue data to webhook endpoint on this app
- Reference data structure: `jira.example.json`

## Database Schema

### Table: `known_issues`
Primary table for storing Jira issues:
- `id` - Primary key
- `jira_id` - Jira internal ID (string)
- `jira_key` - Jira issue key (e.g., "QI-1", "CRM-6") - UNIQUE
- `summary` - Issue title/summary
- `description` - Issue description (nullable)
- `status` - Current status name (e.g., "To Do", "In Progress")
- `status_category` - Status category (e.g., "new", "indeterminate", "done")
- `status_category_name` - Human-readable category name
- `status_color` - Color name for UI (e.g., "blue-gray", "yellow", "green")
- `priority` - Priority level
- `priority_icon_url` - Priority icon URL
- `issue_type` - Type of issue (Bug, Story, etc.)
- `issue_type_icon_url` - Issue type icon URL
- `first_reported_at` - When created in Jira (timestamp)
- `last_updated_at` - When last updated in Jira (timestamp)
- `current_status_since` - When the current status started (timestamp)
- `metadata` - Full Jira payload (JSONB)
- `timestamps` - Laravel created_at/updated_at

### Table: `known_issue_status_history`
Track status changes over time:
- `id` - Primary key
- `known_issue_id` - Foreign key to known_issues
- `status` - Status name
- `status_category` - Status category
- `changed_at` - When this status was set
- `duration_seconds` - How long issue was in this status (nullable, calculated when status changes)
- `timestamps`

## API Endpoint

### POST `/api/webhooks/jira/known-issues`
- **Authentication**: None (public webhook, but could add token validation if needed)
- **Request Body**: Array of Jira issue objects (matches jira.example.json structure)
- **Logic**:
  1. Parse incoming Jira issues
  2. For each issue:
     - Check if issue exists by `jira_key`
     - If exists: Update fields and check for status changes
     - If status changed: Create status history record and update `current_status_since`
     - If new: Create new issue record
  3. Return success response with count of created/updated issues

## Public Page

### Route: `GET /known-issues`
- **Scope**: Landlord (system-wide, not tenant-specific)
- **Authentication**: None (fully public)
- **Component**: `/resources/js/Pages/KnownIssues/Index.svelte`

### Features:
1. Display all known issues in a table/card layout
2. Show for each issue:
   - Jira key (e.g., "QI-1")
   - Summary
   - Issue type with icon
   - Priority with icon
   - Current status (color-coded by status category)
   - First reported date
   - Time in current status (e.g., "3 days", "2 hours")
3. Sortable columns
4. Filter by status category
5. Responsive design

## Scope
- **Connection**: Landlord (system-wide)
- Issues are tracked for the entire application, not per tenant

## Implementation Steps

1. ✅ Create database migration for `known_issues` table
2. ✅ Create database migration for `known_issue_status_history` table
3. ✅ Create `KnownIssue` model with relationships
4. ✅ Create `KnownIssueStatusHistory` model
5. ✅ Create `KnownIssueWebhookController` for handling n8n webhook
6. ✅ Add webhook route to `routes/api.php`
7. ✅ Add public route for known issues page to `routes/web.php`
8. ✅ Create `KnownIssuesController` for serving the public page
9. ✅ Create Svelte page component at `resources/js/Pages/KnownIssues/Index.svelte`
10. ✅ Add styling and polish
11. ✅ Test webhook endpoint
12. ✅ Test public page

## Future Enhancements (Optional)
- Add webhook authentication token
- Subscribe to issue notifications
- Show issue resolution history
- Link to Jira for more details
- Add search functionality
- Show trend graphs (issues created/resolved over time)

# Qadran MCP Server

This document describes the MCP (Model Context Protocol) server implementation for the Qadran time tracking application.

## Overview

The Qadran MCP Server allows AI agents to interact with the Qadran application for project management, task tracking, and time tracking operations.

## Server Configuration

The server is registered in `routes/ai.php` and can be started using:

```bash
php artisan mcp:start qadran
```

## Available Tools

### Project Management

#### create_project
Create a new project in the Qadran system.

**Parameters:**
- `name` (required): The name of the project
- `description` (optional): A description of the project
- `type` (optional): The type of project (open_source, commercial, internal, educational, poc, prototype, portfolio, research, other)
- `status` (optional): The status of the project (active, inactive, archived, deleted, pending)
- `start_date` (optional): The start date of the project (ISO 8601 format)
- `end_date` (optional): The end date of the project (ISO 8601 format)
- `location` (optional): The location of the project
- `icon` (optional): An icon for the project

**Example:**
```json
{
  "name": "Website Redesign",
  "description": "Redesigning the company website",
  "type": "commercial",
  "status": "active"
}
```

#### list_projects
List all projects, optionally filtered by status or type.

**Parameters:**
- `status` (optional): Filter projects by status
- `type` (optional): Filter projects by type
- `limit` (optional): Maximum number of projects to return (default 50, max 100)

**Example:**
```json
{
  "status": "active",
  "limit": 10
}
```

### Task Management

#### create_task
Create a new task, optionally associated with a project.

**Parameters:**
- `name` (required): The name of the task
- `description` (optional): A description of the task
- `project_id` (optional): The ID of the project this task belongs to
- `priority` (optional): The priority of the task (0-6, -1 for blocker)
- `completed` (optional): Whether the task is completed

**Example:**
```json
{
  "name": "Design homepage mockup",
  "description": "Create a mockup for the new homepage",
  "project_id": 1,
  "priority": 3
}
```

#### list_tasks
List all tasks, optionally filtered by project, completion status, or priority.

**Parameters:**
- `project_id` (optional): Filter tasks by project ID
- `completed` (optional): Filter by completion status
- `priority` (optional): Filter by priority level
- `limit` (optional): Maximum number of tasks to return (default 50, max 100)

**Example:**
```json
{
  "project_id": 1,
  "completed": false,
  "limit": 20
}
```

### Time Tracking

#### clock_in
Start tracking time for a project or task.

**Parameters:**
- `user_id` (required): The ID of the user clocking in
- `project_id` (optional): The ID of the project to clock in to
- `timezone` (optional): The timezone for the clock entry
- `notes` (optional): Notes for the clock entry

**Example:**
```json
{
  "user_id": 1,
  "project_id": 1,
  "notes": "Working on homepage design"
}
```

#### clock_out
Stop tracking time.

**Parameters:**
- `user_id` (optional): The ID of the user clocking out (required if clock_entry_id not provided)
- `clock_entry_id` (optional): The ID of the specific clock entry to clock out
- `notes` (optional): Additional notes for the clock entry

**Example:**
```json
{
  "user_id": 1,
  "notes": "Completed homepage mockup"
}
```

#### generate_report
Generate a time tracking report for a specific date or date range.

**Parameters:**
- `date` (optional): The date for the report (YYYY-MM-DD format, defaults to today)
- `user_id` (optional): Filter report by user ID

**Example:**
```json
{
  "date": "2025-11-02",
  "user_id": 1
}
```

### Activity Logging

#### create_activity
Create a single activity log entry associated with a clock entry.

**Parameters:**
- `clock_entry_id` (required): The ID of the clock entry this activity belongs to
- `activity_type_id` (optional): The ID of the activity type
- `task_id` (optional): The ID of the task associated with this activity
- `start_offset_seconds` (optional): Start time offset in seconds from clock entry start
- `end_offset_seconds` (optional): End time offset in seconds from clock entry start
- `notes` (optional): Notes about this activity

**Example:**
```json
{
  "clock_entry_id": 1,
  "activity_type_id": 2,
  "task_id": 5,
  "start_offset_seconds": 0,
  "end_offset_seconds": 3600,
  "notes": "Worked on homepage design"
}
```

#### create_activity_batch
Create multiple activity log entries for the same clock entry in one operation. This is useful for logging several activities that occurred during the same day.

**Parameters:**
- `clock_entry_id` (required): The ID of the clock entry these activities belong to
- `activities` (required): Array of activity objects to create, each with:
  - `activity_type_id` (optional): The ID of the activity type
  - `task_id` (optional): The ID of the task associated with this activity
  - `start_offset_seconds` (optional): Start time offset in seconds from clock entry start
  - `end_offset_seconds` (optional): End time offset in seconds from clock entry start
  - `notes` (optional): Notes about this activity

**Example:**
```json
{
  "clock_entry_id": 1,
  "activities": [
    {
      "activity_type_id": 2,
      "task_id": 5,
      "start_offset_seconds": 0,
      "end_offset_seconds": 3600,
      "notes": "Designed homepage mockup"
    },
    {
      "activity_type_id": 3,
      "task_id": 6,
      "start_offset_seconds": 3600,
      "end_offset_seconds": 7200,
      "notes": "Reviewed code changes"
    }
  ]
}
```

#### list_activity_types
List all available activity types in the system.

**Parameters:**
- `name` (optional): Filter activity types by name (partial match)
- `limit` (optional): Maximum number of activity types to return (default 50, max 100)

**Example:**
```json
{
  "name": "development",
  "limit": 10
}
```

## JSON Resources

All responses use Laravel JSON Resources for consistent formatting:

### ProjectResource
Returns project data including:
- id, name, description
- type, status
- start_date, end_date
- location, icon
- metadata, timestamps

### TaskResource
Returns task data including:
- id, name, description
- completed, project_id
- priority
- timestamps
- related project (when loaded)

### ClockEntryResource
Returns clock entry data including:
- id, user_id, project_id
- in, out timestamps
- timezone, notes
- duration_seconds
- timestamps
- related project (when loaded)

### ReportResource
Returns report data including:
- date
- entries (collection of ClockEntryResource)
- total_hours, total_seconds
- projects (breakdown by project)

### ActivityLogResource
Returns activity log data including:
- id, clock_entry_id, activity_type_id, task_id
- start_offset_seconds, end_offset_seconds, duration_seconds
- notes
- timestamps
- related clock_entry, activity_type, task (when loaded)

### ActivityTypeResource
Returns activity type data including:
- id, name, description
- color, icon
- timestamps

## Workflow Examples

### Starting work on a project

1. Create or list projects:
```json
{
  "tool": "create_project",
  "arguments": {
    "name": "Client Website",
    "type": "commercial",
    "status": "active"
  }
}
```

2. Clock in:
```json
{
  "tool": "clock_in",
  "arguments": {
    "user_id": 1,
    "project_id": 1
  }
}
```

3. Clock out when done:
```json
{
  "tool": "clock_out",
  "arguments": {
    "user_id": 1
  }
}
```

### Managing tasks

1. Create tasks for a project:
```json
{
  "tool": "create_task",
  "arguments": {
    "name": "Design homepage",
    "project_id": 1,
    "priority": 3
  }
}
```

2. List tasks for the project:
```json
{
  "tool": "list_tasks",
  "arguments": {
    "project_id": 1,
    "completed": false
  }
}
```

### Logging daily activities

1. View available activity types:
```json
{
  "tool": "list_activity_types",
  "arguments": {
    "limit": 20
  }
}
```

2. After completing work for the day, log multiple activities using batch creation:
```json
{
  "tool": "create_activity_batch",
  "arguments": {
    "clock_entry_id": 1,
    "activities": [
      {
        "activity_type_id": 2,
        "task_id": 5,
        "start_offset_seconds": 0,
        "end_offset_seconds": 3600,
        "notes": "Designed homepage mockup"
      },
      {
        "activity_type_id": 3,
        "task_id": 6,
        "start_offset_seconds": 3600,
        "end_offset_seconds": 5400,
        "notes": "Code review for API changes"
      },
      {
        "activity_type_id": 1,
        "start_offset_seconds": 5400,
        "end_offset_seconds": 7200,
        "notes": "Team meeting"
      }
    ]
  }
}
```

### Generating reports

```json
{
  "tool": "generate_report",
  "arguments": {
    "date": "2025-11-02",
    "user_id": 1
  }
}
```

## Testing

Run the MCP server tests:

```bash
php artisan test --filter=McpServerTest
```

## Development

The MCP server implementation consists of:

- **Server**: `app/Mcp/Servers/QadranServer.php`
- **Tools**: `app/Mcp/Tools/`
- **Resources**: `app/Http/Resources/`
- **Routes**: `routes/ai.php`
- **Tests**: `tests/Feature/Mcp/`

## References

- [Laravel MCP Documentation](https://laravel.com/docs/12.x/mcp)
- [Model Context Protocol Specification](https://modelcontextprotocol.io)

# Daily Logs and Activities Architecture

## Overview

The daily logs and activities system allows users to:
1. Clock in/out on projects (tracked in `clock_entries` table)
2. Break down their time into specific activities by task category (tracked in `activities` table)

## Data Flow

### 1. Clock Entries (Time Tracking)
- Users clock in and out using the `ClockEntry` model
- Database view `daily_logs_view` aggregates clock entries by user/project/date
- `DailyLog` view model provides easy access to aggregated data

### 2. Activities (Time Breakdown)
- After clocking time, users can break it down by task categories
- Each `Activity` record represents time spent on a specific task category
- Activities are linked to projects and dates, allowing flexible time allocation

## Database Structure

### Tables

**clock_entries**: Actual clock in/out times
- `id`, `user_id`, `project_id`
- `in`, `out` (timestamps)
- `timezone`, `notes`

**activities**: Time breakdown by task category
- `id`, `user_id`, `project_id`, `task_category_id`
- `date`, `duration` (seconds)
- `notes`

**task_categories**: Categories for activities
- `id`, `name`, `description`

### View

**daily_logs_view**: Aggregated daily summaries
- Groups clock entries by user/project/date
- Calculates `total_seconds` and `total_minutes`

## Models

### DailyLog (View Model)
- Represents the `daily_logs_view` database view
- Methods:
  - `activities()`: Get activities for this log
  - `timeLogs()`: Get clock entries for this log
  - `getDaily($date)`: Get logs for a specific date
  - `getMonthly($date)`: Get logs for a month

### Activity (Model)
- Represents time breakdown by task category
- Relationships:
  - `user()`: Belongs to User
  - `project()`: Belongs to Project
  - `taskCategory()`: Belongs to TaskCategory

### ClockEntry (Model)
- Represents actual clock in/out times
- Relationships:
  - `user()`: Belongs to User
  - `project()`: Belongs to Project
  - `activityLogs()`: Has many ActivityLog

## Frontend Components

### Pages/Activity/Daily/Show.svelte
- Main page for viewing and editing activities for a date
- Displays one form per daily log (project)
- Allows saving all activities at once

### Pages/Activity/Daily/DailyLogInputForm.svelte
- Form for editing activities for a single daily log
- Features:
  - Add/remove activity rows
  - Duration input with validation
  - Safety mode to prevent exceeding total time
  - Edit clock entries modal

### TypeScript Types

```typescript
interface DailyLog {
  id: string;
  user_id: number;
  project_id: number;
  project_name: string;
  date: Date | string;
  total_seconds: number;
  activities: Activity[];
  timeLogs: ClockEntry[];
}

interface Activity {
  id?: number;
  user_id: number;
  project_id: number;
  task_category_id: number | null;
  date: Date | string;
  duration: number;  // in seconds
  notes?: string;
}
```

## API Endpoints

### Activities
- `GET /activities/{date}` - Show activities for a date
- `POST /activities/store` - Save activities

### Clock Entries (Timelog)
- `PUT /timelog/batch-update` - Update multiple clock entries
- `DELETE /timelog/{clockEntry}` - Delete a clock entry

## Key Features

1. **Separation of Concerns**: Clock entries and activities are separate, allowing flexible time tracking
2. **Data Consistency**: Resources ensure consistent data formatting between backend and frontend
3. **Type Safety**: TypeScript interfaces provide type checking in frontend
4. **Validation**: Request classes validate data before storage
5. **Safety Mode**: Prevents users from allocating more time than clocked

## Common Operations

### Viewing Daily Activities
1. User navigates to `/activities/{date}`
2. `ActivityController::show()` fetches `DailyLog::getDaily($date)`
3. For each daily log, `activities()` and `timeLogs()` are loaded
4. Data passed to frontend via Inertia

### Saving Activities
1. User edits activities in frontend form
2. Form submits to `POST /activities/store`
3. `StoreActivityRequest` validates data
4. `ActivityController::store()` deletes old activities and creates new ones
5. User redirected back with success message

### Editing Clock Entries
1. User clicks "Edit clock entries" in daily log form
2. Modal opens with time inputs
3. User modifies `in_time` and `out_time`
4. Form submits to `PUT /timelog/batch-update`
5. `ClockEntryController::batchUpdate()` updates entries

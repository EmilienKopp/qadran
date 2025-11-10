<?php

namespace App\Mcp\Servers;

use App\Mcp\Tools\ClockIn;
use App\Mcp\Tools\ClockOut;
use App\Mcp\Tools\CreateActivity;
use App\Mcp\Tools\CreateActivityBatch;
use App\Mcp\Tools\CreateProject;
use App\Mcp\Tools\CreateTask;
use App\Mcp\Tools\GenerateReport;
use App\Mcp\Tools\GetClockEntries;
use App\Mcp\Tools\ListActivityTypes;
use App\Mcp\Tools\ListProjects;
use App\Mcp\Tools\ListTasks;
use App\Mcp\Tools\RegisterInterference;
use Laravel\Mcp\Server;

class QadranServer extends Server
{
    /**
     * The MCP server's name.
     */
    protected string $name = 'Qadran Server';

    /**
     * The MCP server's version.
     */
    protected string $version = '0.0.1';

    /**
     * The MCP server's instructions for the LLM.
     */
    protected string $instructions = <<<'MARKDOWN'
        This MCP server provides tools for managing projects, tasks, time tracking, and activity logging in the Qadran application.
        
        ## Available Tools:
        
        ### Project Management
        - **create_project**: Create a new project with name, description, type, and status
        - **list_projects**: List all projects, optionally filtered by status or type
        
        ### Task Management
        - **create_task**: Create a new task, optionally associated with a project
        - **list_tasks**: List all tasks, optionally filtered by project, completion status, or priority
        
        ### Time Tracking
        - **clock_in**: Start tracking time for a project or task
        - **clock_out**: Stop tracking time
        - **get_clock_entries**: Retrieve clock entries with optional filtering by date, user, and project
        - **generate_report**: Generate a time tracking report for a specific date
        - **register_interference**: Register a brief interruption during a clock entry (e.g., a few minutes on another project)
        
        ### Activity Logging
        - **create_activity**: Create a single activity log entry for a clock entry
        - **create_activity_batch**: Create multiple activity logs for the same clock entry in one operation
        - **list_activity_types**: List all available activity types
        
        ## Workflow Examples:
        
        1. **Starting work on a project**:
           - First, create or select a project using `create_project` or `list_projects`
           - Clock in using `clock_in` with the project_id
           - Clock out when done using `clock_out`
        
        2. **Managing tasks**:
           - Create tasks associated with projects using `create_task`
           - List tasks for a specific project using `list_tasks` with project_id filter
        
        3. **Recording interferences**:
           - While clocked in on one project, if you briefly work on another, use `register_interference`
           - Specify the start/end times, the project of the interference, and optionally link to the interrupted clock entry
        
        4. **Logging daily activities**:
           - After clocking in and out, use `create_activity_batch` to log multiple activities for that day
           - Each activity can have its own activity type, task, time offsets, and notes
           - Use `list_activity_types` to see available activity categories
        
        5. **Generating reports**:
           - Use `generate_report` to get time tracking summaries for specific dates
           - Reports include total hours, project breakdowns, and entry details
    MARKDOWN;

    /**
     * The tools registered with this MCP server.
     *
     * @var array<int, class-string<\Laravel\Mcp\Server\Tool>>
     */
    protected array $tools = [
        CreateProject::class,
        CreateTask::class,
        ClockIn::class,
        ClockOut::class,
        GetClockEntries::class,
        GenerateReport::class,
        ListTasks::class,
        ListProjects::class,
        CreateActivity::class,
        CreateActivityBatch::class,
        ListActivityTypes::class,
        RegisterInterference::class,
    ];

    /**
     * The resources registered with this MCP server.
     *
     * @var array<int, class-string<\Laravel\Mcp\Server\Resource>>
     */
    protected array $resources = [
        //
    ];

    /**
     * The prompts registered with this MCP server.
     *
     * @var array<int, class-string<\Laravel\Mcp\Server\Prompt>>
     */
    protected array $prompts = [
        //
    ];
}

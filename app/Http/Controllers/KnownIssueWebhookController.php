<?php

namespace App\Http\Controllers;

use App\Models\Landlord\KnownIssue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class KnownIssueWebhookController extends Controller
{
    /**
     * Handle incoming webhook from n8n with Jira issues.
     */
    public function store(Request $request)
    {
        try {
            $issues = $request->all();

            // If single issue, wrap in array
            if (isset($issues['key']) && isset($issues['fields'])) {
                $issues = [$issues];
            }

            \Log::debug('Received Jira webhook', ['issue_count' => count($issues)]);

            $created = 0;
            $updated = 0;

            foreach ($issues as $issueData) {
                $this->processIssue($issueData, $created, $updated);
            }

            return response()->json([
                'success' => true,
                'message' => "Processed {$created} new issues and updated {$updated} existing issues",
                'created' => $created,
                'updated' => $updated,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Failed to process Jira webhook', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to process webhook: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Process a single Jira issue.
     */
    private function processIssue(array $issueData, int &$created, int &$updated): void
    {
        $jiraKey = $issueData['key'] ?? null;

        if (!$jiraKey) {
            Log::warning('Skipping issue without key', ['data' => $issueData]);
            return;
        }

        $fields = $issueData['fields'] ?? [];

        // Extract status information
        $status = $fields['status']['name'] ?? 'Unknown';
        $statusCategory = $fields['status']['statusCategory']['key'] ?? null;
        $statusCategoryName = $fields['status']['statusCategory']['name'] ?? null;
        $statusColor = $fields['status']['statusCategory']['colorName'] ?? null;

        // Extract other fields
        $summary = $fields['summary'] ?? 'No summary';
        $description = $fields['description'] ?? null;
        $priority = $fields['priority']['name'] ?? null;
        $priorityIconUrl = $fields['priority']['iconUrl'] ?? null;
        $issueType = $fields['issuetype']['name'] ?? null;
        $issueTypeIconUrl = $fields['issuetype']['iconUrl'] ?? null;

        // Parse dates
        $firstReportedAt = isset($fields['created'])
            ? Carbon::parse($fields['created'])
            : now();

        $lastUpdatedAt = isset($fields['updated'])
            ? Carbon::parse($fields['updated'])
            : now();

        // Find or create the issue
        $issue = KnownIssue::where('jira_key', $jiraKey)->first();

        if ($issue) {
            // Update existing issue
            $statusChanged = $issue->hasStatusChanged($status);

            $issue->update([
                'jira_id' => $issueData['id'] ?? $issue->jira_id,
                'summary' => $summary,
                'description' => $description,
                'priority' => $priority,
                'priority_icon_url' => $priorityIconUrl,
                'issue_type' => $issueType,
                'issue_type_icon_url' => $issueTypeIconUrl,
                'last_updated_at' => $lastUpdatedAt,
                'metadata' => $issueData,
            ]);

            // Update status if changed
            if ($statusChanged) {
                $issue->updateStatus($status, $statusCategory, $statusCategoryName);
                $issue->update(['status_color' => $statusColor]);
            }

            $updated++;
        } else {
            // Create new issue
            $issue = KnownIssue::create([
                'jira_id' => $issueData['id'] ?? null,
                'jira_key' => $jiraKey,
                'summary' => $summary,
                'description' => $description,
                'status' => $status,
                'status_category' => $statusCategory,
                'status_category_name' => $statusCategoryName,
                'status_color' => $statusColor,
                'priority' => $priority,
                'priority_icon_url' => $priorityIconUrl,
                'issue_type' => $issueType,
                'issue_type_icon_url' => $issueTypeIconUrl,
                'first_reported_at' => $firstReportedAt,
                'last_updated_at' => $lastUpdatedAt,
                'current_status_since' => $firstReportedAt,
                'metadata' => $issueData,
            ]);

            $created++;
        }

        Log::info('Processed Jira issue', [
            'key' => $jiraKey,
            'action' => $issue->wasRecentlyCreated ? 'created' : 'updated',
        ]);
    }
}

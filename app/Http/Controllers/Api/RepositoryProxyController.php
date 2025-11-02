<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class RepositoryProxyController extends Controller
{
    /**
     * Proxy method calls to local repository implementations
     */
    public function call(Request $request)
    {
        $repository = $request->input('repository'); // 'UserRepository'
        $method = $request->input('method');
        $args = $request->input('args', []);

        // Map repository names to local implementations
        $repositoryMap = [
            'UserRepository' => \App\Repositories\Local\LocalUserRepository::class,
            'OrganizationRepository' => \App\Repositories\Local\LocalOrganizationRepository::class,
            'ProjectRepository' => \App\Repositories\Local\LocalProjectRepository::class,
            'ReportRepository' => \App\Repositories\Local\LocalReportRepository::class,
            'TenantRepository' => \App\Repositories\Local\LocalTenantRepository::class,
            'ClockEntryRepository' => \App\Repositories\Local\LocalClockEntryRepository::class,
            'TaskRepository' => \App\Repositories\Local\LocalTaskRepository::class,
        ];

        if (!isset($repositoryMap[$repository])) {
            \Log::warning('Repository not allowed', ['repository' => $repository]);
            return response()->json(['error' => 'Repository not allowed'], 403);
        }

        $repoClass = $repositoryMap[$repository];
        $repoInstance = app($repoClass);

        if (!method_exists($repoInstance, $method)) {
            \Log::warning('Method not found', [
                'repository' => $repository,
                'method' => $method
            ]);
            return response()->json(['error' => 'Method not found'], 404);
        }

        try {
            \Log::info('Repository proxy call', [
                'repository' => $repository,
                'method' => $method,
                'args' => $args
            ]);

            $result = $repoInstance->$method(...$args);

            // Serialize result
            if ($result instanceof \Illuminate\Database\Eloquent\Model) {
                return response()->json($result->toArray());
            }

            if ($result instanceof Collection) {
                return response()->json($result->toArray());
            }

            if (is_array($result)) {
                return response()->json($result);
            }

            if (is_null($result)) {
                return response()->json(null);
            }

            if (is_bool($result)) {
                return response()->json(['result' => $result]);
            }

            return response()->json($result);

        } catch (\Exception $e) {
            \Log::error('Repository call failed', [
                'repository' => $repository,
                'method' => $method,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Internal error',
                'message' => app()->environment('local') ? $e->getMessage() : 'An error occurred'
            ], 500);
        }
    }
}

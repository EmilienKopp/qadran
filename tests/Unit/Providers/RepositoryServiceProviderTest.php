<?php

namespace Tests\Unit\Providers;

use App\Providers\RepositoryServiceProvider;
use App\Repositories\ClockEntryRepositoryInterface;
use App\Repositories\Local\LocalClockEntryRepository;
use App\Repositories\Local\LocalOrganizationRepository;
use App\Repositories\Local\LocalProjectRepository;
use App\Repositories\Local\LocalReportRepository;
use App\Repositories\Local\LocalTaskRepository;
use App\Repositories\Local\LocalTenantRepository;
use App\Repositories\Local\LocalUserRepository;
use App\Repositories\OrganizationRepositoryInterface;
use App\Repositories\ProjectRepositoryInterface;
use App\Repositories\Remote\RemoteClockEntryRepository;
use App\Repositories\Remote\RemoteOrganizationRepository;
use App\Repositories\Remote\RemoteProjectRepository;
use App\Repositories\Remote\RemoteReportRepository;
use App\Repositories\Remote\RemoteTaskRepository;
use App\Repositories\Remote\RemoteTenantRepository;
use App\Repositories\Remote\RemoteUserRepository;
use App\Repositories\ReportRepositoryInterface;
use App\Repositories\TaskRepositoryInterface;
use App\Repositories\TenantRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use App\Support\RequestContextResolver;
use Illuminate\Foundation\Testing\TestCase;
use Tests\TestCase as BaseTestCase;

class RepositoryServiceProviderTest extends BaseTestCase
{
    /**
     * Test that all repository interfaces are bound in web (non-desktop) context
     */
    public function test_repositories_are_bound_for_web_context(): void
    {
        // In web context (default), repositories should resolve to Local implementations
        $this->assertInstanceOf(LocalUserRepository::class, app(UserRepositoryInterface::class));
        $this->assertInstanceOf(LocalOrganizationRepository::class, app(OrganizationRepositoryInterface::class));
        $this->assertInstanceOf(LocalProjectRepository::class, app(ProjectRepositoryInterface::class));
        $this->assertInstanceOf(LocalReportRepository::class, app(ReportRepositoryInterface::class));
        $this->assertInstanceOf(LocalTenantRepository::class, app(TenantRepositoryInterface::class));
        $this->assertInstanceOf(LocalClockEntryRepository::class, app(ClockEntryRepositoryInterface::class));
        $this->assertInstanceOf(LocalTaskRepository::class, app(TaskRepositoryInterface::class));
    }

    /**
     * Test that repository bindings are properly registered
     */
    public function test_repository_bindings_exist(): void
    {
        // All interfaces should be bound in the container
        $this->assertTrue(app()->bound(UserRepositoryInterface::class));
        $this->assertTrue(app()->bound(OrganizationRepositoryInterface::class));
        $this->assertTrue(app()->bound(ProjectRepositoryInterface::class));
        $this->assertTrue(app()->bound(ReportRepositoryInterface::class));
        $this->assertTrue(app()->bound(TenantRepositoryInterface::class));
        $this->assertTrue(app()->bound(ClockEntryRepositoryInterface::class));
        $this->assertTrue(app()->bound(TaskRepositoryInterface::class));
    }
}

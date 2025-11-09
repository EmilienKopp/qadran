<?php

use App\Http\Middleware\{ForceWebContext, TenantAwareMcp};
use Laravel\Mcp\Facades\Mcp;



Route::prefix('mcp')->name('mcp.')->group(function () {
    // Local MCP server for development/testing (no authentication)
    Mcp::local('qadran_local', \App\Mcp\Servers\QadranServer::class);
    
    // Tenant-aware web MCP server with authentication
    \Log::debug('Route ACCOUNT parameter', ['account' => request()->route('account')]);
    Mcp::web('qadran', \App\Mcp\Servers\QadranServer::class)
        ->middleware([
            ForceWebContext::class,
            TenantAwareMcp::class,
        ])
        ->name('qadran');
});

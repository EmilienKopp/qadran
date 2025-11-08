<?php

use Laravel\Mcp\Facades\Mcp;

// Local MCP server for development/testing (no authentication)
Mcp::local('qadran_local', \App\Mcp\Servers\QadranServer::class);

// Tenant-aware web MCP server with authentication
Mcp::web('qadran', \App\Mcp\Servers\QadranServer::class)
    ->middleware([
        \App\Http\Middleware\TenantAwareMcp::class,
        'throttle:mcp', // Optional: rate limiting for MCP requests
    ]);

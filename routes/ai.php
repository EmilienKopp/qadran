<?php

use Laravel\Mcp\Facades\Mcp;

Mcp::local('qadran_local', \App\Mcp\Servers\QadranServer::class);

Mcp::web('qadran', \App\Mcp\Servers\QadranServer::class)
    ->middleware(\App\Http\Middleware\ForceWebContext::class);

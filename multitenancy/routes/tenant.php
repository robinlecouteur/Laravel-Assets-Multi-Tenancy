<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Stancl\Tenancy\Middleware\CheckTenantForMaintenanceMode;
use Stancl\Tenancy\Features\UserImpersonation;
use \Spatie\Multitenancy\Http\Middleware;
/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/


// This sets up the web routes for the tenants

Route::group([
    'middleware' => ['tenant',   
    PreventAccessFromCentralDomains::class,
    CheckTenantForMaintenanceMode::class,
    NeedsTenant::class,
    EnsureValidTenantSession::class,
], // See the middleware group in Http Kernel
    'as' => 'tenant.',
], function () {
    Route::get('/impersonate/{token}', function ($token) {
        return UserImpersonation::makeResponse($token); // TODO: Make a controller for this
        // Of course use a controller in a production app and not a Closure route.
        // Closure routes cannot be cached.
    })->name('impersonate');

    Route::get('/', function () {
        return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
    });
});
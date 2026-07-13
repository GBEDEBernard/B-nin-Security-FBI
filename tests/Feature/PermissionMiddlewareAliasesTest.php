<?php

namespace Tests\Feature;

use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Tests\TestCase;

class PermissionMiddlewareAliasesTest extends TestCase
{
    public function test_spatie_permission_middleware_aliases_are_registered(): void
    {
        $router = $this->app['router'];

        $this->assertSame(PermissionMiddleware::class, $router->getMiddleware()['permission']);
        $this->assertSame(RoleMiddleware::class, $router->getMiddleware()['role']);
        $this->assertSame(RoleOrPermissionMiddleware::class, $router->getMiddleware()['role_or_permission']);
    }
}

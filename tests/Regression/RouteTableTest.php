<?php

namespace Tests\Regression;

use App\RouteTable;
use PHPUnit\Framework\TestCase;

/**
 * Locks in the routing table extracted from app/router.php so the
 * auth0/auth0-php upgrade can't silently change which controller/action
 * handles a given request.
 */
class RouteTableTest extends TestCase
{
    /**
     * @dataProvider routes
     */
    public function testResolvesExpectedControllerAndAction(
        string $uri,
        string $method,
        string $expectedController,
        string $expectedAction
    ) {
        $route = RouteTable::resolve($uri, $method);

        $this->assertSame($expectedController, $route['controller']);
        $this->assertSame($expectedAction, $route['action']);
    }

    public function routes(): array
    {
        return [
            'healthcheck' => ['/healthcheck/', 'GET', 'App\\controllers\\HealthCheck', 'get'],
            'callback no trailing slash' => ['/auth/callback', 'GET', 'App\\controllers\\Callback', 'get'],
            'callback trailing slash' => ['/auth/callback/', 'POST', 'App\\controllers\\Callback', 'post'],
            'logout no trailing slash' => ['/auth/logout', 'GET', 'App\\controllers\\Logout', 'get'],
            'logout trailing slash' => ['/auth/logout/', 'GET', 'App\\controllers\\Logout', 'get'],
            'auth root' => ['/auth/', 'GET', 'App\\controllers\\Login', 'get'],
            'root' => ['/', 'GET', 'App\\controllers\\Login', 'get'],
            'unknown path falls back to login' => ['/something/unexpected', 'GET', 'App\\controllers\\Login', 'get'],
            'query string is stripped' => ['/auth/callback?code=abc&state=xyz', 'GET', 'App\\controllers\\Callback', 'get'],
            'method is case-insensitive' => ['/healthcheck/', 'PoSt', 'App\\controllers\\HealthCheck', 'post'],
            'path is case-insensitive' => ['/AUTH/CALLBACK', 'GET', 'App\\controllers\\Callback', 'get'],
        ];
    }
}

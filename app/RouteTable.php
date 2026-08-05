<?php

namespace App;

/**
 * Pure route-resolution logic, extracted from app/router.php so it can be
 * unit tested independently of the Auth0 SDK / controller side effects.
 */
class RouteTable
{
    const ROUTES = [
        '/healthcheck/'   => 'App\\controllers\\HealthCheck',
        '/auth/callback'  => 'App\\controllers\\Callback',
        '/auth/callback/' => 'App\\controllers\\Callback',
        '/auth/logout'    => 'App\\controllers\\Logout',
        '/auth/logout/'   => 'App\\controllers\\Logout',
        '/auth/'          => 'App\\controllers\\Login',
        '/'               => 'App\\controllers\\Login',
    ];

    const DEFAULT_CONTROLLER = 'App\\controllers\\Login';

    /**
     * Resolve a request URI + HTTP method into a controller class and action.
     * Any unmatched path falls back to the Login controller, matching the
     * previous switch statement's `default` case.
     *
     * @return array{controller: string, action: string}
     */
    public static function resolve(string $requestUri, string $requestMethod): array
    {
        $path = strtolower($requestUri);

        if (strpos($path, '?') !== false) {
            $path = substr($path, 0, strpos($path, '?'));
        }

        return [
            'controller' => self::ROUTES[$path] ?? self::DEFAULT_CONTROLLER,
            'action'     => strtolower($requestMethod),
        ];
    }
}

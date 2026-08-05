<?php

namespace App\services;

use Firebase\JWT\JWT;

/**
 * Builds the `jtl` cookie/query-param JWT handed off to downstream apps
 * after a successful Auth0 login. Extracted from Callback::createToken()
 * so its output shape can be locked in with a regression test independent
 * of the Auth0 SDK version.
 */
class TokenFactory
{
    /**
     * @param array $userInfo Auth0 user info array (must contain 'email')
     * @param string $returnUrl The URL the user is being redirected back to
     * @param string $key Signing key (APP_KEY)
     * @return string JWT Token
     */
    public static function create(array $userInfo, string $returnUrl, string $key): string
    {
        $domain = parse_url($returnUrl, PHP_URL_HOST);
        $email = $userInfo['email'];
        $time = new \DateTime();
        $now = $time->getTimestamp();

        $token = [
            'iss' => $domain,
            'iat' => $now,
            'exp' => $now + 86400,
            'uid' => $email,
        ];

        return JWT::encode($token, $key, 'HS256');
    }
}

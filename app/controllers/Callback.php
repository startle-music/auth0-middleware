<?php
namespace App\controllers;

use Auth0\SDK\Auth0;
use \Firebase\JWT\JWT;


class Callback {

    private $auth0;

    function __construct()
    {
        
        $this->auth0 = new Auth0([
            'domain' => getenv('AUTH_DOMAIN'),
            'client_id' => getenv('AUTH_ID'),
            'client_secret' => getenv('AUTH_SECRET'),
            'redirect_uri' => getenv('AUTH_REDIRECT'),
            'audience' => getenv('AUTH_AUDIENCE'),
            'scope' => getenv('AUTH_SCOPE'),
            'persist_id_token' => getenv('AUTH_PERSIST_ID'),
            'persist_access_token' => getenv('AUTH_PERSIST_ACCESS'),
            'persist_refresh_token' => getenv('AUTH_REFRESH'),
        ]);
    }

    /**
     * get method handler
     *
     * @return redirect
     */
    public function get() 

        $userInfo = $this->auth0->getUser();

        if (!$userInfo) {
            header('Location: /auth/error/');

        } else {
            // User is authenticated
            $token = $this->createToken($userInfo);

            setcookie( getenv('COOKIE_NAME'), $token, time() + 60*60*24*30, '/');
            header('Location: '. getenv('AUTH_ENDPOINT'));
        }

        exit();
    }

    /**
     * Creates JWT token from user into array
     *
     * @param array $userInfo
     * @return string JWT Token
     */
    private function createToken(array $userInfo)
    {        
        $key = getenv('APP_KEY');
        $token = [
            'iss' => getenv('APP_DOMAIN'),
            //'aud' => getenv('APP_DOMAIN'),
            'iat' => now(),
            'exp' => now() + 86400,
            'uid' => $userInfo['email']
        ];

        $jwt = JWT::encode($token, $key);

        return $jwt;
    }
}
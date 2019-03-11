<?php
namespace Application\Controllers;

use Auth0\SDK\Auth0;


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

    public function get() 
    {
        $userInfo = $this->auth0->getUser();

        if (!$userInfo) {
            header('Location: /');

        } else {
            // User is authenticated
            setcookie( getenv('COOKIE_NAME'), 'test', time() + 60*60*24*30, '/');
            header('Location: '. getenv('AUTH_ENDPOINT'));
        }

        exit();
    }
}
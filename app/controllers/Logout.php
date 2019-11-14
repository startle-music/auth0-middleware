<?php
namespace App\controllers;

use Auth0\SDK\Auth0;


class Logout
{

    private $auth0;

    function __construct()
    {

        $this->auth0 = new Auth0([
            'domain' => getenv('AUTH_DOMAIN'),
            'client_id' => getenv('AUTH_ID'),
            'client_secret' => getenv('AUTH_SECRET'),
            'redirect_uri' => getenv('AUTH_REDIRECT')
        ]);
    }


    /**
     * get method handler
     *
     * @return redirect
     */
    public function get()
    {
        $auth0 = new Auth0([
            'domain' => getenv('AUTH_DOMAIN'),
            'client_id' => getenv('AUTH_ID'),
            'client_secret' => getenv('AUTH_SECRET'),
            'redirect_uri' => getenv('AUTH_REDIRECT'),
            'return_to' => getenv('AUTH_RETURN_TO')
        ]);

        unset($_SESSION["return_to"]);
        setcookie(getenv('COOKIE_NAME'), '', time() - 3600, '/');
        $auth0->logout();

        $logout_url = sprintf('http://%s/v2/logout?client_id=%s&returnTo=%s', getenv('AUTH_DOMAIN'), getenv('AUTH_ID'), getenv('AUTH_RETURN_TO'));
        header('Location: ' . $logout_url);

        //$this->auth0->login();
        exit();
    }
}

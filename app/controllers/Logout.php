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
            'clientId' => getenv('AUTH_ID'),
            'clientSecret' => getenv('AUTH_SECRET'),
            'redirectUri' => getenv('AUTH_REDIRECT'),
            'cookieSecret' => getenv('COOKIE_SECRET'),
        ]);
    }


    /**
     * get method handler
     *
     * @return redirect
     */
    public function get()
    {
        unset($_SESSION["return_to"]);
        setcookie(getenv('COOKIE_NAME'), '', time() - 3600, '/');

        $logout_url = $this->auth0->logout(getenv('AUTH_RETURN_TO'));
        header('Location: ' . $logout_url);

        exit();
    }
}

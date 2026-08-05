<?php

namespace App\controllers;

use Auth0\SDK\Auth0;


class Login
{

    private $auth0;

    function __construct()
    {

        errorLog(getenv('AUTH_DOMAIN'));

        $this->auth0 = new Auth0([
            'domain' => getenv('AUTH_DOMAIN'),
            'clientId' => getenv('AUTH_ID'),
            'clientSecret' => getenv('AUTH_SECRET'),
            'redirectUri' => getenv('AUTH_REDIRECT'),
            'audience' => [getenv('AUTH_AUDIENCE')],
            'scope' => explode(' ', getenv('AUTH_SCOPE')),
            'cookieSecret' => getenv('COOKIE_SECRET'),
            'persistIdToken' => filter_var(getenv('AUTH_PERSIST_ID'), FILTER_VALIDATE_BOOLEAN),
            'persistAccessToken' => filter_var(getenv('AUTH_PERSIST_ACCESS'), FILTER_VALIDATE_BOOLEAN),
            'persistRefreshToken' => filter_var(getenv('AUTH_REFRESH'), FILTER_VALIDATE_BOOLEAN),
        ]);
    }


    /**
     * get method handler
     *
     * @return redirect
     */
    public function get()
    {
        header('Location: ' . $this->auth0->login());
        exit();
    }

    public function middleware()
    {
        // get the origin url to return to
        if (!isset($_GET['return_to'])) {
            return false;
        }
        $return = $_GET['return_to'];

        // parse the domain
        // check versus allowed domains
        $allowed = explode(',', getenv('AUTH_ENDPOINTS'));

        if (!in_array(parse_url($return, PHP_URL_HOST), $allowed)) {
            print(parse_url($return, PHP_URL_HOST));
            return false;
        }

        // store incoming domain to session
        $_SESSION["return_to"] = $return;

        // continue
        return true;
    }
}

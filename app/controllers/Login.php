<?php
namespace App\controllers;

use Auth0\SDK\Auth0;


class Login
{

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
    {
        $this->auth0->login();
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

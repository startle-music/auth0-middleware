<?php
namespace App\controllers;

use Auth0\SDK\Auth0;
use \Firebase\JWT\JWT;

class Callback
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
    public function post()
    {
        return $this->get();
    }

    /**
     * get method handler
     *
     * @return redirect
     */
    public function get()
    {
        if (!empty($_GET['error']) || !empty($_GET['error_description'])) {
            // Handle errors sent back by Auth0.
        }

        // If there is a user persisted (PHP session by default), return that.
        // Otherwise, look for a "code" and "state" URL parameter to validate and exchange, respectively.
        // If the state validation and code exchange are successful, return the userinfo.
        $userinfo = $this->auth0->getUser();

        // We have no persisted user and no "code" parameter so we redirect to the Universal Login Page.
        if (empty($userinfo) || empty($_SESSION["return_to"])) {
            $this->auth0->login();
            exit();
        } else {
            // User is authenticated
            $return_to = $_SESSION['return_to'];
            $token = $this->createToken($userinfo, $return_to);
            $return_to = $_SESSION['return_to'];

            $addition = '?jtl=' . $token;
            if (strpos($return_to, '?') !== false) {
                $addition = '&jtl=' . $token;
            }

            setcookie(getenv('COOKIE_NAME'), $token, time() + 60 * 60 * 24 * 30, '/');
            header('Location: ' . $return_to . $addition);
        }

        exit();
    }

    /**
     * Creates JWT token from user into array
     *
     * @param array $userInfo
     * @return string JWT Token
     */
    private function createToken(array $userInfo, string $return_url)
    {
        $key = getenv('APP_KEY');
        $domain = parse_url($return_url, PHP_URL_HOST);
        $email = $userInfo['email'];
        $time = new \DateTime();
        $now = $time->getTimestamp();
        $token = [
            'iss' => $domain,
            //'aud' => getenv('APP_DOMAIN'),
            'iat' => $now,
            'exp' => $now + 86400,
            'uid' => $email
        ];

        $jwt = JWT::encode($token, $key);

        return $jwt;
    }
}

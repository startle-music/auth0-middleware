<?php
namespace App\controllers;

use Auth0\SDK\Auth0;
use App\services\TokenFactory;

class Callback
{

    private $auth0;

    function __construct()
    {
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
        $credentials = $this->auth0->getCredentials();

        // Otherwise, look for a "code" and "state" URL parameter to validate and exchange.
        // If the state validation and code exchange are successful, we'll have credentials.
        if (empty($credentials) && !empty($_GET['code']) && !empty($_GET['state'])) {
            try {
                $this->auth0->exchange();
                $credentials = $this->auth0->getCredentials();
            } catch (\Throwable $e) {
                $credentials = null;
            }
        }

        // We have no persisted user and no valid "code"/"state" so we redirect to the Universal Login Page.
        if (empty($credentials) || empty($_SESSION["return_to"])) {
            header('Location: ' . $this->auth0->login());
            exit();
        } else {
            // User is authenticated
            $return_to = $_SESSION['return_to'];
            $token = $this->createToken($credentials->user, $return_to);
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
        return TokenFactory::create($userInfo, $return_url, getenv('APP_KEY'));
    }
}

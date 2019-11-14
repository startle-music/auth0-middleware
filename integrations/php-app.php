<?php
/**
 *  Integration with third party PHP scripts
 * 
 *  This can be included in other php scripts in order to 
 *  check for and parse a token within a cookie
 * 
 * 
 * 
 * 
 */

/**
 *  Autoload all classes installed by composer
 * 
 */
require_once('../vendor/firebase/php-jwt/src/JWT.php');

use \Firebase\JWT\JWT;

class Auth
{

    private $cookie = 'stauth';
    private $key = 'dSErfa324fSDEs';
    private $hash = ['HS256'];
    private $token = NULL;

    /**
     * Check if the cookie with name `$cookie` exists
     *
     * @return bool
     */
    public function cookieExists()
    {
        return isset($_COOKIE[$this->cookie]);
    }

    /**
     * gets the email from the parsed token
     *
     * @return string Email address
     */
    public function getTokenEmail()
    {
        $token = $this->parseCookie();

        return $token['uid'];
    }

    /**
     * checks if the cookie is valid
     *
     * @return bool
     */
    public function cookieIsValid()
    {
        $token = $this->parseCookie();

        if ($token === NULL) {
            return false;
        }
        return true;
    }

    /**
     * Parses the cookie decoding the jwt.
     *
     * @return string jwt token
     */
    public function parseCookie()
    {
        if (!$this->token) {
            try {
                $token = JWT::decode($this->key, $this->hash);

                if (isset($token['uid'])) {
                    $this->token = $token;
                } else {
                    $this->token = NULL;
                }
            } catch (Exception $e) {
                $this->token = NULL;
            }
        }
        return $this->token;
    }
}

/**
 * This implements the class, defining the AUTHED_EMAIL 
 * constant. 
 */
$_email = null;
$auth = new Auth();
// check for token
if ($auth->cookieExists()) {

    if ($auth->cookieIsValid()) {
        // decode token
        $_email = $this->getUserEmail();
    }
}

define('AUTHED_EMAIL', $_email);


/**
 *  In order to use this you can just do the following logic.
 *  Check if AUTHED_EMAIL isn't NULL. Then check within
 *  your application for permissions.
 */
if (AUTHED_EMAIL !== NULL) {
    // Do your stuff
}

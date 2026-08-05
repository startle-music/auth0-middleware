<?php

namespace Tests\Regression;

use App\controllers\Login;
use PHPUnit\Framework\TestCase;

/**
 * Locks in Login::middleware()'s return_to allowlist behaviour, which is
 * pure logic sitting next to the Auth0 SDK calls and should not change
 * across the SDK upgrade. Relies on AUTH_ENDPOINTS from .env (loaded by
 * tests/bootstrap.php) containing "auth-test.byant.co.uk".
 */
class LoginMiddlewareTest extends TestCase
{
    protected function setUp(): void
    {
        $_GET = [];
        $_SESSION = [];
    }

    public function testReturnsFalseWhenReturnToIsMissing()
    {
        $login = new Login();

        $this->assertFalse($login->middleware());
        $this->assertArrayNotHasKey('return_to', $_SESSION);
    }

    public function testReturnsFalseAndDoesNotSetSessionWhenHostIsNotAllowed()
    {
        $_GET['return_to'] = 'https://not-an-allowed-host.example.com/path';

        $login = new Login();

        ob_start();
        $result = $login->middleware();
        ob_end_clean();

        $this->assertFalse($result);
        $this->assertArrayNotHasKey('return_to', $_SESSION);
    }

    public function testReturnsTrueAndPersistsReturnToWhenHostIsAllowed()
    {
        $_GET['return_to'] = 'https://auth-test.byant.co.uk/some/path?x=1';

        $login = new Login();

        $this->assertTrue($login->middleware());
        $this->assertSame('https://auth-test.byant.co.uk/some/path?x=1', $_SESSION['return_to']);
    }
}

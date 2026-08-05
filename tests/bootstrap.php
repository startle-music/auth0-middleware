<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../bootstrap/errors.php';

use Symfony\Component\Dotenv\Dotenv;

// Load the same .env the app uses in dev, so controller constructors that
// require real-looking Auth0 config (domain/client_id/client_secret/etc.)
// don't blow up when instantiated in tests.
try {
    (new Dotenv())->load(__DIR__ . '/../.env');
} catch (\Exception $e) {
    // No .env available in this environment (e.g. CI) - tests that depend
    // on it will fail with a clear error rather than a silent skip.
}

// The Auth0 SDK's SessionStore lazily calls session_set_cookie_params() /
// session_start() the first time a controller touches the session, guarded
// by `if (!session_id())`. Under plain CLI SAPI (as opposed to cli-server
// or a real webserver), headers_sent() always reports true, so those calls
// emit PHP warnings that PHPUnit turns into test failures. Pre-seeding a
// session id satisfies the SDK's guard so it never takes that code path.
if (!session_id()) {
    session_id('phpunit-test-session');
}

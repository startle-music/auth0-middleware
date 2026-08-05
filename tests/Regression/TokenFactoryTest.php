<?php

namespace Tests\Regression;

use App\services\TokenFactory;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use PHPUnit\Framework\TestCase;

/**
 * Locks in the shape of the `jtl` token handed off to downstream apps after
 * a successful login, extracted from Callback::createToken(). Downstream
 * consumers (integrations/php-app.php, and third-party sites) decode this
 * token, so its claims must not change shape across the SDK upgrade.
 */
class TokenFactoryTest extends TestCase
{
    private const KEY = 'test-signing-key';

    public function testTokenContainsExpectedClaims()
    {
        $before = time();

        $jwt = TokenFactory::create(
            ['email' => 'person@example.com'],
            'https://return.example.com/path?x=1',
            self::KEY
        );

        $decoded = (array) JWT::decode($jwt, new Key(self::KEY, 'HS256'));

        $this->assertSame('return.example.com', $decoded['iss']);
        $this->assertSame('person@example.com', $decoded['uid']);
        $this->assertGreaterThanOrEqual($before, $decoded['iat']);
        $this->assertSame($decoded['iat'] + 86400, $decoded['exp']);
        $this->assertArrayNotHasKey('aud', $decoded);
    }

    public function testTokenIsVerifiableWithTheSigningKey()
    {
        $jwt = TokenFactory::create(
            ['email' => 'person@example.com'],
            'https://return.example.com/',
            self::KEY
        );

        // Decoding with the wrong key must fail signature verification.
        $this->expectException(\Firebase\JWT\SignatureInvalidException::class);
        JWT::decode($jwt, new Key('wrong-key', 'HS256'));
    }
}

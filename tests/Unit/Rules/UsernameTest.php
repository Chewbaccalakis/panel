<?php

namespace App\Tests\Unit\Rules;

use App\Rules\Username;
use App\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class UsernameTest extends TestCase
{
    /**
     * Test that this rule can be cast to a string correctly.
     */
    public function testRuleIsStringable(): void
    {
        $this->assertSame('p_username', (string) new Username());
    }

    /**
     * Test valid usernames.
     */
    #[DataProvider('validUsernameDataProvider')]
    public function testValidUsernames(string $username): void
    {
        $this->assertTrue((new Username())->passes('test', $username), 'Assert username is valid.');
    }

    /**
     * Test invalid usernames return false.
     */
    #[DataProvider('invalidUsernameDataProvider')]
    public function testInvalidUsernames(string $username): void
    {
        $this->assertFalse((new Username())->passes('test', $username), 'Assert username is not valid.');
    }

    /**
     * Provide valid usernames.
     */
    public static function validUsernameDataProvider(): array
    {
        return [
            ['username'],
            ['user_name'],
            ['user.name'],
            ['user-name'],
            ['123username123'],
            ['123-user.name'],
            ['123456'],
        ];
    }

    /**
     * Provide invalid usernames.
     */
    public static function invalidUsernameDataProvider(): array
    {
        return [
            ['_username'],
            ['username_'],
            ['_username_'],
            ['-username'],
            ['.username'],
            ['username-'],
            ['username.'],
            ['user*name'],
            ['user^name'],
            ['user#name'],
            ['user+name'],
            ['1234_'],
        ];
    }
}

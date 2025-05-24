<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\User;

class UserModelTest extends TestCase
{
    public function test_fillable_attributes_are_correct()
    {
        $user = new User();

        $this->assertEquals(
            ['name', 'email', 'password'],
            $user->getFillable()
        );
    }

    public function test_hidden_attributes_are_correct()
    {
        $user = new User();

        $this->assertEquals(
            ['password', 'remember_token'],
            $user->getHidden()
        );
    }

    public function test_casts_are_correct()
    {
        $user = new User();

        $casts = $user->getCasts();

        $this->assertArrayHasKey('email_verified_at', $casts);
        $this->assertEquals('datetime', $casts['email_verified_at']);
        $this->assertArrayHasKey('password', $casts);
        $this->assertEquals('hashed', $casts['password']);
    }
}

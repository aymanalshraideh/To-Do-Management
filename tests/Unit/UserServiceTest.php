<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Services\UserService;
use App\Repositories\Eloquent\UserRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;

class UserServiceTest extends TestCase
{
    public function test_create_user_returns_user()
    {
        $data = ['name' => 'Test', 'email' => 'test@example.com'];

        $mockUser = Mockery::mock(User::class);
        $repo = Mockery::mock(UserRepository::class);

        $repo->shouldReceive('create')
            ->once()
            ->with($data)
            ->andReturn($mockUser);

        $service = new UserService($repo);

        $this->assertSame($mockUser, $service->createUser($data));
    }

    public function test_update_user_returns_true()
    {
        $user = Mockery::mock(User::class);
        $data = ['name' => 'Updated'];

        $repo = Mockery::mock(UserRepository::class);
        $repo->shouldReceive('update')
            ->once()
            ->with($user, $data)
            ->andReturn(true);

        $service = new UserService($repo);

        $this->assertTrue($service->updateUser($user, $data));
    }

    public function test_delete_user_returns_true()
    {
        $user = Mockery::mock(User::class);

        $repo = Mockery::mock(UserRepository::class);
        $repo->shouldReceive('delete')
            ->once()
            ->with($user)
            ->andReturn(true);

        $service = new UserService($repo);

        $this->assertTrue($service->deleteUser($user));
    }

    public function test_with_relations_returns_paginated_users()
    {
        $relations = ['roles', 'permissions'];
        $perPage = 10;

        $paginatedUsers = Mockery::mock(LengthAwarePaginator::class);

        $repo = Mockery::mock(UserRepository::class);
        $repo->shouldReceive('with')
            ->once()
            ->with($relations, $perPage)
            ->andReturn($paginatedUsers);

        $service = new UserService($repo);

        $this->assertSame($paginatedUsers, $service->with($relations, $perPage));
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}

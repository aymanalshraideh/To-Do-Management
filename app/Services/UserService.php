<?php

namespace App\Services;


use App\Models\User;
use App\Repositories\Eloquent\UserRepository;

class UserService
{
    protected UserRepository $repo;

    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;
    }

    public function createUser(array $data): User
    {
        return $this->repo->create($data);
    }

    public function updateUser(User $user, array $data): bool
    {
        return $this->repo->update($user, $data);
    }

    public function deleteUser(User $user): bool
    {
        return $this->repo->delete($user);
    }
    public function with(array $relations, int $perPage = 10)
    {
        return $this->repo->with($relations, $perPage);
    }
}

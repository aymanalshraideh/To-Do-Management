<?php


namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function allPaginated(int $perPage = 10)
    {
        return User::paginate($perPage);
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(User $user, array $data): bool
    {
        return $user->update($data);
    }

    public function delete(User $user): bool
    {
        return $user->delete();
    }
    public function with(array $relations, int $perPage = 10)
    {
        return User::with($relations)->paginate($perPage);
    }
}

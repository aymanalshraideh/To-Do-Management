<?php

namespace Modules\TaskApi\App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Task;

interface TaskRepositoryInterface
{
    public function paginateFiltered(array $filters, int $perPage = 15): LengthAwarePaginator;
    public function create(array $data): Task;
    public function update(Task $task, array $data): bool;
    public function delete(Task $task): bool;
    public function findById(int $id): ?Task;
}

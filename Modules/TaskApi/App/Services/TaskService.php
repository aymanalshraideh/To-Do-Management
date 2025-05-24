<?php

namespace Modules\TaskApi\App\Services;


use App\Models\Task;
use Modules\TaskApi\App\Repositories\Contracts\TaskRepositoryInterface;

class TaskService
{
    protected TaskRepositoryInterface $repo;

    public function __construct(TaskRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function listTasks(array $filters, int $perPage = 15)
    {
        return $this->repo->paginateFiltered($filters, $perPage);
    }

    public function createTask(array $data): Task
    {
        return $this->repo->create($data);
    }

    public function updateTask(Task $task, array $data): bool
    {
        return $this->repo->update($task, $data);
    }

    public function deleteTask(Task $task): bool
    {
        return $this->repo->delete($task);
    }
}

<?php

namespace App\Services;

use App\Models\Task;
use App\Repositories\Eloquent\TaskRepository;

class TaskService
{
    protected TaskRepository $repo;

    public function __construct(TaskRepository $repo)
    {
        $this->repo = $repo;
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

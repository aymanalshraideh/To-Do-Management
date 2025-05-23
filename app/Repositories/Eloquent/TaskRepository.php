<?php

namespace App\Repositories\Eloquent;

use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;

class TaskRepository implements TaskRepositoryInterface
{
    public function getAll()
    {
        return Task::with('user')->latest()->get();
    }

    public function create(array $data) :Task
    {
        return Task::create($data);
    }

    public function update(Task $task, array $data) :bool
    {
        return $task->update($data);
    }

    public function delete(Task $task):bool
    {
        return $task->delete();
    }
}

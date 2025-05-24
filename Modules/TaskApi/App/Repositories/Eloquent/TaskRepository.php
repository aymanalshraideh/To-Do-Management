<?php

namespace Modules\TaskApi\App\Repositories\Eloquent;

use App\Models\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\TaskApi\App\Repositories\Contracts\TaskRepositoryInterface;

class TaskRepository implements TaskRepositoryInterface
{
    public function paginateFiltered(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = Task::query();
        if (!empty($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('title', 'like', "%{$filters['search']}%")
                  ->orWhere('description', 'like', "%{$filters['search']}%");
            });
        }

        if (!empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['due_date'])) {
            $query->whereDate('due_date', $filters['due_date']);
        }

        if (!empty($filters['sort_by']) && in_array($filters['sort_by'], ['priority', 'status', 'due_date'])) {
            $sortDir = $filters['sort_dir'] ?? 'asc';
            $query->orderBy($filters['sort_by'], $sortDir);
        } else {
            $query->latest();
        }

        return $query->paginate($perPage);
    }

    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function update(Task $task, array $data): bool
    {
        return $task->update($data);
    }

    public function delete(Task $task): bool
    {
        return $task->delete();
    }

    public function findById(int $id): ?Task
    {
        return Task::find($id);
    }
}

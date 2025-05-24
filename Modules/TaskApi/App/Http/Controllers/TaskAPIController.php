<?php

namespace Modules\TaskApi\App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Modules\TaskApi\App\Services\TaskService;
use Modules\TaskApi\App\Resources\TaskResource;
use Modules\TaskApi\App\Http\Requests\StoreTaskRequest;
use Modules\TaskApi\App\Http\Requests\UpdateTaskRequest;
use Modules\TaskApi\App\Traits\ApiResponseTrait;

class TaskAPIController extends Controller
{
    use ApiResponseTrait;

    protected TaskService $service;

    public function __construct(TaskService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $filters = request()->only(['search', 'priority', 'status', 'due_date', 'sort_by', 'sort_dir']);
        $perPage = request()->get('per_page', 15);

        $tasks = $this->service->listTasks($filters, $perPage);

        // استدعاء resource مع successResponse
        return $this->successResponse(
            TaskResource::collection($tasks)->response()->getData(true),
            'Tasks fetched successfully.'
        );
    }

    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = $this->service->createTask($request->validated());

        return $this->successResponse(
            new TaskResource($task),
            'Task created successfully.',
            201
        );
    }

    public function show(Task $task): JsonResponse
    {
        return $this->successResponse(new TaskResource($task), 'Task retrieved successfully.');
    }

    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        $this->service->updateTask($task, $request->validated());

        return $this->successResponse(null, 'Task updated successfully.');
    }

    public function destroy(Task $task): JsonResponse
    {
        $this->service->deleteTask($task);

        return $this->successResponse(null, 'Task deleted successfully.');
    }
}

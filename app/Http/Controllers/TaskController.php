<?php

namespace App\Http\Controllers;



use App\Models\Task;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Services\TaskService;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\TaskResource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;

class TaskController extends Controller
{
    public function __construct(protected TaskService $service) {}

    public function index(): Response
    {
        $cachedTasks = Cache::remember('tasks', now()->addMinutes(10), function () {
            return Task::with('user')->latest()->get();
        });
        $tasks = TaskResource::collection($cachedTasks);

        $users = User::select('id', 'name')->get();

        return Inertia::render('Dashboard/Tasks/Index', [
            'tasks' => $tasks,
            'users' => $users
        ]);
    }

    public function create(): Response
    {
        //
    }

    public function store(StoreTaskRequest $request): RedirectResponse
    {
 
        $task = $this->service->createTask([...$request->validated()]);
        Cache::forget('tasks');
        return redirect()->route('admin.tasks.index')->with('success', 'Task created');
    }

    public function edit(Task $task): Response
    {
        //
    }

    public function show(Task $task): Response
    {
        //
    }

    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        $this->authorize('update', $task);

        $this->service->updateTask($task, $request->validated());
        Cache::forget('tasks');

        return redirect()->route('admin.tasks.index')->with('success', 'Task updated');
    }

    public function destroy(Task $task): RedirectResponse
    {
        $this->authorize('delete', $task);

        $this->service->deleteTask($task);
        Cache::forget('tasks');

        return redirect()->route('admin.tasks.index')->with('success', 'Task deleted');
    }
    public function updateStatus(Request $request, Task $task): JsonResponse
    {

        $this->authorize('update', $task);
        $validated = $request->validate([
            'status' => ['required', Rule::in(['todo', 'in_progress', 'qa', 'done'])],
        ]);
        $task->update([
            'status' => $validated['status'],
        ]);
        Cache::forget('tasks');

        return response()->json(['message' => 'Status updated successfully']);
    }
    public function bulkLock(Request $request)
    {
        $request->validate([
            'is_locked' => 'required',
            'task_ids' => 'required|array',
        ]);

        Task::whereIn('id', $request->task_ids)->update([
            'is_locked' => $request->is_locked,
        ]);

        Cache::forget('tasks');
        if ($request->is_locked == 1) {
            return response()->json(['message' => 'Tasks locked successfully.']);
        } else {
            return response()->json(['message' => 'Tasks unlocked successfully.']);
        }
    }
}

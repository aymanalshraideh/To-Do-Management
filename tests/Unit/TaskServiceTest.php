<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Task;
use App\Services\TaskService;
use App\Repositories\Eloquent\TaskRepository;
use Mockery;

class TaskServiceTest extends TestCase
{
    public function test_create_task_calls_repository_and_returns_task()
    {
        $taskData = ['title' => 'Test Task'];

        $mockTask = Mockery::mock(Task::class);

        $repo = Mockery::mock(TaskRepository::class);
        $repo->shouldReceive('create')
            ->once()
            ->with($taskData)
            ->andReturn($mockTask);

        $service = new TaskService($repo);

        $result = $service->createTask($taskData);

        $this->assertSame($mockTask, $result);
    }

    public function test_update_task_calls_repository_and_returns_true()
    {
        $task = Mockery::mock(Task::class);
        $data = ['title' => 'Updated Title'];

        $repo = Mockery::mock(TaskRepository::class);
        $repo->shouldReceive('update')
            ->once()
            ->with($task, $data)
            ->andReturn(true);

        $service = new TaskService($repo);

        $this->assertTrue($service->updateTask($task, $data));
    }

    public function test_delete_task_calls_repository_and_returns_true()
    {
        $task = Mockery::mock(Task::class);

        $repo = Mockery::mock(TaskRepository::class);
        $repo->shouldReceive('delete')
            ->once()
            ->with($task)
            ->andReturn(true);

        $service = new TaskService($repo);

        $this->assertTrue($service->deleteTask($task));
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}

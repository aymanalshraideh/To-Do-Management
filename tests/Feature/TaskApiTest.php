<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // إنشاء مستخدم مصادق عليه للاختبارات
        $this->user = User::factory()->create();

        Sanctum::actingAs($this->user, [], 'web');
    }

    public function test_index_returns_tasks()
    {
        // تحضير بيانات مهمة
        Task::factory()->count(3)->create(['user_id' => $this->user->id]);

        $response = $this->getJson('/api/v1/tasks');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'data',    // مجموعة المهام (pagination items)
                    'links',
                    'meta',
                ],
            ])
            ->assertJson([
                'status' => 'success',
                'message' => 'Tasks fetched successfully.'
            ]);
    }

    public function test_store_creates_task()
    {
        $payload = [
            'title' => 'Test Task',
            'description' => 'Description for the test task',
            'priority' => 'high',
            'status' => 'todo',
            'due_date' => now()->addDays(5)->toDateString(),
            'due_time' => now()->format('H:i:s'),
            'is_locked' => false,
            'user_id' => $this->user->id,
        ];

        $response = $this->postJson('/api/v1/tasks', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'success',
                'message' => 'Task created successfully.',
            ])
            ->assertJsonPath('data.attributes.title', 'Test Task');

        $this->assertDatabaseHas('tasks', ['title' => 'Test Task']);
    }

    public function test_show_returns_task()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $response = $this->getJson("/api/v1/tasks/{$task->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Task retrieved successfully.',
                'data' => [
                    'type' => 'tasks',
                    'id' => (string) $task->id,
                    'attributes' => [
                        'title' => $task->title,
                    ],
                ],
            ]);
    }

    public function test_update_modifies_task()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $payload = [
            'title' => 'Updated Title',
            'description' => 'Updated description',
            'priority' => 'medium',
            'status' => 'in_progress',
            'due_date' => now()->addDays(10)->toDateString(),
            'due_time' => now()->format('H:i:s'),
            'is_locked' => false,
            'user_id' => $this->user->id,
        ];

        $response = $this->putJson("/api/v1/tasks/{$task->id}", $payload);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Task updated successfully.'
            ]);

        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'title' => 'Updated Title']);
    }

    public function test_destroy_deletes_task()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $response = $this->deleteJson("/api/v1/tasks/{$task->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Task deleted successfully.'
            ]);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}

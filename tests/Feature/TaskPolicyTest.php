<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use App\Policies\TaskPolicy;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_delete_own_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $policy = new TaskPolicy();

        $this->assertTrue($policy->delete($user, $task));
    }

    public function test_user_cannot_delete_others_task()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user2->id]);

        $policy = new TaskPolicy();

        $this->assertFalse($policy->delete($user1, $task));
    }



public function test_admin_can_delete_any_task()
{
    $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']); 

    $admin = User::factory()->create();
    $admin->assignRole($adminRole); 

    $task = Task::factory()->create();

    $policy = new TaskPolicy();

    $this->assertTrue($policy->delete($admin, $task));
}
}

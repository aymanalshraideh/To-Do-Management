<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Carbon\Carbon; // ✅ أضف هذا

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->name(),
            'description' => fake()->text(),
            'priority' => 'low',
            'status' => 'todo',
            'due_date' => Carbon::now()->addDays(3),
            'due_time' => Carbon::now()->format('H:i:s'), 
            'is_locked' => false,
            'user_id' => User::factory(),
        ];
    }
}

<?php

namespace App\Observers;

use App\Models\Task;
use App\Events\PrivateUserAlert;
use App\Events\TaskCreated;

class TaskObserver
{
    /**
     * Handle the Task "created" event.
     */
    public function created(Task $task): void
    {
        event(new PrivateUserAlert('New Task', 'You have a new assigned task.', $task->user_id));
    }

    /**
     * Handle the Task "updated" event.
     */
    public function updated(Task $task): void
    {
        
        if ($task->wasChanged() && $task->getChanges() === ['status' => $task->status]) {

            event(new PrivateUserAlert('Task Status Updated', "The task status is now '{$task->status}'", $task->user_id));
        } else {
            event(new PrivateUserAlert('Update Task', 'You have a new update task.', $task->user_id));
        }
        
    }

    /**
     * Handle the Task "deleted" event.
     */
    public function deleted(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "restored" event.
     */
    public function restored(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "force deleted" event.
     */
    public function forceDeleted(Task $task): void
    {
        //
    }
}

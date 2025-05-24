<?php

namespace Modules\TaskApi\App\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'type' => 'tasks',
            'id' => (string) $this->id,
            'attributes' => [
                'title' => $this->title,
                'description' => $this->description,
                'priority' => $this->priority,
                'status' => $this->status,
                'due_date' => $this->due_date,
                'due_time' => $this->due_time,
                'is_locked' => $this->is_locked,
                'user_id' => $this->user_id,
            ],

        ];
    }
}

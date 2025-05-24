<?php

namespace Modules\TaskApi\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'sometimes|required|in:low,medium,high',
            'status' => 'sometimes|required|in:todo,in_progress,done',
            'due_date' => 'sometimes|required|date',
            'due_time' => 'nullable|date_format:H:i:s',
            'is_locked' => 'boolean',
            'user_id' => 'sometimes|required|exists:users,id',
        ];
    }
}

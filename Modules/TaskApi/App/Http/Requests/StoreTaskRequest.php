<?php

namespace Modules\TaskApi\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:todo,in_progress,done',
            'due_date' => 'nullable|date',
            'due_time' => 'nullable|date_format:H:i:s',
            'is_locked' => 'boolean',
            'user_id' => 'required|exists:users,id',
        ];
    }
}

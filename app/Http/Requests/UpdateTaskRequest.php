<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority'=>'nullable',
            'status' => 'required|in:todo,in_progress,qa,done',
            'due_date' => 'nullable',
            'due_time' => 'nullable',
            'is_locked' => 'nullable',
            'user_id' => 'required|exists:users,id',
        ];
    }
}

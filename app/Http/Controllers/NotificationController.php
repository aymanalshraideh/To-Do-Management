<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\PrivateUserAlert;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    public function send(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'user_ids' => 'nullable|array|min:1',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|string|in:private,public'
        ]);

        if ($validated->fails()) {
            return back()->withErrors($validated)->withInput();
        }

        $data = $validated->validated();
        if ($data['type'] == 'private') {
            foreach ($data['user_ids'] as $userId) {
                event(new PrivateUserAlert($data['title'], $data['message'], $userId));
            }
        }else{
            event(new TaskCreated($data['title'], $data['message']));
        }
    }
}

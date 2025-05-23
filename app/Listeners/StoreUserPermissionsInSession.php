<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Session;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Contracts\Queue\ShouldQueue;

class StoreUserPermissionsInSession
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
public function handle(Authenticated $event): void
{
    $user = $event->user;

    Session::put('user_roles', $user->getRoleNames());
    Session::put('user_permissions', $user->permissions->pluck('name'));
}

}

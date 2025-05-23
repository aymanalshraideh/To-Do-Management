<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;


class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);
        Permission::create(['name' => 'create user']);
        Permission::create(['name' => 'edit user']);
        Permission::create(['name' => 'remove user']);
        Permission::create(['name' => 'create task']);
        Permission::create(['name' => 'edit task']);
        Permission::create(['name' => 'edit task status']);
        Permission::create(['name' => 'lock task']);
        Permission::create(['name' => 'send notification']);
        $allPermissionNames = Permission::pluck('name')->toArray();
        $adminRole->givePermissionTo($allPermissionNames);
        
        $AdminUser = User::firstOrCreate([
            'email' => 'aymanshraideh96@gmail.com',
        ], [
            'name' => 'Super Admin',
            'email' => 'aymanshraideh96@gmail.com',
            'password' => Hash::make('12345678'),
        ]);

        $AdminUser->assignRole($adminRole);
        $AdminUser->syncPermissions($allPermissionNames);
    }
}

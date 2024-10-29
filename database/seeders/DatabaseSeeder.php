<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        $admin = \App\Models\User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('12345'),
        ]);

        $adminRole = Role::create(['name' => 'admin']);
        $providerRole = Role::create(['name' => 'provider']);
        $customerRole = Role::create(['name' => 'customer']);

        $permissions = Permission::all();
        $adminRole->givePermissionTo($permissions);
        $admin->assignRole($adminRole);

        $providerRole->givePermissionTo([
            Permission::create(['name' => 'create services']),
            Permission::create(['name' => 'update services']),
            Permission::create(['name' => 'delete services']),
        ]);

        $customerRole->givePermissionTo([
            Permission::create(['name' => 'create biddings']),
        ]);
    }
}


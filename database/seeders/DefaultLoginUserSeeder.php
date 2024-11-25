<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Crypt;

class DefaultLoginUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Super Admin Seeder ##
        $superAdminRole = Role::updateOrCreate(['name'=> 'Super Admin']);
        $permissions = Permission::pluck('id','id')->all();
        $superAdminRole->syncPermissions($permissions);

        $user = User::updateOrCreate([
            'email' => Crypt::encryptString('superadmin@gmail.com')
        ],[
            'name' => Crypt::encryptString('Super Admin'),
            'email' => Crypt::encryptString('superadmin@gmail.com'),
            'mobile' => Crypt::encryptString('9999999991'),
            'password' => Hash::make('12345678'),
        ]);
        $user->assignRole([$superAdminRole->id]);



        // Admin Seeder ##
        $adminRole = Role::updateOrCreate(['name'=> 'Organizer']);
        $permissions = Permission::whereIn('id', [1, 13, 14, 15, 16])->pluck('id', 'id')->all();
        $adminRole->syncPermissions($permissions);

        $user = User::updateOrCreate([
            'email' => Crypt::encryptString('organizer@gmail.com')
        ],[
            'name' => Crypt::encryptString('Organizer'),
            'email' => Crypt::encryptString('organizer@gmail.com'),
            'mobile' => Crypt::encryptString('9999999992'),
            'password' => Hash::make('12345678')
        ]);
        $user->assignRole([$adminRole->id]);

        // Admin Seeder ##
        $adminRole = Role::updateOrCreate(['name'=> 'Attendee']);
        $permissions = Permission::whereIn('id', [1])->pluck('id', 'id')->all();
        $adminRole->syncPermissions($permissions);

        $user = User::updateOrCreate([
            'email' => Crypt::encryptString('attendee@gmail.com')
        ],[
            'name' => Crypt::encryptString('Attendee'),
            'email' => Crypt::encryptString('attendee@gmail.com'),
            'mobile' => Crypt::encryptString('9999999993'),
            'password' => Hash::make('12345678')
        ]);
        $user->assignRole([$adminRole->id]);

    }
}

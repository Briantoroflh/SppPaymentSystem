<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get roles
        $superAdminRole = Role::where('name', 'Super Admin')->first();
        $schoolAdminRole = Role::where('name', 'School Admin')->first();
        $studentRole = Role::where('name', 'Student')->first();

        // Create Super Admin user
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'),
            ]
        );
        if ($superAdminRole) {
            $superAdmin->assignRole($superAdminRole);
        }

        // Create School Admin user
        $schoolAdmin = User::firstOrCreate(
            ['email' => 'schooladmin@example.com'],
            [
                'name' => 'School Admin',
                'password' => Hash::make('password123'),
            ]
        );
        if ($schoolAdminRole) {
            $schoolAdmin->assignRole($schoolAdminRole);
        }

        // Create Student user
        $student = User::firstOrCreate(
            ['email' => 'student@example.com'],
            [
                'name' => 'Student User',
                'password' => Hash::make('password123'),
            ]
        );
        if ($studentRole) {
            $student->assignRole($studentRole);
        }
    }
}

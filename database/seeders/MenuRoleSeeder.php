<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class MenuRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get roles
        $superAdmin = Role::where('name', 'Super Admin')->first();
        $schoolAdmin = Role::where('name', 'School Admin')->first();
        $student = Role::where('name', 'Student')->first();

        // If roles don't exist, create them
        if (!$superAdmin) {
            $superAdmin = Role::create(['name' => 'Super Admin', 'guard_name' => 'web']);
        }
        if (!$schoolAdmin) {
            $schoolAdmin = Role::create(['name' => 'School Admin', 'guard_name' => 'web']);
        }
        if (!$student) {
            $student = Role::create(['name' => 'Student', 'guard_name' => 'student']);
        }

        // Get all menus
        $allMenus = Menu::all();

        // Assign all menus to Super Admin
        foreach ($allMenus as $menu) {
            if (!$menu->roles->contains($superAdmin)) {
                $menu->roles()->attach($superAdmin);
            }
        }

        // Assign specific menus to School Admin
        $schoolAdminMenus = Menu::whereIn('title', [
            'Dashboard',
            'Major',
            'Student',
            'Teacher',
        ])->get();

        foreach ($schoolAdminMenus as $menu) {
            if (!$menu->roles->contains($schoolAdmin)) {
                $menu->roles()->attach($schoolAdmin);
            }
        }

        // Assign specific menus to Student
        $studentMenus = Menu::whereIn('title', [
            'Bill Spp'
        ])->get();

        foreach ($studentMenus as $menu) {
            if (!$menu->roles->contains($student)) {
                $menu->roles()->attach($student);
            }
        }
    }
}

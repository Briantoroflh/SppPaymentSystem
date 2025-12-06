<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            // Common Section
            [
                'sequence' => 1,
                'head_title' => 'Common',
                'title' => 'Dashboard',
                'icon' => 'ri-dashboard-3-line',
                'url' => '/dashboard',
                'created_by' => 'Admin'
            ],
            [
                'sequence' => 2,
                'head_title' => 'Common',
                'title' => 'School',
                'icon' => 'ri-school-line',
                'url' => '/dashboard/manage-school',
                'created_by' => 'Admin'
            ],
            [
                'sequence' => 3,
                'head_title' => 'Common',
                'title' => 'Region',
                'icon' => 'ri-map-pin-line',
                'url' => '/dashboard/manage-region',
                'created_by' => 'Admin'
            ],
            [
                'sequence' => 4,
                'head_title' => 'Common',
                'title' => 'Major',
                'icon' => 'ri-book-line',
                'url' => '/dashboard/manage-major',
                'created_by' => 'Admin'
            ],
            [
                'sequence' => 5,
                'head_title' => 'Common',
                'title' => 'Student',
                'icon' => 'ri-user-2-line',
                'url' => '/dashboard/manage-student',
                'created_by' => 'Admin'
            ],
            [
                'sequence' => 6,
                'head_title' => 'Common',
                'title' => 'Teacher',
                'icon' => 'ri-team-line',
                'url' => '/dashboard/manage-teacher',
                'created_by' => 'Admin'
            ],
            [
                'sequence' => 7,
                'head_title' => 'Common',
                'title' => 'User',
                'icon' => 'ri-user-line',
                'url' => '/dashboard/manage-users',
                'created_by' => 'Admin'
            ],
            [
                'sequence' => 1,
                'head_title' => 'Accessbility',
                'title' => 'Role',
                'icon' => 'ri-shield-line',
                'url' => '/dashboard/manage-role',
                'created_by' => 'Admin'
            ],
            [
                'sequence' => 1,
                'head_title' => 'Finance',
                'title' => 'Spp Student',
                'icon' => 'ri-bill-line',
                'url' => '/dashboard/manage-spp',
                'created_by' => 'Admin'
            ],
            [
                'sequence' => 9,
                'head_title' => 'Common',
                'title' => 'Classes',
                'icon' => 'ri-building-3-line',
                'url' => '/dashboard/manage-classes',
                'created_by' => 'Admin'
            ],
            [
                'sequence' => 1,
                'head_title' => 'Group',
                'title' => 'Student Classes',
                'icon' => 'ri-home-smile-line',
                'url' => '/dashboard/manage-student-classes',
                'created_by' => 'Admin'
            ],
            [
                'sequence' => 2,
                'head_title' => 'Finance',
                'title' => 'Bill Spp',
                'icon' => 'ri-receipt-line',
                'url' => '/dashboard/student/spp',
                'created_by' => 'Admin'
            ],
        ];

        Menu::insert($menus);
    }
}

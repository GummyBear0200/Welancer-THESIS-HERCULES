<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'CEO/Administrator', 'description' => 'Full system access'],
            ['name' => 'HR', 'description' => 'Human Resources Management'],
            ['name' => 'Team Leader/Manager', 'description' => 'Manages team and projects'],
            ['name' => 'Team Member/Employee', 'description' => 'Regular employee'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
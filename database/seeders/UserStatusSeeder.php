<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserStatus;

class UserStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ['name' => 'Active'],
            ['name' => 'Inactive'],
        ];

        foreach ($statuses as $status) {
            UserStatus::create($status);
        }
    }
}
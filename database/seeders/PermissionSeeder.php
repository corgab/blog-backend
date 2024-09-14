<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;


class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Posts
        Permission::create(['name' => 'approve posts']);
        Permission::create(['name' => 'publish posts']);
        Permission::create(['name' => 'edit posts']);
        // Tags
        Permission::create(['name' => 'manage tags']);

    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Trova i permessi
        $approvePosts = Permission::findByName('approve posts');
        $publishPosts = Permission::findByName('publish posts');
        $editPosts = Permission::findByName('edit posts');

        // Trova i ruoli
        $adminRole = Role::findByName('admin');
        $editorRole = Role::findByName('editor');
        $authorRole = Role::findByName('author');

        // Assegna permessi ai ruoli
        $adminRole->givePermissionTo([$approvePosts, $publishPosts, $editPosts]);
        $editorRole->givePermissionTo([$approvePosts, $editPosts]);
        $authorRole->givePermissionTo($editPosts);
    }
}

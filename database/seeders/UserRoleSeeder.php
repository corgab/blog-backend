<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserRoleSeeder extends Seeder
{
    public function run()
    {
        // Recupera i ruoli disponibili
        $roles = Role::pluck('name')->toArray();

        // Recupera tutti gli utenti, ordinati per ID
        $users = User::orderBy('id')->get();

        // Assegna il ruolo di admin al primo utente
        $firstUser = $users->first();
        $firstUser->assignRole('admin');

        // Rimuovi il primo utente dalla lista
        $remainingUsers = $users->slice(1);

        // Assegna ruoli casuali agli altri utenti
        foreach ($remainingUsers as $user) {
            $randomRole = $roles[array_rand($roles)];
            $user->assignRole($randomRole);
        }

    }
}

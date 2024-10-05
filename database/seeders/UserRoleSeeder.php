<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserRoleSeeder extends Seeder
{
    public function run()
    {
        // Definisci i ruoli
        $roles = ['admin', 'editor', 'author'];

        // Assicurati che i ruoli esistano
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Recupera tutti gli utenti, ordinati per ID
        $users = User::orderBy('id')->get();

        // Assegna il ruolo di admin al primo utente
        $firstUser = $users->first();
        $firstUser->assignRole('admin');

        // Rimuovi il primo utente dalla lista
        $remainingUsers = $users->slice(1);

        // Assegna ruoli casuali agli altri utenti
        foreach ($remainingUsers as $user) {
            // Scegli un ruolo casuale
            $randomRole = $roles[array_rand($roles)];
            $user->assignRole($randomRole);
        }
    }
}

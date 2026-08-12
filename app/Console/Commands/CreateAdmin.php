<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdmin extends Command
{
    protected $signature   = 'admin:create {--promote= : Email d\'un utilisateur existant à promouvoir}';
    protected $description = 'Créer un compte administrateur ou promouvoir un utilisateur existant';

    public function handle()
    {
        // Mode promotion d'un utilisateur existant
        if ($email = $this->option('promote')) {
            $user = User::where('email', $email)->first();

            if (!$user) {
                $this->error("Aucun utilisateur trouvé avec l'email : $email");
                return 1;
            }

            $ancienRole = $user->role;
            $user->update(['role' => 'admin']);
            $this->info("✅ {$user->name} ({$email}) promu admin. (ancien rôle : $ancienRole)");
            return 0;
        }

        // Mode création d'un nouvel admin
        $this->info('=== Création d\'un compte Administrateur ===');

        $name = $this->ask('Nom complet');
        $email = $this->ask('Email');

        if (User::where('email', $email)->exists()) {
            $this->error("Un utilisateur avec cet email existe déjà.");
            $this->line("Pour le promouvoir admin : php artisan admin:create --promote=$email");
            return 1;
        }

        $password = $this->secret('Mot de passe');
        $confirm  = $this->secret('Confirmer le mot de passe');

        if ($password !== $confirm) {
            $this->error('Les mots de passe ne correspondent pas.');
            return 1;
        }

        $telephone = $this->ask('Téléphone (optionnel)', '');
        $ville     = $this->ask('Ville (optionnel)', 'Yaoundé');

        $user = User::create([
            'name'      => $name,
            'email'     => $email,
            'password'  => Hash::make($password),
            'telephone' => $telephone,
            'role'      => 'admin',
            'ville'     => $ville,
        ]);

        $this->info("✅ Administrateur créé avec succès !");
        $this->table(['Champ', 'Valeur'], [
            ['Nom',   $user->name],
            ['Email', $user->email],
            ['Rôle',  $user->role],
            ['Ville', $user->ville],
        ]);

        return 0;
    }
}


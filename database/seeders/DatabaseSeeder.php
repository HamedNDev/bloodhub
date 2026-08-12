<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // ===== ADMIN (créé directement, jamais via inscription) =====
        \App\Models\User::firstOrCreate(
            ['email' => 'admin@vitaflow.cm'],
            [
                'name'      => 'Administrateur VitaFlow',
                'password'  => bcrypt('Admin@2024!'),
                'telephone' => '+237691000000',
                'role'      => 'admin',
                'ville'     => 'Yaoundé',
            ]
        );

        // Donneurs
        $donneurs = \App\Models\User::factory(5)->create([
            'role' => 'donneur',
            'groupe_sanguin' => fake()->randomElement(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']),
        ]);

        // Hôpitaux
        $hopitaux_users = \App\Models\User::factory(3)->create([
            'role' => 'hopital',
        ]);

        // Banques de sang
        $banques_users = \App\Models\User::factory(2)->create([
            'role' => 'banque_de_sang',
        ]);

        // Patients
        $patients = \App\Models\User::factory(5)->create([
            'role' => 'patient',
            'groupe_sanguin' => fake()->randomElement(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']),
        ]);

        // Créer des profils donneurs
        foreach ($donneurs as $donneur_user) {
            \App\Models\Donneur::create([
                'user_id' => $donneur_user->id,
                'date_naissance' => fake()->dateTimeBetween('-60 years', '-18 years'),
                'sexe' => fake()->randomElement(['M', 'F']),
                'disponible' => true,
                'dernier_don' => fake()->dateTimeBetween('-12 months', 'now'),
                'nombre_dons' => fake()->numberBetween(0, 10),
                'notifications_actives' => true,
            ]);
        }

        // Créer des hôpitaux
        foreach ($hopitaux_users as $hopital_user) {
            \App\Models\Hopital::create([
                'user_id' => $hopital_user->id,
                'nom' => fake()->company() . ' Hospital',
                'adresse' => fake()->streetAddress(),
                'ville' => fake()->randomElement(['Yaoundé', 'Douala', 'Buea', 'Bafoussam']),
                'region' => fake()->randomElement(['Centre', 'Littoral', 'Sud-Ouest', 'Ouest']),
                'telephone' => fake()->phoneNumber(),
                'latitude' => fake()->latitude(),
                'longitude' => fake()->longitude(),
                'actif' => true,
            ]);
        }

        // Créer des banques de sang
        $banques = [];
        foreach ($banques_users as $banque_user) {
            $banques[] = \App\Models\BanqueDeSang::create([
                'user_id' => $banque_user->id,
                'nom' => 'Banque de Sang ' . fake()->city(),
                'adresse' => fake()->streetAddress(),
                'ville' => fake()->randomElement(['Yaoundé', 'Douala', 'Buea', 'Bafoussam']),
                'region' => fake()->randomElement(['Centre', 'Littoral', 'Sud-Ouest', 'Ouest']),
                'telephone' => fake()->phoneNumber(),
                'latitude' => fake()->latitude(),
                'longitude' => fake()->longitude(),
                'actif' => true,
            ]);
        }

        // Créer des stocks pour chaque banque
        $groupes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        foreach ($banques as $banque) {
            foreach ($groupes as $groupe) {
                \App\Models\Stock::create([
                    'banque_de_sang_id' => $banque->id,
                    'groupe_sanguin' => $groupe,
                    'quantite' => fake()->numberBetween(5, 50),
                    'seuil_alerte' => 10,
                    'derniere_mise_a_jour' => now(),
                ]);
            }
        }
    }
}


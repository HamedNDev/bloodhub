<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bons_paiement', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demande_id')->constrained()->onDelete('cascade');
            $table->foreignId('acheteur_id')->constrained('users')->onDelete('cascade'); // celui qui offre
            $table->foreignId('beneficiaire_id')->constrained('users')->onDelete('cascade'); // le patient
            $table->decimal('montant', 10, 2);
            $table->string('code')->unique(); // code unique du bon
            $table->string('message')->nullable(); // message du donateur
            $table->string('methode')->default('orange_money');
            $table->string('statut')->default('actif'); // actif, utilise, expire
            $table->timestamp('expire_le')->nullable();
            $table->timestamp('utilise_le')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bons_paiement');
    }
};


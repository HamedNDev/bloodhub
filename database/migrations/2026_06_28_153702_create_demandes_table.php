<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('demandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('hopital_id')->nullable()->constrained('hopitaux')->onDelete('set null');
            $table->foreignId('banque_de_sang_id')->nullable()->constrained('banques_de_sang')->onDelete('set null');
            $table->enum('groupe_sanguin', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']);
            $table->integer('quantite')->default(1);
            $table->enum('urgence', ['normale', 'urgente', 'critique'])->default('normale');
            $table->enum('statut', ['en_attente', 'en_cours', 'satisfaite', 'annulee'])->default('en_attente');
            $table->text('description')->nullable();
            $table->timestamp('date_besoin')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('demandes');
    }
};


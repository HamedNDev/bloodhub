<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cagnottes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demande_id')->constrained()->onDelete('cascade');
            $table->foreignId('createur_id')->constrained('users')->onDelete('cascade');
            $table->string('titre');
            $table->text('description')->nullable();
            $table->decimal('objectif', 10, 2); // montant cible
            $table->decimal('montant_collecte', 10, 2)->default(0);
            $table->string('slug')->unique(); // lien public unique
            $table->string('statut')->default('active'); // active, atteinte, fermee
            $table->timestamp('expire_le')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cagnottes');
    }
};


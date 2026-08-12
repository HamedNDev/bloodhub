<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('contributions_cagnotte', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cagnotte_id')->constrained('cagnottes')->onDelete('cascade');
            $table->foreignId('contributeur_id')->nullable()->constrained('users')->onDelete('set null'); // null si anonyme
            $table->string('nom_contributeur')->nullable(); // pour les anonymes
            $table->decimal('montant', 10, 2);
            $table->string('methode')->default('orange_money');
            $table->string('message')->nullable();
            $table->boolean('anonyme')->default(false);
            $table->string('statut')->default('confirme'); // confirme, en_attente, echoue
            $table->string('reference')->unique()->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contributions_cagnotte');
    }
};


<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demande_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('montant', 10, 2);
            $table->enum('methode', ['orange_money', 'mtn_momo', 'autre'])->default('orange_money');
            $table->enum('statut', ['en_attente', 'confirme', 'echoue', 'rembourse'])->default('en_attente');
            $table->string('reference')->unique()->nullable();
            $table->string('numero_telephone', 20)->nullable();
            $table->timestamp('date_paiement')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('paiements');
    }
};


<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('banque_de_sang_id')->constrained('banques_de_sang')->onDelete('cascade');
            $table->enum('groupe_sanguin', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']);
            $table->integer('quantite')->default(0);
            $table->integer('seuil_alerte')->default(5);
            $table->timestamp('derniere_mise_a_jour')->nullable();
            $table->timestamps();

            $table->unique(['banque_de_sang_id', 'groupe_sanguin']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('stocks');
    }
};


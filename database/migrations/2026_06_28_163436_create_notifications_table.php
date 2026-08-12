<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('titre');
            $table->text('message');
            $table->enum('type', ['alerte_don', 'demande_satisfaite', 'stock_critique', 'paiement', 'info'])->default('info');
            $table->foreignId('demande_id')->nullable()->constrained()->onDelete('set null');
            $table->boolean('lue')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};


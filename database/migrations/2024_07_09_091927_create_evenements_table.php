<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('evenements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cat_id')->constrained('categories_evenements')->onDelete('restrict');
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->string('nom', 254);
            $table->date('dateDebut');
            $table->time('heure');
            $table->integer('place')->nullable();
            $table->integer('placeRestant')->nullable();
            $table->string('lieu', 254);
            $table->string('description', 254);
            $table->string('image');
            $table->string('status');
            $table->string('motif');
            $table->string('type');
            $table->date('datePublication')->nullable();
            $table->date('dateFin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evenements');
    }
};

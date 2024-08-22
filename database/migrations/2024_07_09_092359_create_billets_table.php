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
        Schema::create('billets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eve_id')->constrained('evenements')->onDelete('restrict');
            $table->foreignId('typ_id')->constrained('types_billets')->onDelete('restrict');
            $table->integer('nombre')->nullable();
            $table->decimal('prix', 8, 0);
            $table->integer('quota')->nullable();
            $table->integer('rest')->nullable();
            $table->string('status', 254);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billets');
    }
};

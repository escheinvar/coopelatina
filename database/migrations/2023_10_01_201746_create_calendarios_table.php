<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('calendarios', function (Blueprint $table) {
            $table->id();
            $table->integer('act')->default('1');
            $table->integer('anio');
            $table->integer('mes');
            $table->string('event');
            $table->string('titulo')->nullable();
            
            $table->datetime('start');
            $table->datetime('end');           

            $table->string('responsable')->nullable();
            $table->string('opciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendarios');
    }
};

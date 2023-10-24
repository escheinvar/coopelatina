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
        Schema::create('caja', function (Blueprint $table) {
            $table->id('caja_id');
            $table->enum('caja_act',['0','1'])->default('1');
            $table->decimal('caja_caja',10,2)->default('0');
            $table->decimal('caja_teso',10,2)->default('0');
            $table->decimal('caja_banco',10,2)->default('0');
            $table->integer('caja_responsable')->nullable();
            $table->integer('caja_usrid')->nullable();
            $table->string('caja_tipo')->nullable();
            $table->string('caja_descripcion')->nullable();
            $table->string('caja_observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caja');
    }
};

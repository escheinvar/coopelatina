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
        Schema::create('trabajos', function (Blueprint $table) {
            $table->id('work_id');
            $table->integer('work_usrid');
            $table->enum('work_act',['0','1'])->default('1');
            $table->string('work_usr');
            $table->integer('work_mes');
            $table->integer('work_anio');
            $table->date('work_anualidadvence');
            $table->date('work_fechatrabajo');
            $table->string('work_descripcion');
            $table->integer('work_responsableid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trabajos');
    }
};

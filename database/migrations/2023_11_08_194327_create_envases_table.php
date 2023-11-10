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
        Schema::create('envases', function (Blueprint $table) {
            $table->id('fco_id');
            $table->enum('fco_act',['0','1'])->default('1');
            $table->string('fco_nombre');
            $table->string('fco_describe');
            $table->integer('fco_costo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('envases');
    }
};

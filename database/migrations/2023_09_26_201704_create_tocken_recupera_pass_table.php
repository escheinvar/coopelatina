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
        Schema::create('tocken_recupera_pass', function (Blueprint $table) {
            $table->id();
            $table->string('usr');
            $table->string('tocken');
            $table->dateTime('creacion');
            $table->dateTime('caduca');
            $table->integer('act')->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tocken_recupera_pass');
    }
};

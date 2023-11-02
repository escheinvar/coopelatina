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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('ap_pat');
            $table->string('ap_mat')->nullable();
            $table->string('usr')->unique();
            $table->enum('activo',['0','1'])->default('1');
            $table->enum('estatus',['pru','reg','act','pre'])->default('pru');
            $table->enum('priv',['root','admon','teso','usr'])->default('usr');
            $table->string('tel');
            $table->string('mail');
            $table->string('direccion')->nullable();;
            $table->timestamp('dateregistro')->nullable();
            $table->timestamp('membrefin')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

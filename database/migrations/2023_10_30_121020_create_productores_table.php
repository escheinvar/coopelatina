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
        Schema::create('productores', function (Blueprint $table) {
            $table->id('prod_id');
            $table->enum('prod_act',['0','1'])->default('1');
            $table->string('prod_nombrecorto');
            $table->string('prod_nombrelargo');
            $table->string('prod_contacto')->nullable();
            $table->string('prod_tel')->nullable();
            $table->string('prod_correo')->nullable();
            $table->longText('prod_descripcion')->nullable();
            $table->string('prod_direccion')->nullable();
            $table->string('prod_http')->nullable();
            $table->string('prod_facebook')->nullable();
            $table->string('prod_instagram')->nullable();
            $table->string('prod_youtube')->nullable();
            $table->enum('prod_tipo',['Cooperativa','Organización','Familiar','Ocasion','Microempresa','Empresa','Otro'])->nullable();            
            $table->string('prod_logo')->nullable();
            $table->longText('prod_fotos')->nullable();
            $table->integer('prod_orden')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productores');
    }
};

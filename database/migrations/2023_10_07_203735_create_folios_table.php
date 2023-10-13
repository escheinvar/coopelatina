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
        Schema::create('folios', function (Blueprint $table) {
            $table->id('fol_id');
            $table->enum('fol_act',['0','1'])->default('1');
            $table->integer('fol_edo')->default('4');  ### 4:pedido 3:pagado 2:EntrParcial 1:EntregaTotal 0:cancelado
            $table->integer('fol_anio');
            $table->integer('fol_mes');
            $table->integer('fol_usrid');
            $table->string('fol_usr');
            $table->string('fol_pagoimg')->nullable();
            $table->date('fol_pagodate')->nullable(); 
            $table->date('fol_canceldate')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('folios');
    }
};

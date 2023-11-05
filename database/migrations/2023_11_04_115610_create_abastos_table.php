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
        Schema::create('abastos', function (Blueprint $table) {
            $table->id('aba_id');
            #$table->enum('aba_act',['0','1'])->default('1');
            $table->integer('aba_anio');
            $table->integer('aba_mes');

            $table->enum('aba_com',['com1','com2']);            ### Comanda de entrega
            $table->integer('aba_prodid');                      ### Número de id del producto
            $table->string('aba_prodsabor');                    ### sabor del producto
            $table->string('aba_producto')->nullable();         ### concatenado identificador:  com1:idnum@sabor
            
            $table->enum('aba_listas',['0','1'])->default('0');     ### Indica si ya se realizó el pedido de lista de abasto
            $table->integer('aba_listas_cantpeds')->default('0');   ### Cantidad solicitada para pedidos
            $table->integer('aba_listas_canttien')->default('0');   ### Cantidad solicitada para tienda

            
            $table->enum('aba_abasto',['0','1'])->default('0'); ### Indica si ya se realizó el abasto por el proveedor
            $table->integer('aba_abasto_cant')->nullable();      ### Cantidad recibida
            $table->date('aba_abasto_date')->nullable();         ### Fecha en que se realizó el abasto
            

            $table->enum('aba_faltante',['0','1'])->default('0');  ### if aba_faltante =0 entrega correcta =1 faltan pedidos
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abastos');
    }
};

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
        Schema::create('folios_prods', function (Blueprint $table) {
            $table->id('ped_id');
            $table->enum('ped_act',['0','1'])->default('1');
            $table->integer('ped_folio');
            $table->enum('ped_entregado',['0','1'])->default('0');

            $table->string('ped_producto');                 #### com1:id@sabor 
            $table->enum('ped_entrega',['com1','com2','oca']);    #### com1 o com2 u ocasión
            $table->integer('ped_prodid');                  #### 123
            $table->string('ped_prod');                     #### Malbabiscos
            $table->string('ped_prodvar')->nullable();      #### de Menta

            $table->string('ped_prodpresenta')->nullable(); #### Paquetes de 10
            $table->integer('ped_cant');                    #### 10
            $table->decimal('ped_costo',6,1);               #### 60.0
            $table->integer('ped_usrid');                   # IDusuario (Num)
            $table->integer('ped_cantentregada')->default('0');
            $table->date('ped_entregadate')->nullable();
            $table->enum('ped_transfiere',['0','1'])->default('0');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('folios_prods');
    }
};

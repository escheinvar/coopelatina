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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->enum('activo',['0','1'])->default('1');
            $table->string('gpo');
            $table->string('nombre');
            $table->string('variantes')->nullable();
            $table->string('presentacion');
            $table->enum('entrega',['no','com1','com2','com12','comid'])->default('no');
            $table->enum('venta',['si','no'])->default('no');
            $table->integer('existencias')->default(0);
            $table->decimal('costo',6,1)->default('0');
            $table->decimal('precioact',6,1)->default('0');
            $table->decimal('precioreg',6,1)->default('0');
            $table->decimal('preciopub',6,1)->default('0');
            
            $table->enum('mintipo',['0','1','2'])->default('0');  ##0=no 1=producto (num de prods.) 2=proovedor(monto $)
            $table->decimal('min',6,1)->default('0');

            $table->string('proveedor');
            $table->string('categoria');
            $table->string('responsable');
            $table->longText('descripcion')->nullable();
            $table->string('img')->nullable();
            $table->integer('orden')->default('9999');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};

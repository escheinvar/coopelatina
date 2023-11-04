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
            $table->id('ab_id');
            $table->enum('ab_act',['0','1'])->default('1');
            #$table->string('prod_nombrecorto');
            #$table->string('prod_nombrelargo');
            #$table->string('prod_contacto')->nullable();

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

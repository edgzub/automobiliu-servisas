<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('pavadinimas');
            $table->text('aprasymas');
            $table->decimal('kaina', 8, 2);
            $table->decimal('trukme_valandomis', 4, 2);
            $table->string('kategorija');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('services');
    }
};
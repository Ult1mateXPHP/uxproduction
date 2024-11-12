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
        Schema::create('prods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->string('ver');
        });

        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->integer('prod');
            $table->string('build');
        });

        Schema::create('rcon_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('access');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prods');
        Schema::dropIfExists('packages');
        Schema::dropIfExists('rcon_methods');
    }
};

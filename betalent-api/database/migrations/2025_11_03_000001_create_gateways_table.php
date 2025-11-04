<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


// database/migrations/2025_11_03_000001_create_gateways_table.php
return new class extends Migration {
    public function up(): void {
        Schema::create('gateways', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->integer('priority')->default(1);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('gateways');
    }
};

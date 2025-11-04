<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


// database/migrations/2025_11_03_000004_create_transactions_table.php
return new class extends Migration {
    public function up(): void {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained();
            $table->foreignId('gateway_id')->constrained();
            $table->string('external_id')->nullable();
            $table->enum('status', ['PENDING', 'SUCCESS', 'FAILED']);
            $table->integer('amount');
            $table->string('card_last_numbers', 4);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('transactions');
    }
};

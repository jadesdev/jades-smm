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
        Schema::create('transactions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('user_id')->constrained()->cascadeOnDelete();
            $table->string('type')->default('credit');
            $table->string('service')->nullable();
            $table->string('code')->nullable();
            $table->double('amount', 20, 3)->nullable();
            $table->double('charge', 20, 3)->default(0);
            $table->string('message')->nullable();
            $table->string('status')->default('pending');
            $table->string('image')->nullable();
            $table->double('old_balance', 20, 2)->nullable();
            $table->double('new_balance', 20, 2)->nullable();
            $table->json('meta')->nullable();
            $table->json('response')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

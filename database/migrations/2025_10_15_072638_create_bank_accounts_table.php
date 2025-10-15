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
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('reference')->unique();
            $table->string('bank_name')->nullable();
            $table->string('number')->nullable();
            $table->string('name')->nullable();
            $table->string('bank_code')->nullable();
            $table->string('type')->default('static');
            $table->string('provider')->default('korapay');
            $table->json('meta')->nullable();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('kyc_type')->nullable()->after('api_token');
            $table->string('kyc_number')->nullable()->after('kyc_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_accounts');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('kyc_type');
            $table->dropColumn('kyc_number');
        });
    }
};

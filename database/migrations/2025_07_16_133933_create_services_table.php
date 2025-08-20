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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('categories')->cascadeOnDelete();
            $table->foreignId('api_provider_id')->nullable()->constrained('api_providers')->nullOnDelete();
            $table->bigInteger('api_service_id')->nullable()->index();
            $table->tinyInteger('manual_order')->default(0);
            $table->string('name');
            $table->string('type')->default('default');
            $table->decimal('price', 10, 2)->default(0)->index();
            $table->decimal('api_price', 10, 2)->default(0);
            $table->decimal('original_price', 10, 2)->default(0); // api price to site currency/rate
            $table->integer('min')->default(1);
            $table->integer('max')->default(1000);
            $table->longText('description')->nullable();
            $table->boolean('dripfeed')->default(0);
            $table->boolean('cancel')->default(0);
            $table->boolean('refill')->default(0);
            $table->boolean('refill_automatic')->default(0);
            $table->boolean('status')->default(1)->index();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['category_id', 'status']);
            $table->index('api_provider_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};

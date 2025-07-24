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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('parent_order_id')->nullable();

            $table->unsignedBigInteger('api_service_id')->nullable();
            $table->unsignedBigInteger('api_provider_id')->nullable();
            $table->bigInteger('api_order_id')->nullable();
            $table->integer('api_refill_id')->nullable();

            $table->enum('type', ['direct', 'api'])->default('direct');
            $table->string('service_type', 50)->default('default');
            $table->string('link', 255)->nullable();
            $table->unsignedInteger('quantity')->nullable();
            $table->unsignedInteger('start_counter')->nullable();
            $table->unsignedInteger('remains')->default(0);

            $table->decimal('price', 15, 4)->default(0);
            $table->decimal('api_price', 15, 4)->default(0);
            $table->decimal('profit', 15, 4)->default(0);

            $table->enum('status', ['pending', 'inprogress', 'processing', 'completed', 'partial', 'canceled', 'refunded', 'error', 'fail'])->default('pending');

            $table->text('comments')->nullable();            
            $table->text('usernames')->nullable();           
            $table->string('username')->nullable();          
            $table->text('hashtags')->nullable();            
            $table->string('hashtag')->nullable();           
            $table->string('media')->nullable();

            $table->enum('sub_status', ['active', 'paused', 'completed', 'expired', 'canceled'])->nullable();
            $table->unsignedInteger('sub_posts')->nullable();
            $table->unsignedInteger('sub_min')->nullable();
            $table->unsignedInteger('sub_max')->nullable();
            $table->unsignedInteger('sub_delay')->nullable();
            $table->date('sub_expiry')->nullable(); 
            $table->json('sub_response_orders')->nullable(); 
            $table->json('sub_response_posts')->nullable();

            $table->boolean('is_drip_feed')->default(false); 
            $table->unsignedInteger('runs')->default(0);
            $table->unsignedSmallInteger('interval')->default(0); 
            $table->unsignedInteger('dripfeed_quantity')->default(0);
            $table->string('refill_status', 20)->nullable();
            $table->date('refilled_at')->nullable();
            $table->text('note')->nullable();
            $table->tinyInteger('error', false, true)->default(0);
            $table->string('error_message', 500)->nullable();
            $table->json('response')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('set null');
            $table->foreign('parent_order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('api_provider_id')->references('id')->on('api_providers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

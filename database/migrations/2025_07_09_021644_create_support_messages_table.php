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
        Schema::create('support_messages', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('ticket_id')->constrained('support_tickets')->onDelete('cascade');
            $table->foreignUlid('user_id')->nullable()->constrained()->onDelete('set null');
            $table->text('message')->nullable();
            $table->enum('type', ['text', 'image'])->default('text');
            $table->string('image')->nullable();
            $table->boolean('is_admin')->default(false);
            $table->timestamps();

            $table->index(['ticket_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_messages');
    }
};

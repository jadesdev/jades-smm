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
        Schema::create('newsletters', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->boolean('user_emails')->default(false);
            $table->text('other_emails')->nullable();
            $table->string('subject')->nullable();
            $table->longText('content')->nullable();
            $table->tinyInteger('status')->default(2);
            $table->timestamp('date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newsletters');
    }
};

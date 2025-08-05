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
        Schema::table('settings', function (Blueprint $table) {
            $table->string('twitter')->nullable()->after('secondary');
            $table->string('facebook')->nullable()->after('twitter');
            $table->string('instagram')->nullable()->after('facebook');
            $table->string('telegram')->nullable()->after('instagram');
            $table->string('whatsapp')->nullable()->after('telegram');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['twitter', 'facebook', 'instagram', 'telegram', 'whatsapp']);
        });
    }
};

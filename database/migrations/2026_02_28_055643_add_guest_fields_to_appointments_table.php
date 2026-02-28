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
        Schema::table('appointments', function (Blueprint $table) {
            $table->string('guest_name')->nullable();
            $table->string('guest_email')->nullable();
            $table->string('guest_phone')->nullable();
            $table->dateTime('expires_at')->nullable();
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
        });
        Schema::table('appointments', function (Blueprint $table) {
            $table->unsignedBigInteger('client_id')->nullable()->change();
        });
        Schema::table('appointments', function (Blueprint $table) {
            $table->foreign('client_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
        });
        Schema::table('appointments', function (Blueprint $table) {
            $table->unsignedBigInteger('client_id')->nullable(false)->change();
        });
        Schema::table('appointments', function (Blueprint $table) {
            $table->foreign('client_id')->references('id')->on('users')->cascadeOnDelete();
        });
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['guest_name', 'guest_email', 'guest_phone', 'expires_at']);
        });
    }
};

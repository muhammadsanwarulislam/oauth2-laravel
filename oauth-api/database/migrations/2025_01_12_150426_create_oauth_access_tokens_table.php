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
        Schema::create('oauth_access_tokens', function (Blueprint $table) {
            $table->string('id', 100)->primary();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->unsignedBigInteger('client_id');
            $table->string('name')->nullable();
            $table->json('scopes')->nullable();
            $table->boolean('revoked')->default(false);
            $table->timestamps();
            $table->dateTime('expires_at')->nullable();
            $table->string('refresh_token')->nullable();

            $table->index(['client_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oauth_access_tokens');
    }
};

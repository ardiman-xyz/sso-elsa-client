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
        Schema::table('users', function (Blueprint $table) {
            $table->string('sso_id')->nullable()->unique()->after('id');
            
            $table->string('sso_provider')->nullable()->after('sso_id');
            
            $table->json('sso_data')->nullable()->after('sso_provider');
            
            $table->timestamp('last_login_at')->nullable()->after('remember_token');
            
            $table->text('sso_token')->nullable()->after('sso_data');
            $table->text('sso_refresh_token')->nullable()->after('sso_token');
            $table->timestamp('token_expires_at')->nullable()->after('sso_refresh_token');
            
            $table->string('password')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'sso_id',
                'sso_provider',
                'sso_data',
                'last_login_at',
                'sso_token',
                'sso_refresh_token',
                'token_expires_at'
            ]);
            
            // Kembalikan password menjadi required
            $table->string('password')->nullable(false)->change();
        });
    }
};

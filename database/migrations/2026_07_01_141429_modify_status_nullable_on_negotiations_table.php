<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('negotiations', function (Blueprint $table) {
            $table->enum('status', [
                'pending',
                'accepted',
                'rejected'
            ])->nullable()->default(null)->change();
        });
    }

    public function down(): void
    {
        Schema::table('negotiations', function (Blueprint $table) {
            $table->enum('status', [
                'pending',
                'accepted',
                'rejected'
            ])->default('pending')->nullable(false)->change();
        });
    }
};
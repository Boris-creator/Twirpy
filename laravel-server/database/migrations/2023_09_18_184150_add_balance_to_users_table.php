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
        Schema::table(app(\App\Models\User::class)->getTable(), function (Blueprint $table) {
            $table->integer('balance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(app(\App\Models\User::class)->getTable(), function (Blueprint $table) {
            $table->dropColumn('balance');
        });
    }
};
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
        Schema::create('wish_offers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('status');
            $table->foreignIdFor(\App\Models\Wish::class);
            $table->foreignIdFor(\App\Models\Book::class);
            $table->foreignIdFor(\App\Models\User::class);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wish_offers');
    }
};

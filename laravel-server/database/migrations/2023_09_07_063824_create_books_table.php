<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \App\Models;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title');
            $table->string('year')->nullable();
            $table->string('city')->nullable();
            $table->string('volume')->nullable();
            $table->string('annotation')->nullable();
            $table->string('isbn')->nullable();
            $table->unsignedBigInteger('published_by')->nullable();
            $table->string('filename')->nullable();
            $table->string('hash_sum')->nullable();
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->foreign('owner_id')->references('id')->on(app(Models\User::class)->getTable())->onDelete('cascade');
            $table->foreign('published_by')->references('id')->on(app(Models\Publisher::class)->getTable())->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};

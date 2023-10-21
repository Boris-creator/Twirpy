<?php

use App\Models;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(app(Models\Book::class)->getTable(), function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title');
            $table->string('year')->nullable();
            $table->string('city')->nullable();
            $table->string('volume')->nullable();
            $table->string('annotation')->nullable();
            $table->string('isbn')->nullable();
            $table->string('pages')->nullable();
            $table->unsignedBigInteger('language_id')->nullable();
            $table->unsignedBigInteger('published_by')->nullable();
            $table->string('filename')->nullable();
            $table->string('hash_sum')->nullable();
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->double('price');
            $table->foreign('owner_id')->references('id')->on(app(Models\User::class)->getTable())->onDelete('cascade');
            $table->foreign('published_by')->references('id')->on(app(Models\Publisher::class)->getTable())->onDelete('cascade');
            $table->foreign('language_id')->references('id')->on(app(Models\Language::class)->getTable())->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(app(Models\Book::class)->getTable());
    }
};

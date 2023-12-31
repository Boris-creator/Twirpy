<?php

use App\Models\Comment;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(app(Comment::class)->getTable(), function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->longText('text');
            $table->unsignedBigInteger('answer_to')->nullable();
            $table->foreignIdFor(\App\Models\Book::class);
            $table->foreignIdFor(\App\Models\User::class);
            $table->foreign('answer_to')->references('id')->on($table->getTable())->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(app(Comment::class)->getTable());
    }
};

<?php

use App\Models\Book;
use App\Models\Comment;
use App\Models\User;
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
            $table->foreignIdFor(Book::class);
            $table->foreignIdFor(User::class);
            $table->foreign('answer_to')->on($table->getTable())->references('id');
            $table->foreign('book_id')->on(app(Book::class)->getTable())->references('id');
            $table->foreign('user_id')->on(app(User::class)->getTable())->references('id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(app(Comment::class)->getTable());
    }
};

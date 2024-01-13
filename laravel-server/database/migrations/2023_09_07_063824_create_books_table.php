<?php

use App\Models\Book;
use App\Models\User;
use App\Traits\BookSchema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    use BookSchema;

    public function up(): void
    {
        Schema::create(app(Book::class)->getTable(), function (Blueprint $table) {
            $this->create($table);
            $table->string('filename')->nullable();
            $table->string('hash_sum')->nullable();
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->foreign('owner_id')->references('id')->on(app(User::class)->getTable())->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(app(Book::class)->getTable());
    }
};

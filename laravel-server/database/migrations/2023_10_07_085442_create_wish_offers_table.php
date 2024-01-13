<?php

use App\Models\Book;
use App\Models\User;
use App\Models\Wish;
use App\Models\WishOffer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(app(WishOffer::class)->getTable(), function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('status');
            $table->foreignIdFor(Wish::class);
            $table->foreignIdFor(Book::class);
            $table->foreignIdFor(User::class);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(app(WishOffer::class)->getTable());
    }
};

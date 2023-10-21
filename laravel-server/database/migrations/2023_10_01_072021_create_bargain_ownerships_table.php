<?php

use App\Models\BargainOwnership;
use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(app(BargainOwnership::class)->getTable(), function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('buyer_id');
            $table->unsignedBigInteger('seller_id');
            $table->foreignIdFor(Book::class);
            $table->foreign('buyer_id')->references('id')
                ->on(app(User::class)->getTable());
            $table->foreign('seller_id')->references('id')
                ->on(app(User::class)->getTable());
            $table->double('price');
            $table->boolean('done');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(app(BargainOwnership::class)->getTable());
    }
};

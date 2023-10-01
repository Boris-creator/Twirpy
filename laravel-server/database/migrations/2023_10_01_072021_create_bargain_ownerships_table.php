<?php

use App\Models\Book;
use App\Models\User;
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
        Schema::create('bargain_ownerships', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignIdFor(Book::class);
            $table->foreign('buyer_id')->references('id')
                ->on(app(User::class)->getTable());
            $table->foreign('seller_id')->references('id')
                ->on(app(User::class)->getTable());
            $table->double('price');
            $table->boolean('done');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bargain_ownerships');
    }
};

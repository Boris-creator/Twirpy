<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name')->unique();
            $table->unsignedBigInteger('alias_of')->nullable();
            $table->foreign('alias_of')->references('id')->on($table->getTable())->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};

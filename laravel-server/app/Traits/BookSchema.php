<?php

namespace App\Traits;

use App\Models;
use Illuminate\Database\Schema\Blueprint;

trait BookSchema
{
    public function create(Blueprint $table)
    {
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
        $table->double('price');
        $table->foreign('published_by')->references('id')->on(app(Models\Publisher::class)->getTable())->onDelete('cascade');
        $table->foreign('language_id')->references('id')->on(app(Models\Language::class)->getTable())->onDelete('cascade');
    }
}

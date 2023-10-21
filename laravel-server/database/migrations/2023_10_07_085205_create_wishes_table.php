<?php

use App\Models\User;
use App\Models\Wish;
use App\Traits\BookSchema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    use BookSchema;

    public function up(): void
    {
        Schema::create(app(Wish::class)->getTable(), function (Blueprint $table) {
            $this->create($table);
            $table->foreignIdFor(User::class);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(app(Wish::class)->getTable());
    }
};

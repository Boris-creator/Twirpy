<?php

use App\Models\Permission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(app(Permission::class)->getTable(), function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(app(Permission::class)->getTable());
    }
};

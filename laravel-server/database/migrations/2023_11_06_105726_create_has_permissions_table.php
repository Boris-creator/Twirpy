<?php

use App\Models\Permission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('has_permissions', function (Blueprint $table) {
            $table->id();
            $table->morphs('has_permissions');
            $table->unsignedBigInteger('permission_id');
            $table->foreign('permission_id')
                ->references('id')
                ->on(app(Permission::class)->getTable())
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('has_permissions');
    }
};

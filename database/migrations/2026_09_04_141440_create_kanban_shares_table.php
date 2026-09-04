<?php

use App\Domains\Kanban\Kanban;
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
        Schema::create('kanban_shares', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Kanban::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->enum('access', ['admin', 'write', 'read']);
            $table->timestamps();
            $table->unique(['kanban_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kanban_shares');
    }
};

<?php

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
        Schema::create('docu_links', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->string('password')->nullable();
            $table->dateTime('deadline')->nullable();
            $table->foreignId('docu_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('docu_links');
    }
};

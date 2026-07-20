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
        Schema::create('evaluation_criteria', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->text("description");
            $table->timestamps();
        });

        Schema::create('evaluation_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id');
            $table->foreignId('evaluation_criterion_id');
            $table->integer('note');
            $table->text('comment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_criteria');
    }
};

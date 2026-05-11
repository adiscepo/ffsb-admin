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
        Schema::create('production_houses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('website')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->text('remark')->nullable();
            $table->timestamps();
        });

        Schema::create('docu_production_house', function (Blueprint $table) {
            $table->foreignId('docu_id')->constrained()->cascadeOnDelete();
            $table->foreignId('production_house_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_house_docu');
        Schema::dropIfExists('production_houses');
    }
};

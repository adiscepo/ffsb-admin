<?php

use App\Models\Enum\DocuTarget;
use App\Models\Enum\DocuLang;
use App\Models\User;
use App\Models\EditionYear;
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
        Schema::create('docus', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('summary')->nullable();
            $table->integer('duration');
            $table->year('year');
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->enum('lang', DocuLang::cases());
            $table->enum('subtitles', DocuLang::cases())->nullable();
            $table->enum('target', DocuTarget::cases())->nullable();
            $table->text('comment')->nullable();
            $table->foreignId('edition_year_id')->constrained();
            $table->timestamps();
        });

        // The fields of the documentary (Biology, Math, etc.)
        // Use a pivot table
        Schema::create('fields', function (Blueprint $table) {
            $table->id();
            $table->string('field')->unique();
            $table->string('color');
            $table->timestamps();
        });

        Schema::create('docu_field', function (Blueprint $table) {
            $table->foreignId('docu_id')->constrained()->cascadeOnDelete();
            $table->foreignId('field_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('docu_fields');
        Schema::dropIfExists('fields');
        Schema::dropIfExists('docus');
    }
};

<?php

use App\Models\Enum\DocuLang;
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
        Schema::create('docus', function (Blueprint $table) {
            $table->id();
            $table->string(column: "title", length: 255);
            $table->string(column: "summary")->nullable();
            $table->integer("duration");
            $table->foreignIdFor(User::class, "found_by")->constrained()->cascadeOnDelete();
            $table->enum("lang", DocuLang::cases());
            $table->enum("subtitles", DocuLang::cases())->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('docus');
    }
};

<?php

use App\Domains\Programs\Program;
use App\Domains\Programs\Enum\ProgramEventKind;
use App\Models\EditionYear;
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
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('edition_year_id');
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->foreignId('user_id');
            $table->integer('version')->nullable();
            $table->timestamps();
        });

        Schema::create('program_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId(Program::class);
            $table->dateTime('start');
            $table->integer('duration');
            $table->enum('kind', ProgramEventKind::cases());
            $table->json('payload');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};

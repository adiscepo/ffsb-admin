<?php

namespace App\Domains\Programs;

use App\Domains\Events\Traits\Eventable;
use App\Domains\Programs\Enum\ProgramEventKind;
use App\Domains\Programs\Factory\ProgramEventFactory;
use App\Models\EditionYear;
use App\Models\Status;
use App\Models\User;
use Carbon\CarbonImmutable;
use Carbon\CarbonPeriod;
use Carbon\CarbonPeriodImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class ProgramEvent extends Model
{
    use HasFactory, Eventable;

    protected $fillable = ['program_id', 'start', 'duration', 'kind', 'payload'];

    protected $casts = [
        'payload' => 'array',
        'start' => 'immutable_datetime',
        'kind' => ProgramEventKind::class,
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    // The format of date has to be consistent wrt 'Y-m-d' format.
    public function getDay()
    {
        return CarbonImmutable::parse($this->start)->format('Y-m-d');
    }

    public function getHour()
    {
        return CarbonImmutable::parse($this->start)->format('H:i:s');
    }

    public function getPeriod(): CarbonPeriodImmutable
    {
        $start = $this->start;
        $end = $start->addMinutes($this->duration);
        return CarbonPeriodImmutable::create($start, $end);
    }
    // Return the number of minutes between the start of the day (00:00) and
    // the start of the event
    public function getStartInMinutes()
    {
        return $this->start->startOfDay()->diffInMinutes($this->start, false);
    }

    protected static function newFactory(): ProgramEventFactory
    {
        return ProgramEventFactory::new();
    }
}

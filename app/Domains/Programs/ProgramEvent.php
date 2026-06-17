<?php

namespace App\Domains\Programs;

use App\Domains\Docus\Docu;
use App\Domains\Events\Traits\Eventable;
use App\Domains\Programs\Enum\ProgramEventKind;
use App\Domains\Programs\Factory\ProgramEventFactory;
use App\Models\EditionYear;
use App\Models\Status;
use App\Models\User;
use Carbon\CarbonImmutable;
use Carbon\CarbonPeriod;
use Carbon\CarbonPeriodImmutable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Override;

use function Laravel\Prompts\error;

class ProgramEvent extends Model
{
    use HasFactory, Eventable;

    // Because of the different value that can take an event (projection,
    // intervention, other)
    // public string $title;
    // public int $duration;
    // public string $from_to;

    protected $fillable = ['program_id', 'start', 'kind', 'payload'];

    protected $casts = [
        'payload' => 'array',
        'start' => 'immutable_datetime',
        'kind' => ProgramEventKind::class,
    ];

    /**
     * Get the event's title.
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn() => match ($this->getAttribute('kind')) {
                ProgramEventKind::PROJECTION => Docu::findOrFail($this->getAttribute('payload')['docu_id'])->title,
                default => $this->getAttribute('payload')['name'],
            }
        );
    }

    /**
     * Get the event's title.
     */
    protected function description(): Attribute
    {
        return Attribute::make(
            get: fn() => match ($this->getAttribute('kind')) {
                ProgramEventKind::PROJECTION => Docu::findOrFail($this->getAttribute('payload')['docu_id'])->comment,
                default => $this->getAttribute('payload')['description'] ?? null,
            }
        );
    }

    /**
     * Get the event's duration.
     */
    protected function duration(): Attribute
    {
        return Attribute::make(
            get: fn() => match ($this->getAttribute('kind')) {
                ProgramEventKind::PROJECTION => intval(Docu::findOrFail($this->getAttribute('payload')['docu_id'])->duration),
                default => intval($this->getAttribute('payload')['duration']),
            }
        );
    }

    /**
     * Get the event's duration in string format.
     * WARNING: The method as to be in camelCase and will be
     *          in snake_case as an attribute (lost 15min bc of that)
     */
    protected function fromTo(): Attribute
    {
        return Attribute::make(
            get: fn() =>  $this->getPeriod()->getStartDate()->format('H:i') . " à " . $this->getPeriod()->getEndDate()->format('H:i'),
        );
    }

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
        $end = $start->addMinutes($this->getAttribute('duration'));
        return CarbonPeriodImmutable::create($start, $end, CarbonPeriod::EXCLUDE_END_DATE | CarbonPeriod::EXCLUDE_START_DATE);
    }

    public function isOverlappingOtherEvent(): bool
    {
        $duration = $this->getAttribute('duration');
        $start_hour = $this->start;
        $event_period = CarbonPeriodImmutable::create($start_hour, $start_hour->addMinutes($duration), CarbonPeriod::EXCLUDE_END_DATE | CarbonPeriod::EXCLUDE_START_DATE);
        // Loop over all the event in the same day and check if the duration
        // of the new one overlaps with the ones already in the program
        // dd($this->program->eventsFor($this->getDay())->reverse());
        foreach ($this->program->eventsFor($this->getDay())->reverse() as $event) {
            // Because the events are stored in db, eventsFor will return also
            // the current event
            if ($event->id == $this->id) continue;
            error_log('Comparing ' . $this->start . ' with ' . $event->start);
            $program_event_period = $event->getPeriod();
            if ($event_period->overlaps($program_event_period)) {
                return true;
            }
        }
        return false;
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

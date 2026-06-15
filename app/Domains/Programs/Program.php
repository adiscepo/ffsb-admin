<?php

namespace App\Domains\Programs;

use App\Domains\Events\Traits\Eventable;
use App\Domains\Programs\Factory\ProgramFactory;
use App\Models\EditionYear;
use App\Models\Status;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;

class Program extends Model
{
    use HasFactory, Eventable;

    protected $fillable = ['name', 'start_date', 'end_date', 'edition_year_id', 'user_id', 'version'];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function edition_year(): BelongsTo
    {
        return $this->belongsTo(EditionYear::class);
    }

    public function statuses(): MorphToMany
    {
        return $this->morphToMany(Status::class, 'statusable');
    }

    public function number_days(): int
    {
        $start_date = Carbon::create($this->start_date);
        $end_date = Carbon::create($this->end_date);
        return $start_date->diff($end_date)->days + 1;
    }

    public function interval_days(): CarbonPeriod
    {
        $start_date = Carbon::create($this->start_date);
        $end_date = Carbon::create($this->end_date);
        return CarbonPeriod::create($start_date, $end_date);
    }

    public function events(): HasMany
    {
        return $this->hasMany(ProgramEvent::class);
    }

    public function getCalendar()
    {
        $calendar = collect();
        foreach ($this->interval_days() as $day) {
            $calendar->push($this->eventsFor($day));
        }
        return $calendar;
    }

    public function eventsFor($date)
    {
        $startOfDay = Carbon::parse($date)->startOfDay();
        $endOfDay = Carbon::parse($date)->endOfDay();

        return ProgramEvent::whereBetween('start', [$startOfDay, $endOfDay])->get();
    }

    protected static function newFactory(): ProgramFactory
    {
        return ProgramFactory::new();
    }
}

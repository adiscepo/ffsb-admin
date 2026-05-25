<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evaluation extends Model
{
    /** @use HasFactory<\Database\Factories\EvaluationFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'docu_id', 'evaluation', 'comment'];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function docu(): BelongsTo {
        return $this->belongsTo(Docu::class);
    }
    
    public function note(): int {
        $eval = json_decode($this->evaluation, true);
        $note = 0;
        foreach ($eval as $id => $data) {
            $note = $note + intval($data['note']);
        }
        return $note;
    }
}

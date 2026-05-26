<?php

namespace App\Models;

use App\Models\EvaluationCriterion;
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
        if ($eval) {
            foreach ($eval as $id => $data) {
                $note = $note + intval($data['note']);
            }
        }
        return $note;
    }

    public function notes(): array {
        $eval = json_decode($this->evaluation, true);
        $res = [];
        if ($eval) {
            foreach ($eval as $id => $data) {
                array_push($res, $data['note']);
            }
        }
        return $res;
    }

    public function getNoteCriterion(EvaluationCriterion $criterion) {
        $eval = json_decode($this->evaluation, true);
        if ($eval) {
            if (array_key_exists($criterion->id, $eval)) {
                return $eval[$criterion->id]['note'];
            }
        }
        return '';
    }

    public function getCommentCriterion(EvaluationCriterion $criterion) {
        $eval = json_decode($this->evaluation, true);
        if ($eval) {
            if (array_key_exists($criterion->id, $eval)) {
                return $eval[$criterion->id]['comment'];
            }
        }
        return '';
    }

    public function maxNote(): int {
        return EvaluationCriterion::all()->count() * 5;
    }
}

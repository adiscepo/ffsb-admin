<?php

namespace App\Domains\Files;

use App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class File extends Model
{
    use HasFactory;

    protected $fillable = ['filename', 'full_path', 'client_name', 'size', 'extension', 'user_id'];

    protected $primaryKey = 'filename';
    protected $keyType = 'string';
    public $incrementing = false;

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    static function formatSize(int $bytes): string
    {
        if (intdiv($bytes, 1_000_000) > 0) {
            return round($bytes / 1_000_000, 2) . ' MB';
        }
        return round($bytes / 1_000, 2) . ' KB';
    }

    public function getSize(): string
    {
        return File::formatSize($this->size);
    }
}

<?php

namespace App\Domains\Roles;

use App\Domains\Roles\Factory\RoleFactory;
use App\Enums\Color;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'color',
    ];

    protected $casts = ['color' => Color::class];

    protected static function newFactory(): RoleFactory
    {
        return RoleFactory::new();
    }
}

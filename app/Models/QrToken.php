<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'token_hash',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];
}
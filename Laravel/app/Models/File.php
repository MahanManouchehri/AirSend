<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'original_name',
        'stored_name',
        'unique_hash',
        'size',
        'expires_at',
        'user_token',

    ];

    public function isExpired(): bool
    {
        return now()->greaterThan($this->expires_at);
    }
}

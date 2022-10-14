<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoupleUser extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function couple(): BelongsTo
    {
        return $this->belongsTo(Couple::class);
    }
}

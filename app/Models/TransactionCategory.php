<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionCategory extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $incrementing = false;

    public function couple(): BelongsTo
    {
        return $this->belongsTo(Couple::class);
    }
}

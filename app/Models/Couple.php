<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Couple extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $appends = ['categories'];

    public function getCategoriesAttribute(): Collection
    {
        return TransactionCategory::query()->where('couple_id', auth()->id())
            ->orWhereNull('couple_id')
            ->orderBy('name')
            ->get();
    }
}

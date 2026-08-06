<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Borrowing extends Model
{
    protected $guarded = ['id'];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'borrowing_book')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}
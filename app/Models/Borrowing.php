<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Borrowing extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 'borrowings';

    protected $casts = [
        'borrow_date' => 'datetime',
        'return_date' => 'datetime',
        'actual_return_date' => 'datetime',
        'fine' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the member that owns the borrowing.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the books for this borrowing.
     */
    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'borrowing_book')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    /**
     * Check if borrowing is overdue.
     */
    public function isOverdue(): bool
    {
        if ($this->status === 'returned') {
            return false;
        }
        return now()->greaterThan($this->return_date);
    }

    /**
     * Get days overdue.
     */
    public function getDaysOverdue(): int
    {
        if (!$this->isOverdue()) {
            return 0;
        }
        return now()->diffInDays($this->return_date);
    }

    /**
     * Get late fee amount (Rp 5000 per day).
     */
    public function getCalculatedFine(): int
    {
        return $this->getDaysOverdue() * 5000;
    }
}

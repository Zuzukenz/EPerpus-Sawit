<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Member extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 'members';

    protected $casts = [
        'join_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the borrowings for the member.
     */
    public function borrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class);
    }

    /**
     * Get active borrowings (not yet returned).
     */
    public function activeBorrowings()
    {
        return $this->borrowings()->where('status', 'borrowed');
    }

    /**
     * Get total fines for this member.
     */
    public function getTotalFines(): float
    {
        return $this->borrowings()
            ->where('status', 'returned')
            ->sum('fine') ?? 0;
    }
}

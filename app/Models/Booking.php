<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id_string',
        'user_id',
        'book_date',
        'start_time',
        'end_time',
        'duration',
        'status',
        'cancellation_reason',
        'total_price',
    ];

    protected $casts = [
        'book_date' => 'date',
        'duration' => 'integer',
        'total_price' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function latestPayment(): HasOne
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    /**
     * Total amount paid by customer for this booking.
     */
    public function paidAmount(): float
    {
        return (float) $this->payments()->where('status', 'paid')->sum('amount');
    }

    /**
     * Remaining settlement balance.
     */
    public function remainingAmount(): float
    {
        $paid = $this->paidAmount();
        return max(0, (float) $this->total_price - $paid);
    }

    /**
     * Check whether this booking is fully paid (100%).
     */
    public function isFullyPaid(): bool
    {
        return $this->remainingAmount() <= 0 && $this->status === 'confirmed';
    }

    /**
     * Check if cancellation caused DP forfeiture (DP Hangus).
     */
    public function isDpHangus(): bool
    {
        return $this->status === 'cancelled' && str_contains(strtolower($this->cancellation_reason ?? ''), 'hangus');
    }
}


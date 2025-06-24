<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'rental_date',
        'expected_return_date',
        'actual_return_date',
        'total_price',
        'rental_fee',           // Tiền thuê
        'deposit_amount',       // Tiền cọc
        'deposit_type',         // Loại cọc (money/idcard)
        'deposit_note',         // Ghi chú về cọc
        'total_paid',           // Tổng tiền khách đã trả
        'refund_amount',        // Số tiền hoàn lại
        'status',
        'notes',
    ];

    protected $casts = [
        'rental_date' => 'date',
        'expected_return_date' => 'date',
        'actual_return_date' => 'date',
        'total_price' => 'decimal:2',
        'rental_fee' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'total_paid' => 'decimal:2',
        'refund_amount' => 'decimal:2',
    ];

    /**
     * Get all of the items for the rental.
     */
    public function items(): HasMany
    {
        return $this->hasMany(RentalItem::class);
    }

    /**
     * Get all of the products for the rental.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'rental_items');
    }

    // Relationship with customer
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    // Check if rental is overdue
    public function isOverdue(): bool
    {
        return $this->status === 'active' && 
               $this->expected_return_date->isPast() && 
               !$this->actual_return_date;
    }

    // Calculate overdue days
    public function getOverdueDays(): int
    {
        if (!$this->isOverdue()) {
            return 0;
        }
        
        return now()->diffInDays($this->expected_return_date);
    }

    /**
     * Calculate total amount customer needs to pay (rental fee + deposit)
     */
    public function getTotalAmount(): float
    {
        return $this->rental_fee + $this->deposit_amount;
    }

    /**
     * Calculate amount to refund when returning (deposit amount)
     */
    public function getRefundAmount(): float
    {
        return $this->deposit_amount;
    }

    /**
     * Check if deposit is money type
     */
    public function hasMoneyDeposit(): bool
    {
        return $this->deposit_type === 'money' && $this->deposit_amount > 0;
    }

    /**
     * Check if deposit is ID card type
     */
    public function hasIdCardDeposit(): bool
    {
        return $this->deposit_type === 'idcard' && !empty($this->deposit_note);
    }

    /**
     * Get formatted deposit information
     */
    public function getDepositInfo(): string
    {
        if ($this->hasMoneyDeposit()) {
            return number_format($this->deposit_amount) . ' VNĐ';
        } elseif ($this->hasIdCardDeposit()) {
            return 'CMND: ' . $this->deposit_note;
        }
        return 'Không có cọc';
    }

    // Scope for active rentals
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope for overdue rentals
    public function scopeOverdue($query)
    {
        return $query->where('status', 'active')
                    ->where('expected_return_date', '<', now());
    }

    // Số ngày trễ hạn (nếu có)
    public function getLateDays(): int
    {
        if (!$this->actual_return_date) return 0;
        $actual = $this->actual_return_date->diffInDays($this->rental_date);
        $paid = $this->expected_return_date->diffInDays($this->rental_date);
        $late = $actual - $paid;
        return $late > 0 ? $late : 0;
    }

    // Tiền phạt trễ hạn
    public function getLateFee(): int
    {
        $late = $this->getLateDays();
        if ($late <= 0) return 0;
        if ($late === 1) return 20000;
        return 20000 + ($late - 1) * 10000;
    }
} 
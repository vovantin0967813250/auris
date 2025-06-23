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
        'deposit_amount',
        'status',
        'notes'
    ];

    protected $casts = [
        'rental_date' => 'date',
        'expected_return_date' => 'date',
        'actual_return_date' => 'date',
        'total_price' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
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

    // Calculate total amount (rental price + deposit)
    public function getTotalAmount(): float
    {
        $depositAmount = ($this->deposit_type === 'money' && $this->deposit) ? $this->deposit : 0;
        return $this->rental_price + $depositAmount;
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
} 
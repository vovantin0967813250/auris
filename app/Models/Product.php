<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_code',
        'name',
        'description',
        'image',
        'purchase_price',
        'rental_price',
        'purchase_date',
        'status'
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'purchase_price' => 'decimal:2',
        'rental_price' => 'decimal:2',
    ];

    // Relationship with rentals
    public function rentals()
    {
        return $this->belongsToMany(Rental::class, 'rental_items');
    }

    /**
     * Get all of the rental items for the product.
     */
    public function rentalItems(): HasMany
    {
        return $this->hasMany(RentalItem::class);
    }

    // Get active rental
    public function activeRental()
    {
        return $this->rentals()->where('status', 'active')->first();
    }

    // Check if product is available
    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    // Scope for available products
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    // Scope for rented products
    public function scopeRented($query)
    {
        return $query->where('status', 'rented');
    }
} 
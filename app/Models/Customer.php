<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'address'
    ];

    // Relationship with rentals
    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class);
    }

    // Get active rentals
    public function activeRentals()
    {
        return $this->rentals()->where('status', 'active');
    }

    // Get rental history
    public function rentalHistory()
    {
        return $this->rentals()->where('status', 'returned');
    }
} 
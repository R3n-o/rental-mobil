<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'brand',
        'model',
        'plate_number',
        'daily_rent_price',
        'is_available',
        'image'
    ];

    // Relasi: Mobil milik satu kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi: Mobil punya banyak booking
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
    
    // Relasi: Mobil punya banyak review
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
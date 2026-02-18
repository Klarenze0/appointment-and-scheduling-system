<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'duration_minutes',
        'price',
        'payment_mode',
        'deposit_amount',
        'is_active',    
    ];

    public function casts(): array {
        return [
            'price' => 'decimal:2',
            'deposit_amount' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    // relationships

    public function staffProfiles(){
        return $this->belongsToMany(StaffProfile::class, 'staff_services');
    }

    public function appointments() {
        return $this->hasMany(Appointment::class);
    }

    public function scopeActive($query){
        return $query->where('is_active', true);
    }
}

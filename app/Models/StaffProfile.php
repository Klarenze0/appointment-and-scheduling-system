<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bio',
        'timezone',
        'is_active',
    ];

    protected function casts(): array {
        return [
            'is_active' => 'boolean',
        ];
    }

    // relationships

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function services() {
        return $this->belongsToMany(Service::class, 'staff_services');
    }

    public function schedule() {
        return $this->hasMany(StaffSchedule::class);
    }

    public function overrides() {
        return $this->hasMany(StaffOverride::class);
    }

    public function appointments() {
        return $this->hasMany(Appointment::class);
    }

    public function scopeActive($query) {
        return $query->where('is_active', true);
    }


}

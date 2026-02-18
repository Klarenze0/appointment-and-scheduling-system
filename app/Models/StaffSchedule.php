<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_profile_id',
        'day_of_week',
        'start_time',
        'end_time',
        'is_active',
    ];

    protected function casts(): array {
        return [
            'is_active' => 'boolean',
            'day_of_week' => 'integer',
        ];
    }

    // relationships

    public function staffProfile() {
        return $this->belongsTo(StaffProfile::class);
    }

    public function getDatNameAttribute() {
        return [
            'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday',
            'Friday', 'Saturday'
        ][$this->day_of_week];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffOverride extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_profile_id',
        'override_date',
        'start_time',
        'end_time',
        'is_unavailable',
        'reason'
    ];

    protected function casts(): array {
        return [
            'override_date' => 'date',
            'is_unavailable' => 'boolean',
        ];
    }

    // relationship

    public function staffProfile() {
        return $this->belongsTo(StaffProfile::class);
    }
}

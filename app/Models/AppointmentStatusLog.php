<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentStatusLog extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'appointment_id',
        'changed_by',
        'old_status',
        'new_status',
        'note',
    ];

    // relationship

    public function appointment() {
        return $this->belongsTo(Appointment::class);
    }

    public function changedBy() {
        return $this->belongsTo(User::class, 'changed_by');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'staff_profile_id',
        'service_id',
        'booked_by',
        'scheduled_at',
        'end_at',
        'status',
        'notes',
        'cancellation_reason',
    ];

    protected function casts(): array {
        return [
            'scheduled_at' => 'datetime',
            'end_at' => 'datetime',
        ];
    }

    // relationships

    public function client() {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function bookedBy() {
        return $this->belongsTo(User::class, 'booked_by');
    }

    public function staffProfile() {
        return $this->belongsTo(Service::class);
    }

    public function service() {
        return $this->belongsTo(Service::class);
    }

    public function statusLogs() {
        return $this->hasMany(AppointmentStatusLog::class);
    }

    public function payment() {
        return $this->hasOne(Payment::class);
    }

    public function notifications() {
        return $this->hasMany(NotificationLog::class);
    }

    // scopes

    public function scopeUpcoming($query) {
        return $query->where('scheduled_at', '>=', now())->whereNotIn('status', ['cancelled']);
    }

    public function scopePast($query) {
        return $query->where('end_at', '<', now());
    }

    public function scopeForClient($query, $clientId) {
        return $query->where('client_id', $clientId);
    }

    public function scopeForStaff($query, $staffProfileId) {
        return $query->where('staff_profile_id', $staffProfileId);
    }
}

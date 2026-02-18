<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    protected $table = 'notification_log';

    protected $fillable = [
        'user_id',
        'appointment_id',
        'type',
        'channel',
        'status',
        'sent_at',
    ];

    protected function casts(): array {
        return [
            'sent_at' => 'datetime',
        ];
    }

    // relationships

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function appointment() {
        return $this->belongsTo(Appointment::class);
    }
    
}

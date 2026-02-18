<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'amount',
        'deposit_amount',
        'mode',
        'status',
        'gateway',
        'transaction_reference',
        'payload',
    ];

    protected function casts(): array {
        return [
            'amount' => 'decimal:2',
            'deposit_amount' => 'decimal:2',
            'payload' => 'array',
        ];
    }

    // relationships

    public function appointment() {
        return $this->belongsTo(Appointment::class);
    }

    public function isPaid(): bool {
        return $this->status === 'paid';
    }

    public function isPending(): bool {
        return $this->status === 'pending';
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'participant_id',
        'seminar_id',
        'payment_method',
        'account_name',
        'account_number',
        'reference_number',
        'screenshot',
        'payment_status',
        
    ];

    public function participant() {
        return $this->belongsTo(Participant::class, 'participant_id', 'id');
    }
}

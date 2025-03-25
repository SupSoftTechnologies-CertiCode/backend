<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = [
        'seminar_id',
        'user_id',
        'guest_id',
    ];

    // A participant belongs to a seminar
    public function seminar()
    {
        return $this->belongsTo(Seminar::class, 'seminar_id', 'id');
    }

    // A participant belongs to a registered user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // A participant belongs to a guest
    public function guest()
    {
        return $this->belongsTo(Guest::class, 'guest_id', 'id');
    }
}

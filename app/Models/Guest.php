<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    use HasFactory;

    protected $fillable = [
        'seminar_id',
        'name',
        'address',
        'phone',
        'email',
    ];

    public function seminar()
    {
        return $this->belongsTo(Seminar::class, 'seminar_id', 'id');
    // Relationship: A guest belongs to a seminar
    }

    // Relationship: A guest can have many participation records
    public function participants()
    {
        return $this->hasMany(Participant::class);
    }
}

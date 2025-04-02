<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    //
    protected $table = 'users_profile';
    protected $fillable = [
        'users_id',
        'first_name',
        'last_name',
        'middle_name',
        'age',
        'gender',
        'address',
        'phone',
        'bio',
        'country',
        'province',
        'facebook',
        'x',
        'linkedin',
        'instagram'
    ];

    public function users() 
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }
}

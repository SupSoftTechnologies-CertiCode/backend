<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seminar extends Model
{
    protected $fillable = [
        'name_of_seminar',
        'topics',
        'description',
        'date',
        'location',
        'speaker_name',
        'organization_name',
        'speaker_image',
        'seminar_image',
        'about_the_speaker',
        'certificate_template_id',
        'price',
    ];

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }

    public function certificateTemplate()
    {
        return $this->belongsTo(CertificateTemplate::class);
    }
    
}

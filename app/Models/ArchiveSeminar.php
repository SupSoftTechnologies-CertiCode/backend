<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArchiveSeminar extends Model
{
    use HasFactory;

    protected $table = 'archive_seminars';

    protected $fillable = [
        'id',
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
    ];

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArchivedCertificateTemplate extends Model
{
    
    protected $table = 'archived_certificate_templates'; // Specify table name

    protected $fillable = ['id','name', 'pdf_filename'];
}

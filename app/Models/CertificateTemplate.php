<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificateTemplate extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'name', 'pdf_filename', 'json_layout'];

    // Relationship with Seminars
    public function seminars()
    {
        return $this->hasMany(Seminar::class);
    }
}

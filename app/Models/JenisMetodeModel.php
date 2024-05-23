<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisMetodeModel extends Model
{
    use HasFactory;
    protected $table = 'jenis_metode';
    protected $primaryKey = 'id';
}

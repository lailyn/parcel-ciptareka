<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatuanBeratModel extends Model
{
    use HasFactory;
    protected $table = 'satuan_berat';
    protected $primaryKey = 'id';
}

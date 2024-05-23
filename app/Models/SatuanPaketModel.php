<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatuanPaketModel extends Model
{
    use HasFactory;
    protected $table = 'satuan_paket';
    protected $primaryKey = 'id';
}

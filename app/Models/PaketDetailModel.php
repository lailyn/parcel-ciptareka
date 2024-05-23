<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaketDetailModel extends Model
{
    use HasFactory;
    protected $table = 'paket_detail';
    protected $primaryKey = 'id';
}

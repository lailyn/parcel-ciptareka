<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodeBayarModel extends Model
{
    use HasFactory;
    protected $table = 'metode_bayar';
    protected $primaryKey = 'id';
    public $timestamps = false;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KabupatenModel extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'location_cities';
    protected $primaryKey = 'id_cities';
}

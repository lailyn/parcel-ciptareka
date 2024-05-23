<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelurahanModel extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'location_village';
    protected $primaryKey = 'id_village';
    
}

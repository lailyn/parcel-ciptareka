<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NegaraModel extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'location_countries';
    protected $primaryKey = 'id';
}

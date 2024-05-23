<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManifestJemputDetailModel extends Model
{
    use HasFactory;
    protected $table = 'manifestJemput_detail';
    protected $primaryKey = 'id';
}

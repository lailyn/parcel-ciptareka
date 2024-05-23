<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberPaketModel extends Model
{
    use HasFactory;
    protected $table = 'member_paket';
    protected $primaryKey = 'id';    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PengembalianSetoranModel extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'pengembalianSetoran';
    protected $primaryKey = 'id';

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = Str::uuid()->toString();
            }
        });
    }
    public function getIncrementing()
    {
        return false;
    }    

    public function getKeyType()
    {
        return 'string';
    }
}

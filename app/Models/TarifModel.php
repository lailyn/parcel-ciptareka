<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class TarifModel extends Model
{
    use HasFactory;
    protected $table = 'tarif';
    protected $primaryKey = 'id';

    public function cekDataOngkir($jenis_tarif,$asal,$tujuan){        
        $cekData = DB::table("tarif AS t")->join("tarif_detail AS td","t.id","=","td.tarif_id")
                ->where("td.status",1)->where("t.asal",$asal)->where("t.tujuan",$tujuan)
                ->where("td.jenis_tarif_id",$jenis_tarif)->get(["td.cost","td.durasi"]);
        if($cekData->count()){
            $dt = $cekData->first();
            $ongkos = $dt->cost;
            $durasi = $dt->durasi;
        }else{
            $ongkos = 0;$durasi = 0;
        }
        $result = [
            'ongkos' => $ongkos,
            'durasi' => $durasi
        ];
        return $result;
    }
}

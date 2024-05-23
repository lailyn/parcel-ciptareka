<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KecamatanModel extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'location_subdistrict';
    protected $primaryKey = 'id_subdistrict';

    function get_location($id_kecamatan=null,$id_kabupaten=null,$id_provinsi=null){
        $where="";
        if(!is_null($id_kecamatan)) $where .= "location_subdistrict.id_subdistrict = '$id_kecamatan'";        
        if(!is_null($id_kabupaten)) $where .= "location_cities.id_cities = '$id_kabupaten'";
        if(!is_null($id_provinsi)) $where .= "location_states.id_states = '$id_provinsi'";

        $cek = KecamatanModel::join('location_cities', 'location_cities.id_cities', '=', 'location_subdistrict.id_cities')
                    ->join('location_states', 'location_states.id_states', '=', 'location_cities.id_states')                   
                    ->whereRaw("1=1")                    
                    ->whereRaw($where)                    
                    ->distinct()
                    ->get(['location_states.states','location_states.id_states', 'location_cities.cities','location_subdistrict.*']);        
        $result = [
            'id_provinsi' => ($cek->count())?$cek->first()->id_states:"",            
            'provinsi' => ($cek->count())?$cek->first()->states:"",          
            'id_kabupaten' => ($cek->count())?$cek->first()->id_cities:"",          
            'kabupaten' => ($cek->count())?$cek->first()->cities:"",            
            'id_kecamatan' => ($cek->count())?$cek->first()->id_subdistrict:"",          
            'kecamatan' => ($cek->count())?$cek->first()->subdistrict:"",                        
        ];
        return $result;
    }    
}

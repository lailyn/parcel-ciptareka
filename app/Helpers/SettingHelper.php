<?php

use Illuminate\Support\Facades\DB;
use App\Models\KelurahanModel;
use App\Models\StatusModel;
use App\Models\HistoryModel;
use Carbon\Carbon;


if (!function_exists('no_plat')) {    
	function no_plat($no_plat){
    if($no_plat!=''){
        $angka = preg_replace("/[^0-9]/","",$no_plat);
        $huruf = explode($angka, $no_plat);
        $no_plat = $huruf[0]." ".$angka." ".$huruf[1];
    }

    return $no_plat;
	}
}
if (!function_exists('success_response')) {    
	function success_response($message='success',$data=[],$status_header=200) {		
		$data = ['message' => 'Hello, world!'];
		return response()->json($data, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		
		// return response()->json([
        //   'status' => 1,
        //   'message' => $message,
        //   'data' => $data
        // ], $status_header);
    }
}
if (!function_exists('fail_response')) {    
	function fail_response($message='failed',$data=[],$status_header=401) {
		return response()->json([
          'status' => 0,
          'message' => $message,
          'data' => $data
        ], $status_header);
    }
}
if (!function_exists('get_setting')) {    
	function get_setting($name) {
		$set = DB::table('setting')->where('name', $name)->first();        
		return (isset($set->value) ? $set->value : '');
	}
}

if (!function_exists('cariWilayah')) {    
	function cariWilayah($id_village) {		
		return $id_village;
	}
}
if (!function_exists('no_plat')) {    
	function no_plat($no_plat){
	    if($no_plat!=''){
	        $angka = preg_replace("/[^0-9]/","",$no_plat);
	        $huruf = explode($angka, $no_plat);
	        $no_plat = $huruf[0]." ".$angka." ".$huruf[1];
	        $no_plat = str_replace(" ", "", $no_plat);
	    }
	    return $no_plat;
	}
}

if (!function_exists('setMenu')) {    
	function setMenu($name) {		
		$id_user_type = session()->get('id_user_type');
	  $set = DB::table('user_type')->where('id', $id_user_type)->first();        
	  if($set) $user_type = $set->name;
			else $user_type = "";
		
		$cekAccess = DB::table('users')
			->join('user_access', 'users.id', '=', 'user_access.user_type_id')            
			->join('menu', 'menu.id', '=', 'user_access.menu_id')            
			->where("user_type_id",$id_user_type)
			->where("menu.menu_link",$name)
			->where("user_access.can_view",1)
			->select('user_access.*')
			->get();			  

		if(count($cekAccess) > 0 OR $user_type == 'Administrator') $akses = "";
		else $akses = "style='display:none;'";            
	  
		return $akses;
	}
}
if (!function_exists('setMsg')) {    
	function setMsg($msg,$type) {		
		return " 
			<div class=\"content\">
				<div class=\"alert alert-$type alert-dismissable\" role=\"alert\">
			<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
			  <span aria-hidden=\"true\">&times;</span>
			</button>
		  <p class=\"mb-0\">$msg</p>
		</div></div>";
	}
}
if (!function_exists('cekError')) {    
	function cekError($errors) {		
		$err="";
		if(count($errors)>0){

			foreach($errors->all() AS $error){
				$err.="<li>$error</li>";
			}
			return "
				<div class=\"content\">
					<div class=\"alert alert-danger\">
				<ul>
					$err
				</ul>
				</div>
			</div>
			";
		}		
	}
}

if (!function_exists('ubahRupiah')) {    
	function ubahRupiah($nominal)
	{
		$rupiah = str_replace(',', '', $nominal);		
		return $rupiah;
	}
}
if (!function_exists('tgl_indo')) {    
	function tgl_indo($tanggal){
    if($tanggal!='0000-00-00'){
        $bulan = array (
          1 =>   'Januari',
          'Februari',
          'Maret',
          'April',
          'Mei',
          'Juni',
          'Juli',
          'Agustus',
          'September',
          'Oktober',
          'November',
          'Desember'
        );
        $pecahkan = explode('-', $tanggal);
                
        return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
    }else{
        return $tanggal;
    }
  }
}
if (!function_exists('mata_uang_help')) {    
	function mata_uang_help($a){      
	if(is_numeric($a) AND $a != 0 AND $a != ""){
	  return number_format($a, 0, ',', '.');
	}else{
	  return $a;
	}
	}
}
if (!function_exists('cariWilayah')) {    
	function cariWilayah($id){      
		$cari = DB::table('location_village')->where('id_village', $id)->first();        
		if($cari) $kecamatan = $set->village;
			else $kecamatan = "";

	return $kecamatan;
	}
}
function generateRandomString($length = 4) {
	$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[random_int(0, $charactersLength - 1)];
	}
	return $randomString;
}
if (!function_exists('isSTTNumberExists')) {    
	function isSTTNumberExists($sttNumber) {
	$count = DB::table("pengiriman")->where('stt_number', $sttNumber)->count();
	return $count > 0;
	}
}
if (!function_exists('sttNumber')) {    
	function sttNumber($port_id){
		// $characters = '1234567890';
		// $charactersLength = strlen($characters);
		// $randomCode = '';
		// for ($i = 0; $i < $length; $i++) {
		// 	$randomCode .= $characters[random_int(0, $charactersLength - 1)];
		// }
		$cariKodePort = DB::table("cabang")->where("id",$port_id)->get("cabang.slug")->first()->slug;

		$tgl = date("Y-m");
		$query = DB::table("pengiriman")
				->whereRaw("LEFT(created_at,7)= :value",["value"=>$tgl])
				->orderBy("created_at","DESC")
				->groupBy("code")
				->selectRaw('max(RIGHT(code,5)) as kd_max')
				->limit(1);		
		$kd = "";
		if($query->count()>0){
			$isiData = $query->first();			
			$tmp = ((int)$isiData->kd_max)+1;
			$kd = sprintf("%05s", $tmp);			
		}else{
			$kd = "000001";
		}
		return date('ym').$cariKodePort.$kd;
		
  }
}

if (!function_exists('cariKode_manifest')) {    
	function cariKode_manifest($table,$kode,$asal,$tujuan){
		// JKTMDN1230701001	
		$tgl = date("Y-m-d");
		$tgl_kode = date("ymd");
		$query = DB::table($table)
				->whereRaw("LEFT(created_at,10)= :value",["value"=>$tgl])
				->orderBy("created_at","DESC")
				->groupBy("code")
				->selectRaw('max(RIGHT(code,3)) as kd_max')
				->limit(1);
		// dd($query);
		$kd = "";
		if($query->count()>0){
			$isiData = $query->first();			
			$tmp = ((int)$isiData->kd_max)+1;
			$kd = sprintf("%03s", $tmp);			
		}else{
			$kd = "001";
		}
		return $asal.$tujuan.$kode.$tgl_kode.$kd;
	}
}
if (!function_exists('cariKode_awb')) {    
	function cariKode_awb($table,$asal,$tujuan){
		// JKTMDN1230701001	
		$tgl = date("Y-m-d");
		$tgl_kode = date("ymd");
		$query = DB::table($table)
				->whereRaw("LEFT(created_at,10)= :value",["value"=>$tgl])
				->orderBy("created_at","DESC")
				->groupBy("code")
				->selectRaw('max(RIGHT(code,3)) as kd_max')
				->limit(1);
		// dd($query);
		$kd = "";
		if($query->count()>0){
			$isiData = $query->first();			
			$tmp = ((int)$isiData->kd_max)+1;
			$kd = sprintf("%02s", $tmp);			
		}else{
			$kd = "001";
		}
		return $asal.$tujuan."AWB".$tgl_kode.$kd;
	}
}
if (!function_exists('cariKode_helper')) {    
	function cariKode_helper($table,$kode=""){
		$tgl = date("Y-m");
		$query = DB::table($table)
				->whereRaw("LEFT(created_at,7)= :value",["value"=>$tgl])
				->orderBy("created_at","DESC")
				->groupBy("code")
				->selectRaw('max(RIGHT(code,6)) as kd_max')
				->limit(1);
		// dd($query);
		$kd = "";
		if($query->count()>0){
			$isiData = $query->first();			
			$tmp = ((int)$isiData->kd_max)+1;
			$kd = sprintf("%05s", $tmp);			
		}else{
			$kd = "000001";
		}
		return $kode.date('ds').$kd;
	}
}

if (!function_exists('simpanHistory')) {    
	function simpanHistory($codes, $manifestLintas_id, $kendaraan_id,$karyawan_id,$port_asal,$port_tujuan,$port_singgah,$tipe,$id_user=NULL){				
		try{
    		DB::beginTransaction();
    		$cek = HistoryModel::where("manifestLintas_id",$manifestLintas_id);
    		if($cek->count()>0){
    			$isiData = $cek->first();
    			$data = HistoryModel::find($isiData->id);
    		}else{
    			$data = new HistoryModel;
    		}			
			$data->awb_number = $codes;			
			$data->manifestLintas_id = $manifestLintas_id;			
			$data->tgl_proses = Carbon::now()->toDateString();
			$data->kendaraan_id = $kendaraan_id;
			$data->karyawan_id = $karyawan_id;
			$data->port_singgah = $port_singgah;
			$data->port_asal = $port_asal;
			$data->port_tujuan = $port_tujuan;
			$data->keterangan = "";
			$data->created_at = Carbon::now()->toDateTimeString();
			if($tipe=="pickup"){
				$data->pickup_at = Carbon::now()->toDateTimeString();
				$data->pickup_by = $id_user;
			}elseif($tipe=="penerimaan"){
				$data->penerimaan_at = Carbon::now()->toDateTimeString();
				$data->penerimaan_by = $id_user;
			}

			$data->save();
			DB::commit();
		}catch (\PDOException $e) {
		    DB::rollBack();
		}
	}
}
if (!function_exists('simpanStatus')) {    
	function simpanStatus($id,$waktu,$status,$ket=''){				
		try{
    		DB::beginTransaction();
    		$cek = StatusModel::where("pengiriman_id",$id)->where("status",$status);
    		if($cek->count()>0){
    			$isiData = $cek->first();
    			$data = StatusModel::find($isiData->id);
    		}else{
    			$data = new StatusModel;
    		}			
			$data->pengiriman_id = $id;
			$data->waktu = $waktu;
			$data->status = $status;
			$data->keterangan = $ket;
			$data->created_at = Carbon::now()->toDateTimeString();
			$data->save();
			DB::commit();
		}catch (\PDOException $e) {
		    DB::rollBack();
		}
	}
}
if (!function_exists('hapusStatus')) {    
	function hapusStatus($id,$status){				
		try{
    		DB::beginTransaction();
    		$cek = StatusModel::where("pengiriman_id",$id)->where("status",$status);
    		if($cek->count()>0){
    			$isiData = $cek->first();
    			$data = StatusModel::find($isiData->id);
    			$data->delete();
    			DB::commit();
    		}						
		}catch (\PDOException $e) {
		    DB::rollBack();
		}
	}
}
if (!function_exists('cariDaerah_helper')) {    
	function cariDaerah_helper($id,$tipe="kec",$spesifik=null){				
		if($tipe=="kec"){
			$tabel = "location_subdistrict.id_subdistrict";
		}elseif($tipe=="kab"){
			$tabel = "location_cities.id_cities";
		}elseif($tipe=="prov"){
			$tabel = "location_states.id_states";
		}elseif($tipe=="kel"){
			$tabel = "location_village.id_village";
		}
		$query = KelurahanModel::join('location_subdistrict', 'location_subdistrict.id_subdistrict', '=', 'location_village.subdistrict_id')
					->join('location_cities', 'location_cities.id_cities', '=', 'location_subdistrict.id_cities')
					->join('location_states', 'location_states.id_states', '=', 'location_cities.id_states')                   
					->where($tabel,"=",$id)					
					->distinct()
					->get(['location_states.states', 'location_cities.cities','location_subdistrict.*','location_village.village']);		
		// dd($query);
		$kd = "";		
		if($query->count()>0){
			$isiData = $query->first();
			if(!is_null($spesifik)){
				if($spesifik=="kel"){
					$kd = $isiData->village;
				}elseif($spesifik=="kec"){
					$kd = $isiData->subdistrict;
				}elseif($spesifik=="kab"){
					$kd = $isiData->states;
				}elseif($spesifik=="prov"){
					$kd = $isiData->states;
				}
			}else{
				$kd = $isiData->subdistrict.", ".$isiData->cities.", ".$isiData->states;
			}
		}
		return $kd;
	}
}
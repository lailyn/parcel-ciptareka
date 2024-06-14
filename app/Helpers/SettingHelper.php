<?php

use Illuminate\Support\Facades\DB;
use App\Models\KelurahanModel;
use App\Models\StatusModel;
use App\Models\HistoryModel;
use Carbon\Carbon;



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

function cariSelisih($tgl1,$tgl2=null){  	 
  $tgl1 = new DateTime($tgl1);
  if(is_null($tgl2)) $tgl2 = new DateTime(gmdate("Y-m-d", time() + 60 * 60 * 7));
 	else $tgl2 = new DateTime($tgl2);
  $d = $tgl2->diff($tgl1)->days + 1;    
  return $d;
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

function generateRandomString($length = 4) {
	$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[random_int(0, $charactersLength - 1)];
	}
	return $randomString;
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

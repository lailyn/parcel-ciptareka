<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\ManifestJemputModel;
use App\Models\KaryawanCakupanModel;
use App\Models\ManifestJemputDetailModel;
use App\Models\ManifestLintasModel;
use App\Models\ManifestLintasDetailModel;
use App\Models\ManifestAntarModel;
use App\Models\ManifestAntarDetailModel;
use App\Models\KaryawanModel;
use App\Models\CabangModel;
use App\Models\KendaraanDanPlatModel;
use App\Models\PengirimanModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use DB;
use Karyawan;

class KurirController extends Controller
{

  
  public function daftar_kurir(Request $request)
  {
    $data = array();
    $user = Auth('sanctum')->user();    
    $tgl = date("Y-m-d");
    $manifest = $request->manifest;
    $karyawan_id = $user->karyawan_id;      
    
    $cekCabang = KaryawanModel::join("jabatan","karyawan.jabatan_id","=","jabatan.id")->where("karyawan.id",$karyawan_id)
          ->get(["karyawan.*","jabatan.name AS jabatan"]);
    $port = $cekCabang->first()->cabang_id;
    $jabatan = $cekCabang->first()->jabatan;

    $whereRaw="1=1";$total=0;
    // if(!is_null($karyawan_id)) $whereRaw.=" AND manifestJemput.karyawan_id = '$karyawan_id'";        
    
    if($manifest=="jemput"){
      if(!is_null($port)) $whereRaw.=" AND pengiriman.port_asal = '$port'";    
      $cekData = ManifestJemputModel::join("karyawan","manifestJemput.karyawan_id","=","karyawan.id")
        ->join("jabatan","karyawan.jabatan_id","=","jabatan.id")
        ->leftJoin("manifestJemput_detail","manifestJemput.code","=","manifestJemput_detail.code")
        ->leftJoin("pengiriman","manifestJemput_detail.pengiriman_id","=","pengiriman.id")
        ->leftJoin("kendaraan","manifestJemput.kendaraan_id","=","kendaraan.id")
        ->where("manifestJemput_detail.status_utama","manifest_jemput_pickup")
        ->groupBy("karyawan.id")
        ->whereRaw($whereRaw)->get(['kendaraan.no_plat','manifestJemput.code','karyawan.name AS nama_karyawan','karyawan.id AS id_kurir','karyawan.no_hp']);    
    }elseif($manifest=="lintas"){
      if(!is_null($port)) $whereRaw.=" AND pengiriman.port_tujuan = '$port'";    
      $cekData = ManifestLintasModel::join("karyawan","manifestLintas.karyawan_id","=","karyawan.id")
        ->join("jabatan","karyawan.jabatan_id","=","jabatan.id")
        ->leftJoin("manifestLintas_detail","manifestLintas.code","=","manifestLintas_detail.code")
        ->leftJoin("pengiriman","manifestLintas_detail.pengiriman_id","=","pengiriman.id")
        ->leftJoin("kendaraan","manifestLintas.kendaraan_id","=","kendaraan.id")
        ->where("manifestLintas_detail.status_utama","manifest_lintas_pickup")
        ->groupBy("karyawan.id")
        ->whereRaw($whereRaw)->get(['kendaraan.no_plat','manifestLintas.code','karyawan.name AS nama_karyawan','karyawan.id AS id_kurir','karyawan.no_hp']);    
    }

    if($cekData->count()){  
      foreach ($cekData as $key => $ambilData){              
        $code = $ambilData->code;
        if(!is_null($code)){
          if($manifest=="jemput"){            
            $cekTotal = ManifestJemputModel::leftJoin("manifestJemput_detail","manifestJemput.code","=","manifestJemput_detail.code")
                    ->where("karyawan_id",$ambilData->id_kurir)->where("manifestJemput.status","locked")
                    ->where("manifestJemput_detail.status_utama","manifest_jemput_pickup");
            $total = $cekTotal->count();
          }elseif($manifest=="lintas"){            
            $cekTotal = ManifestLintasModel::leftJoin("manifestLintas_detail","manifestLintas.code","=","manifestLintas_detail.code")
                    ->where("karyawan_id",$ambilData->id_kurir)->where("manifestLintas.status","locked")
                    ->where("manifestLintas_detail.status_utama","manifest_lintas_pickup");
            $total = $cekTotal->count();
          }
        }
        $data[] = array(
          'id'=>$ambilData->id_kurir,
          'nama'=>$ambilData->nama_karyawan,
          'total_barang'=>$total,
          'nomor_plat'=>no_plat($ambilData->no_plat),
          'no_hp'=>$ambilData->no_hp
        );      
      }
      return response()->json(['status' => 1,'message' => 'success','data' => $data], 200);
    }

    return response()->json(['status' => 0,'message' => 'Data not found','data' => $data], 200);
    
  }
  public function daftar_manifest_jemput(Request $request)
  {
    $data = array();    
    $user = Auth('sanctum')->user();            
    $karyawan_id = $user->karyawan_id;    
    

    $cekCabang = KaryawanModel::join("jabatan","karyawan.jabatan_id","=","jabatan.id")->where("karyawan.id",$karyawan_id)
          ->get(["karyawan.*","jabatan.name AS jabatan"]);
    $port = $cekCabang->first()->cabang_id;
    $jabatan = $cekCabang->first()->jabatan;   

    $whereRaw="1=1";$total=0;
    if(strtolower($jabatan)=="admin port"){
      if(isset($request->id_kurir) && !is_null($request->id_kurir)) $whereRaw.= " AND manifestJemput.karyawan_id = '$request->id_kurir'";
      if(!is_null($port)) $whereRaw.=" AND pengiriman.port_asal = '$port' AND manifestJemput_detail.status_utama = 'manifest_jemput_pickup'";                
    }else{    
      if(!is_null($karyawan_id)) $whereRaw.=" AND manifestJemput.karyawan_id = '$karyawan_id' AND (manifestJemput_detail.status_utama = 'manifest_jemput' OR manifestJemput_detail.status_utama = 'manifest_jemput_pickup')";
    }
    // if(!is_null($tgl)) $whereRaw.=" AND manifestJemput.tgl_manifest = '$tgl'";    
    
    $cekData = ManifestJemputModel::join("karyawan","manifestJemput.karyawan_id","=","karyawan.id")
      ->leftJoin("outlet","manifestJemput.outlet_id","=","outlet.id")
      ->join("manifestJemput_detail","manifestJemput.code","=","manifestJemput_detail.code")      
      ->join("pengiriman","manifestJemput_detail.pengiriman_id","=","pengiriman.id")
      ->where("manifestJemput.status","locked")      
      ->groupBy("manifestJemput.code")
      ->whereRaw($whereRaw)->get(['manifestJemput_detail.pengiriman_id','manifestJemput.*','outlet.name AS nama_outlet','karyawan.name AS nama_karyawan','karyawan.id AS id_kurir','karyawan.no_hp']);    
    if($cekData->count()){  
      foreach ($cekData as $key => $ambilData){              
        $code = $ambilData->code;        
        $manifestDetail = ManifestJemputDetailModel::where("code",$code)->whereNot("status","penerimaan");
        $total = $manifestDetail->count();
        
        $data[] = array(
          'id'=>$ambilData->id,
          'no_manifest'=>$ambilData->code,
          'nama_outlet'=>$ambilData->nama_outlet,
          'total_barang'=>$total,
          'barang'=>$this->cekBarang($ambilData->code,$jabatan)
        );      
      }
      return response()->json(['status' => 1,'message' => 'success','data' => $data], 200);
    }    
    return response()->json(['status' => 0,'message' => 'Data not found','data' => $data], 200);
    
  }  
  public function cekBarang($code,$jabatan){
    $dataBarang = array();
    $whereRaw="1=1";
    if(strtolower($jabatan)=="admin port"){      
      $whereRaw.=" AND manifestJemput_detail.status_utama = 'manifest_jemput_pickup'";                      
    }else{
      $whereRaw.=" AND (manifestJemput_detail.status_utama = 'manifest_jemput' OR manifestJemput_detail.status_utama = 'manifest_jemput_pickup')";                
    }
    $cekData = ManifestJemputModel::join("karyawan","manifestJemput.karyawan_id","=","karyawan.id")    
      ->join("manifestJemput_detail","manifestJemput.code","=","manifestJemput_detail.code")
      ->join("pengiriman","manifestJemput_detail.pengiriman_id","=","pengiriman.id")      
      ->whereRaw($whereRaw)
      ->where("manifestJemput_detail.code",$code)->get(['pengiriman.*','manifestJemput_detail.status_utama AS sta']);              
    if($cekData){
      foreach($cekData AS $key => $row){
        $cariKel = cariDaerah_helper($row->id_kelurahan_penerima,'kel','kel');               
        $cariKec = cariDaerah_helper($row->id_kecamatan_penerima,'kec','kec');                       

        $dataBarang[] = array(
          "id"=>$row->id,
          "stt"=>$row->stt_number,
          "nama_penerima"=>$row->nama_penerima,
          "kelurahan"=> $cariKel,
          "kecamatan"=> $cariKec,
          "no_hp"=>$row->no_hp_penerima,
          "status"=>$row->sta
        );
      }
      return $dataBarang;
    }
    return response()->json(['status' => 0,'message' => 'Data not found','data' => $data], 200);
  } 	
  public function konfirmasi_penjemputan(Request $request){
    $user = Auth('sanctum')->user();    
    $karyawan_id = $user->karyawan_id;
    
    $json_result = file_get_contents('php://input');		    
    $data = json_decode($json_result, true);    
    $list_stt = $data['list_stt'];    
    $data = explode(",", $list_stt);    
    
    $cekData = ManifestJemputModel::join("karyawan","manifestJemput.karyawan_id","=","karyawan.id")
        ->join("manifestJemput_detail","manifestJemput.code","=","manifestJemput_detail.code")
        ->join("pengiriman","pengiriman.id","=","manifestJemput_detail.pengiriman_id")
        ->where("manifestJemput.karyawan_id",$karyawan_id)->where("manifestJemput.status","locked")
        ->groupBy("pengiriman.stt_number")
        ->get(['pengiriman.stt_number']);
    $dataStt = [];
    foreach($cekData AS $key => $row){      
      $dataStt[] = $row->stt_number;
    }        

    $isiList=0;
    $dataSttScan = [];
    if (count($data)) {      
      $listStt = $data;
      foreach ($listStt as $value) {
        $dataSttScan[] = $value;          
        $isiList++;
      }
    }
    

    $listYgSama = 0;
    $commonValues = array_intersect($dataStt, $dataSttScan);
    foreach ($commonValues as $value) {
      $listYgSama++;
      $pengirimanID = PengirimanModel::where("stt_number",$value)->get(['pengiriman.id'])->first()->id;

      $data = ManifestJemputDetailModel::where("pengiriman_id",$pengirimanID)->first();								      
      $ids = $data->pengiriman_id;
      $data->updated_at = $data->pickup_at = $waktu = Carbon::now()->toDateTimeString();
      $data->pickup_by = $user['id'];
      $data->status =  "pickup";
      $data->status_utama =  "manifest_jemput_pickup";
      $data->save();             	

      
      $cekKaryawan = DB::table("karyawan")->where("id",$karyawan_id);
      $nama_karyawan = ($cekKaryawan->count()>0)?$cekKaryawan->first()->name:"";												

      simpanStatus($ids,$waktu,"manifest_jemput_pickup","Paket telah diserahkan ke kurir ".$nama_karyawan." pada ".$waktu);
    }

    $dataMsg[] = array(
      'total_item_scan'=>$isiList,
      'stt_number_yg_sesuai'=>$listYgSama,      
    );
    return response()->json(['status' => 1,'message' => 'success','data' => $dataMsg], 200);
  }
  public function konfirmasi_lintas(Request $request){
    $user = Auth('sanctum')->user();    
    $karyawan_id = $user->karyawan_id;
    
    $json_result = file_get_contents('php://input');        
    $data = json_decode($json_result, true);    
    $list_stt = $data['list_stt'];    
    $data = explode(",", $list_stt);    
    
    $cekData = ManifestLintasModel::join("karyawan","manifestLintas.karyawan_id","=","karyawan.id")
        ->join("manifestLintas_detail","manifestLintas.code","=","manifestLintas_detail.code")
        ->join("pengiriman","pengiriman.id","=","manifestLintas_detail.pengiriman_id")
        ->where("manifestLintas.karyawan_id",$karyawan_id)->where("manifestLintas.status","locked")
        ->groupBy("pengiriman.stt_number")
        ->get(['pengiriman.stt_number']);
    $dataStt = [];
    foreach($cekData AS $key => $row){      
      $dataStt[] = $row->stt_number;
    }        

    $isiList=0;
    $dataSttScan = [];
    if (count($data)) {      
      $listStt = $data;
      foreach ($listStt as $value) {
        $dataSttScan[] = $value;          
        $isiList++;
      }
    }
    

    $listYgSama = 0;
    $commonValues = array_intersect($dataStt, $dataSttScan);
    foreach ($commonValues as $value) {
      $listYgSama++;
      $pengirimanID = PengirimanModel::where("stt_number",$value)->get(['pengiriman.id'])->first()->id;

      $data = ManifestLintasDetailModel::where("pengiriman_id",$pengirimanID)->first();                     
      $ids = $data->pengiriman_id;
      $data->updated_at = $data->pickup_at = $waktu = Carbon::now()->toDateTimeString();
      $data->pickup_by = $user['id'];
      $data->status =  "pickup";
      $data->status_utama =  "manifest_lintas_pickup";
      $data->save();              

      
      $cekKaryawan = DB::table("karyawan")->where("id",$karyawan_id);
      $nama_karyawan = ($cekKaryawan->count()>0)?$cekKaryawan->first()->name:"";                        

      simpanStatus($ids,$waktu,"manifest_lintas_pickup","Paket telah diserahkan ke kurir ".$nama_karyawan." pada ".$waktu);
    }

    $dataMsg[] = array(
      'total_item_scan'=>$isiList,
      'stt_number_yg_sesuai'=>$listYgSama,      
    );
    return response()->json(['status' => 1,'message' => 'success','data' => $dataMsg], 200);
  }
  public function penerimaan_paket_jemput(Request $request){
    $user = Auth('sanctum')->user();    

    $json_result = file_get_contents('php://input');
    $datas = json_decode($json_result, true);    
    $list_stt = $datas['list_stt'];    
    $data = explode(",", $list_stt);    

    $karyawan_id = $datas['id_kurir'];
    $user_id_kurir = User::where("karyawan_id",$karyawan_id)->first()->id;
        
    $cekData = ManifestJemputModel::join("karyawan","manifestJemput.karyawan_id","=","karyawan.id")
        ->join("manifestJemput_detail","manifestJemput.code","=","manifestJemput_detail.code")
        ->join("pengiriman","pengiriman.id","=","manifestJemput_detail.pengiriman_id")        
        ->where("manifestJemput_detail.pickup_by",$user_id_kurir)->where("manifestJemput_detail.status","pickup")
        ->groupBy("pengiriman.stt_number")
        ->get(['pengiriman.stt_number']);
    $dataStt = [];
    foreach($cekData AS $key => $row){      
      $dataStt[] = $row->stt_number;
    }        
    // dd($dataStt);
    $isiList=0;
    if (count($data)) {
      $listStt = $data;
      foreach ($listStt as $value) {
        $dataSttScan[] = $value;          
        $isiList++;
      }
    }

    $listYgSama = 0;
    $commonValues = array_intersect($dataStt, $dataSttScan);
    foreach ($commonValues as $value) {
      $listYgSama++;
      $pengirimanID = PengirimanModel::where("stt_number",$value)->get(['pengiriman.id'])->first()->id;
      
      $data = ManifestJemputDetailModel::where("pengiriman_id",$pengirimanID)->first();								      
      $codes = $data->code;
      $ids = $data->pengiriman_id;
      $data->updated_at = $data->warehouse_at = $waktu = Carbon::now()->toDateTimeString();
      $data->warehouse_by = $user['id'];
      $data->status =  "penerimaan";
      $data->status_utama =  "manifest_jemput_penerimaan";
      $data->save();             											

      $cekManifest = ManifestJemputModel::where("code",$codes)->first();				
      $outlet_id = $cekManifest->outlet_id;
      $cekPort = DB::table("outlet")->join("cabang","cabang.id","=","outlet.port_id")
            ->where("outlet.id",$outlet_id)->get(['cabang.name AS port']);
      $port = ($cekPort->count()>0)?$cekPort->first()->port:"";												

      simpanStatus($ids,$waktu,"manifest_jemput_penerimaan","Paket telah sampai di Port Asal ".$port." pada ".$waktu);      

    }

    $dataMsg[] = array(
      'total_item_scan'=>$isiList,
      'stt_number_yg_sesuai'=>$listYgSama,      
    );
    return response()->json(['status' => 1,'message' => 'success','data' => $dataMsg], 200);
  }
  public function penerimaan_paket_lintas(Request $request){
    $user = Auth('sanctum')->user();    

    $json_result = file_get_contents('php://input');
    $datas = json_decode($json_result, true);    
    $list_stt = $datas['list_stt'];    
    $data = explode(",", $list_stt);    

    $karyawan_id = $datas['id_kurir'];
    $user_id_kurir = User::where("karyawan_id",$karyawan_id)->first()->id;
        
    $cekData = ManifestLintasModel::join("karyawan","manifestLintas.karyawan_id","=","karyawan.id")
        ->join("manifestLintas_detail","manifestLintas.code","=","manifestLintas_detail.code")
        ->join("pengiriman","pengiriman.id","=","manifestLintas_detail.pengiriman_id")
        ->where("manifestLintas_detail.status","pickup")
        ->where("manifestLintas_detail.pickup_by",$user_id_kurir)
        ->groupBy("pengiriman.stt_number")
        ->get(['pengiriman.stt_number']);
    $dataStt = [];
    foreach($cekData AS $key => $row){      
      $dataStt[] = $row->stt_number;
    }        
    
    $isiList=0;
    if (count($data)) {
      $listStt = $data;
      foreach ($listStt as $value) {
        $dataSttScan[] = $value;          
        $isiList++;
      }
    }

    $listYgSama = 0;
    $commonValues = array_intersect($dataStt, $dataSttScan);
    foreach ($commonValues as $value) {
      $listYgSama++;
      $pengirimanID = PengirimanModel::where("stt_number",$value)->get(['pengiriman.id'])->first()->id;
      
      $data = ManifestLintasDetailModel::where("pengiriman_id",$pengirimanID)->first();                     
      $codes = $data->code;
      $ids = $data->pengiriman_id;
      $data->updated_at = $data->warehouse_at = $waktu = Carbon::now()->toDateTimeString();
      $data->warehouse_by = $user['id'];
      $data->status =  "penerimaan";
      $data->status_utama =  "manifest_lintas_penerimaan";
      $data->save();                                  

      $cekManifest = ManifestLintasModel::where("code",$codes)->first();        
      $outlet_id = $cekManifest->outlet_id;
      $cekPort = DB::table("outlet")->join("cabang","cabang.id","=","outlet.port_id")
            ->where("outlet.id",$outlet_id)->get(['cabang.name AS port']);
      $port = ($cekPort->count()>0)?$cekPort->first()->port:"";                       

      simpanStatus($ids,$waktu,"manifest_lintas_penerimaan","Paket telah sampai di Port Tujuan ".$port." pada ".$waktu);      

    }

    $dataMsg[] = array(
      'total_item_scan'=>$isiList,
      'stt_number_yg_sesuai'=>$listYgSama,      
    );
    return response()->json(['status' => 1,'message' => 'success','data' => $dataMsg], 200);
  }
  public function cekJangkauan($code){
    $data = [];
    $cekData = ManifestAntarModel::join("manifestAntar_detail","manifestAntar.code","=","manifestAntar_detail.code")
      ->join("pengiriman","manifestAntar_detail.pengiriman_id","=","pengiriman.id")
      ->where("manifestAntar.code",$code)
      ->get(['pengiriman.id_kecamatan_penerima'])->unique('id_kecamatan_penerima');    
    if($cekData->count()){  
      foreach ($cekData as $key => $ambilData){              
        $kecamatan = cariDaerah_helper($ambilData->id_kecamatan_penerima,'kec','kec');                                                                                    
        $data[] = $kecamatan;
      }                    
    }
    return $data;
  }
  public function daftar_manifest_antar(Request $request)
  {
    $data = array();
    $tgl = date("Y-m-d");
    $user = Auth('sanctum')->user();    
    $karyawan_id = $user->karyawan_id;
    $outlet_id = $request->outlet_id;
    $whereRaw="(manifestAntar_detail.status_utama = 'manifest_antar' OR manifestAntar_detail.status_utama = 'manifest_antar_pickup') ";$total=0;
    if(!is_null($karyawan_id)) $whereRaw.=" AND manifestAntar.karyawan_id = '$karyawan_id'";        
    
    $cekData = ManifestAntarModel::join("karyawan","manifestAntar.karyawan_id","=","karyawan.id")      
      ->join("manifestAntar_detail","manifestAntar.code","=","manifestAntar_detail.code")            
      ->groupBy("manifestAntar.code")
      ->whereRaw($whereRaw)->get(['manifestAntar_detail.pengiriman_id','manifestAntar.*','karyawan.name AS nama_karyawan','karyawan.id AS id_kurir','karyawan.no_hp']);    
    if($cekData->count()){  
      foreach ($cekData as $key => $ambilData){              
        $code = $ambilData->code;        
        $manifestDetail = ManifestAntarDetailModel::where("code",$code);
        $total = $manifestDetail->count();        

        $data[] = array(
          'id'=>$ambilData->id,
          'no_manifest'=>$ambilData->code,
          'kecamatan'=>$this->cekJangkauan($code),
          'total_barang'=>$total,
          'barang'=>$this->cekBarangAntar($ambilData->code)
        );      
      }
      return response()->json(['status' => 1,'message' => 'success','data' => $data], 200);
    }
    return response()->json(['status' => 0,'message' => 'Data not found','data' => $data], 200);    
  }

  public function daftar_manifest_lintas(Request $request)
  {
    $data = array();    
    $user = Auth('sanctum')->user();            
    $karyawan_id = $user->karyawan_id;    
    

    $cekCabang = KaryawanModel::join("jabatan","karyawan.jabatan_id","=","jabatan.id")->where("karyawan.id",$karyawan_id)
          ->get(["karyawan.*","jabatan.name AS jabatan"]);
    $port = $cekCabang->first()->cabang_id;
    $jabatan = $cekCabang->first()->jabatan;

    $whereRaw="1=1";$total=0;
    if(strtolower($jabatan)=="admin port"){
      if(isset($request->id_kurir) && !is_null($request->id_kurir)) $whereRaw.= " AND manifestLintas.karyawan_id = '$request->id_kurir'";
      if(!is_null($port)) $whereRaw.=" AND pengiriman.port_tujuan = '$port' AND manifestLintas_detail.status_utama = 'manifest_lintas_pickup'";                
    }else{    
      if(!is_null($karyawan_id)) $whereRaw.=" AND manifestLintas.karyawan_id = '$karyawan_id' AND (manifestLintas_detail.status_utama = 'manifest_lintas' OR manifestLintas_detail.status_utama = 'manifest_lintas_pickup')";
    }
    // if(!is_null($tgl)) $whereRaw.=" AND manifestLintas.tgl_manifest = '$tgl'";    
    
    $cekData = ManifestLintasModel::join("karyawan","manifestLintas.karyawan_id","=","karyawan.id")      
      ->join("manifestLintas_detail","manifestLintas.code","=","manifestLintas_detail.code")      
      ->join("pengiriman","manifestLintas_detail.pengiriman_id","=","pengiriman.id")
      ->where("manifestLintas.status","locked")      
      ->groupBy("manifestLintas.code")
      ->whereRaw($whereRaw)->get(['manifestLintas_detail.pengiriman_id','manifestLintas.*','karyawan.name AS nama_karyawan','karyawan.id AS id_kurir','karyawan.no_hp']);    
    if($cekData->count()){  
      foreach ($cekData as $key => $ambilData){              
        $code = $ambilData->code;        
        $manifestDetail = ManifestLintasDetailModel::where("code",$code)->whereNot("status","penerimaan");
        $total = $manifestDetail->count();
        
        $port_asal = CabangModel::find($ambilData->port_asal)->name;
        $port_tujuan = CabangModel::find($ambilData->port_tujuan)->name;

        $data[] = array(
          'id'=>$ambilData->id,
          'no_manifest'=>$ambilData->code,
          'port_asal'=>$port_asal,
          'port_tujuan'=>$port_tujuan,
          'total_barang'=>$total,
          'barang'=>$this->cekBarangLintas($ambilData->code,$jabatan)
        );      
      }
      return response()->json(['status' => 1,'message' => 'success','data' => $data], 200);
    }    
    return response()->json(['status' => 0,'message' => 'Data not found','data' => $data], 200);
  } 
  public function cekBarangLintas($code,$jabatan){
    $dataBarang = array();
    $whereRaw="1=1";
    if(strtolower($jabatan)=="admin port"){      
      $whereRaw.=" AND manifestLintas_detail.status_utama = 'manifest_lintas_pickup'";                      
    }else{
      $whereRaw.=" AND (manifestLintas_detail.status_utama = 'manifest_lintas' OR manifestLintas_detail.status_utama = 'manifest_lintas_pickup')";                
    }
    $cekData = ManifestLintasModel::join("karyawan","manifestLintas.karyawan_id","=","karyawan.id")    
      ->join("manifestLintas_detail","manifestLintas.code","=","manifestLintas_detail.code")
      ->join("pengiriman","manifestLintas_detail.pengiriman_id","=","pengiriman.id")      
      ->whereRaw($whereRaw)
      ->where("manifestLintas_detail.code",$code)->get(['pengiriman.*','manifestLintas_detail.status_utama AS sta']);              
    if($cekData){
      foreach($cekData AS $key => $row){
        $cariKel = cariDaerah_helper($row->id_kelurahan_penerima,'kel','kel');               
        $cariKec = cariDaerah_helper($row->id_kecamatan_penerima,'kec','kec');                       

        $dataBarang[] = array(
          "id"=>$row->id,
          "stt"=>$row->stt_number,
          "nama_penerima"=>$row->nama_penerima,
          "kelurahan"=> $cariKel,
          "kecamatan"=> $cariKec,
          "no_hp"=>$row->no_hp_penerima,
          "status"=>$row->sta
        );
      }
      return $dataBarang;
    }
    return response()->json(['status' => 0,'message' => 'Data not found','data' => $data], 200);
  }  
  public function cekBarangAntar($code){
    $dataBarang = array();
    // $cekData = PengirimanModel::where("id",$code)->get();    
    $whereRaw = "(manifestAntar_detail.status_utama='manifest_antar' OR manifestAntar_detail.status_utama='manifest_antar_pickup')";
    $cekData = ManifestAntarModel::join("karyawan","manifestAntar.karyawan_id","=","karyawan.id")    
      ->join("manifestAntar_detail","manifestAntar.code","=","manifestAntar_detail.code")
      ->join("pengiriman","manifestAntar_detail.pengiriman_id","=","pengiriman.id")            
      ->where("manifestAntar.code",$code)->whereRaw($whereRaw)
      ->get(['pengiriman.*','manifestAntar_detail.status_utama AS sta']);              
    if($cekData){
      foreach($cekData AS $key => $row){
        $cariKel = cariDaerah_helper($row->id_kelurahan_penerima,'kel','kel');               
        $cariKec = cariDaerah_helper($row->id_kecamatan_penerima,'kec','kec');               
        $dataBarang[] = array(
          "id"=>$row->id,
          "stt"=>$row->stt_number,
          "nama_penerima"=>$row->nama_penerima,
          "kelurahan"=> $cariKel,
          "kecamatan"=> $cariKec,
          "no_hp"=>$row->no_hp_penerima,
          "status"=>$row->sta??""
        );
      }
      return $dataBarang;
    }
    return response()->json(['status' => 0,'message' => 'Data not found','data' => $data], 200);
  }
  public function antar_barang(Request $request){        		
    $cekSTT = PengirimanModel::where("stt_number",$request->id)->first()->id;
    if($request->status_terima==1){
      $data = ManifestAntarDetailModel::where("pengiriman_id",$cekSTT)->first();    

      if($data->status_utama=="paket_sampai_dipenerima"){
        return response()->json(['status' => 0,'message' => 'failed','data' => []], 200);           
        exit();
      }

      $data->penerima = $penerima = $request->penerima;
      $data->diterima_at = $data->updated_at = Carbon::now()->toDateTimeString();
      $data->status_utama = "paket_sampai_dipenerima";

      $objectName = 'bukti';
      $request->validate([$objectName => 'required|mimes:jpeg,jpg,png|max:1000']);    
      if($request->file()) {
        $fileName = time().'_'.$request->$objectName->getClientOriginalName();        
        $upload = $request->$objectName->move(public_path('ima49es/delivery'), $fileName);        
        $data->gambar = $fileName;                
      }
      $data->save();

      
      $data2 = PengirimanModel::find($cekSTT);
      $data2->updated_at = $data2->tgl_delivered = $waktu = Carbon::now()->toDateTimeString();
      $data2->status = "paket_sampai_dipenerima";
      $data2->save();
      
      simpanStatus($cekSTT,$waktu,"paket_sampai_dipenerima","Paket sudah diterima ".$penerima);
    }else{
      $data = ManifestAntarDetailModel::where("pengiriman_id",$cekSTT)->first();
      $data->alasan = $alasan = $request->keterangan;
      $data->ditunda_at = $waktu = $data->updated_at = Carbon::now()->toDateTimeString();
      $data->status_utama = "paket_ditunda_penyerahan";
      
      $data->save();
      
      simpanStatus($cekSTT,$waktu,"paket_ditunda_penyerahan","Paket belum diterima karena alasan: ".$alasan);
    }
		return response()->json(['status' => 1,'message' => 'success','data' => []], 200);           
  }  
  public function konfirmasi_pengantaran(Request $request){
    $user = Auth('sanctum')->user();    
    $karyawan_id = $user->karyawan_id;
    
    $json_result = file_get_contents('php://input');
    $data = json_decode($json_result, true);    
    $list_stt = $data['list_stt'];    
    $data = explode(",", $list_stt);    

    $cekData = ManifestAntarModel::join("karyawan","manifestAntar.karyawan_id","=","karyawan.id")
        ->join("manifestAntar_detail","manifestAntar.code","=","manifestAntar_detail.code")
        ->join("pengiriman","pengiriman.id","=","manifestAntar_detail.pengiriman_id")
        ->where("manifestAntar.karyawan_id",$karyawan_id)->where("manifestAntar.status","locked")
        ->groupBy("pengiriman.stt_number")
        ->get(['pengiriman.stt_number']);
    $dataStt = [];
    foreach($cekData AS $key => $row){      
      $dataStt[] = $row->stt_number;
    }        
    
    $isiList=0;
    if (count($data)) {
      $listStt = $data;        
      foreach ($listStt as $value) {
        $dataSttScan[] = $value;          
        $isiList++;
      }
    }

    $listYgSama = 0;
    $commonValues = array_intersect($dataStt, $dataSttScan);
    foreach ($commonValues as $value) {
      $listYgSama++;
      $pengirimanID = PengirimanModel::where("stt_number",$value)->get(['pengiriman.id'])->first()->id;
      
      $data = ManifestAntarDetailModel::where("pengiriman_id",$pengirimanID)->first();								
      $codes = $data->code;
      $ids = $data->pengiriman_id;
      $data->updated_at = $data->pickup_at = $waktu = Carbon::now()->toDateTimeString();
      $data->pickup_by = $user['id'];
      $data->status =  "penyerahan";
      $data->status_utama =  "manifest_antar_pickup";
      $data->save();             											

      $cekManifest = ManifestAntarModel::where("code",$codes)->first();				
      $outlet_id = $cekManifest->outlet_id;
      $cekPort = DB::table("outlet")->join("cabang","cabang.id","=","outlet.port_id")
            ->where("outlet.id",$outlet_id)->get(['cabang.name AS port']);
      $port = ($cekPort->count()>0)?$cekPort->first()->port:"";												

      simpanStatus($ids,$waktu,"manifest_antar_pickup","Paket telah dibawa oleh kurir pada ".$waktu);
    }

    $dataMsg[] = array(
      'total_item_scan'=>$isiList,
      'stt_number_yg_sesuai'=>$listYgSama,      
    );
    return response()->json(['status' => 1,'message' => 'success','data' => $dataMsg], 200);
  }
  public function detail_paket($id){
    
    $cekBarang = PengirimanModel::where("stt_number",$id);
    if($cekBarang->count()){
      $amb = $cekBarang->first();
      $konstanta = get_setting("konstanta");
		  $cekDimensi = floor(($amb->tinggi * $amb->panjang * $amb->lebar) / $konstanta);
      $cekTerkirim = ManifestAntarDetailModel::where("pengiriman_id",$amb->id)->first();
      $gambar = "";
      if($cekTerkirim){
        if(!is_null($cekTerkirim->gambar)){
          $gambar =  env('APP_URL')."/public/ima49es/delivery/".$cekTerkirim->gambar;
        }
      }
      $dataMsg = array(
        "id" => $amb->id,
        "stt" => $amb->stt_number,
        "nama_pengirim" => $amb->nama_pengirim,
        "alamat_pengirim" => $amb->alamat_pengirim,
        "no_hp_pengirim" => $amb->no_hp_pengirim,
        "nama_penerima" => $amb->nama_penerima,
        "alamat_penerima" => $amb->alamat_penerima,
        "no_hp_penerima" => $amb->no_hp_penerima,
        "biaya_kirim" => (int)$amb->total_tagihan,
        "cod" => ($amb->metode_bayar=='on')?false:true,
        "asuransi" => (int)$amb->biaya_asuransi,
        "daftar_barang" => $amb->isi_paket,
        "berat" => ceil($amb->berat/1000),
        "dimensi" => $cekDimensi,
        "waktu_pengiriman" => $amb->created_at,
        "waktu_pesanan_selesai" => $amb->tgl_delivered,
        "bukti_antar" => $gambar,
        "tracking" => $this->tracking($amb->id),
      );        
      return response()->json(['status' => 1,'message' => 'success','data' => $dataMsg], 200);
    }else{
      return response()->json(['status' => 0,'message' => 'Data not found','data' => $data], 200);
    }
  }
  public function tracking($id){
    $data = [];
    $timelineStatus = DB::table("table_status")->where("pengiriman_id",$id)->orderby('updated_at','DESC')->get();
    foreach($timelineStatus AS $track){
      $judul = ucfirst(str_replace("_"," ",$track->status));      

      if($track->status=="paket_ditunda_penyerahan") $tipe = "warning";
        else $tipe = "normal";
      $data[] = array(        
        "judul" => $judul,
        "deskripsi" => $track->keterangan,
        "waktu" => $track->waktu,
        "icon" => $track->status,
        "tipe" => $tipe,
        "latitude" => -6.206532,
        "longitude" => 106.765656,
      );
    }
    return $data;
  }
  public function riwayat_lintas_port(Request $request)
  {
    $data = array();
    $tgl_mulai = $request->tanggal_mulai;
    $tgl_akhir = $request->tanggal_akhir;
    $user = Auth('sanctum')->user();    
    $karyawan_id = $user->karyawan_id;
    $cariOutlet = KaryawanModel::find($karyawan_id);     
    $cabang_id = $cariOutlet->cabang_id;

    if(is_null($cabang_id)){
      echo response_fail($message = "Outlet Karyawan tidak ditemukan", [], $status_header = 200);
      exit();
    }

    $whereRaw="1=1";$total=0;
    if(!is_null($cabang_id)) $whereRaw.=" AND manifestLintas.port_tujuan = '$cabang_id'";    
    if(!is_null($tgl_mulai)) $whereRaw.=" AND manifestLintas.tgl_manifest BETWEEN '$tgl_mulai' AND '$tgl_akhir'";
    
    $cekData = ManifestLintasModel::join("karyawan","manifestLintas.karyawan_id","=","karyawan.id")      
      ->join("manifestLintas_detail","manifestLintas.code","=","manifestLintas_detail.code")
      ->join("kendaraan","manifestLintas.kendaraan_id","=","kendaraan.id")
      ->orderBy("manifestLintas_detail.warehouse_at","DESC")
      ->groupBy("manifestLintas.karyawan_id")
      ->where("manifestLintas_detail.status_utama","manifest_lintas_penerimaan")
      ->whereRaw($whereRaw)->get(['karyawan.no_hp','kendaraan.no_plat','manifestLintas_detail.pengiriman_id','manifestLintas.*','karyawan.name AS nama_karyawan','karyawan.id AS id_kurir','karyawan.no_hp']);    
    if($cekData->count()){  
      foreach ($cekData as $key => $ambilData){              
        $code = $ambilData->code;        
        $cekData = ManifestLintasModel::join("karyawan","manifestLintas.karyawan_id","=","karyawan.id")      
          ->join("manifestLintas_detail","manifestLintas.code","=","manifestLintas_detail.code")
          ->join("kendaraan","manifestLintas.kendaraan_id","=","kendaraan.id")          
          ->where("manifestLintas_detail.status_utama","manifest_lintas_penerimaan")
          ->whereRaw($whereRaw)->get(['karyawan.no_hp','kendaraan.no_plat','manifestLintas_detail.pengiriman_id','manifestLintas.*','karyawan.name AS nama_karyawan','karyawan.id AS id_kurir','karyawan.no_hp']);    
        $total = $cekData->count();

        // dd($cekData);
        
        $data[] = array(
          'id_kurir'=>$ambilData->karyawan_id,
          'nama'=>$ambilData->nama_karyawan,
          'total_barang'=>$total,
          'nomor_plat'=>$ambilData->no_plat,
          'no_hp'=>$ambilData->no_hp,                   
          'manifest'=>$this->daftarManifestRiwayatLintas($ambilData->karyawan_id,$tgl_mulai,$tgl_akhir)
        );      
      }
      return response()->json(['status' => 1,'message' => 'success','data' => $data], 200);
    }
    return response()->json(['status' => 0,'message' => 'Data not found','data' => $data], 200);  
  }
  public function daftarManifestRiwayatLintas($karyawan_id,$tgl_mulai,$tgl_akhir)
  {
    $data = array();    
    $whereRaw="1=1";$total=0;
    if(!is_null($karyawan_id)) $whereRaw.=" AND manifestLintas.karyawan_id = '$karyawan_id'";            
    if(!is_null($tgl_mulai)) $whereRaw.=" AND manifestLintas.tgl_manifest BETWEEN '$tgl_mulai' AND '$tgl_akhir'";

    $cekData = ManifestLintasModel::join("karyawan","manifestLintas.karyawan_id","=","karyawan.id")
      ->join("manifestLintas_detail","manifestLintas.code","=","manifestLintas_detail.code")
      ->leftJoin("pengiriman","manifestLintas_detail.pengiriman_id","=","pengiriman.id")
      ->leftJoin("outlet","pengiriman.outlet_id","=","outlet.id")      
      ->groupBy("manifestLintas.code")
      ->where("manifestLintas_detail.status_utama","manifest_lintas_penerimaan")
      ->whereRaw($whereRaw)->get(['pengiriman.port_asal','pengiriman.port_tujuan','manifestLintas_detail.pengiriman_id','manifestLintas.*','outlet.name AS nama_outlet','karyawan.name AS nama_karyawan','karyawan.id AS id_kurir','karyawan.no_hp']);    
    if($cekData->count()){  
      foreach ($cekData as $key => $ambilData){              
        $code = $ambilData->code;  
        $cekData = ManifestLintasModel::join("karyawan","manifestLintas.karyawan_id","=","karyawan.id")    
          ->join("manifestLintas_detail","manifestLintas.code","=","manifestLintas_detail.code")
          ->join("pengiriman","manifestLintas_detail.pengiriman_id","=","pengiriman.id")
          ->where("manifestLintas_detail.status_utama","manifest_lintas_penerimaan")
          ->where("manifestLintas_detail.code",$code)->get(['pengiriman.*']);                        
        $total = $cekData->count();
        
        $cariPortAsal = DB::table("cabang")->where("id",$ambilData->port_asal)->first()->name;
        $cariPortTujuan = DB::table("cabang")->where("id",$ambilData->port_tujuan)->first()->name;

        $data[] = array(
          'id'=>$ambilData->id,
          'no_manifest'=>$ambilData->code,
          'port_asal'=>$cariPortAsal,
          'port_tujuan'=>$cariPortTujuan,
          'total_barang'=>$total,
          'barang'=>$this->cekBarangRiwayatLintasDetail($code)
        );      
      }
      return $data;
    }
    return response()->json(['status' => 0,'message' => 'Data not found','data' => $data], 200);
  }
  public function cekBarangRiwayatLintasDetail($code){
    $dataBarang = array();
    $cekData = ManifestLintasModel::join("karyawan","manifestLintas.karyawan_id","=","karyawan.id")    
      ->join("manifestLintas_detail","manifestLintas.code","=","manifestLintas_detail.code")
      ->join("pengiriman","manifestLintas_detail.pengiriman_id","=","pengiriman.id")
      ->where("manifestLintas_detail.status_utama","manifest_lintas_penerimaan")
      ->where("manifestLintas_detail.code",$code)->get(['pengiriman.*','manifestLintas_detail.status_utama']);          
    if($cekData){
      foreach($cekData AS $key => $row){
        $cariKel = cariDaerah_helper($row->id_kelurahan_penerima,'kel','kel');               
        $cariKec = cariDaerah_helper($row->id_kecamatan_penerima,'kec','kec');               
        $dataBarang[] = array(
          "id"=>$row->id,
          "stt"=>$row->stt_number,
          "nama_penerima"=>$row->nama_penerima,
          "kelurahan"=> $cariKel,
          "kecamatan"=> $cariKec,
          "no_hp"=>$row->no_hp_penerima,
          "status"=> $row->status_utama??"",
        );
      }
      return $dataBarang;
    }
    return response()->json(['status' => 0,'message' => 'Data not found','data' => $data], 200);
  }


  public function riwayat_jemput_port(Request $request)
  {
    $data = array();
    $tgl_mulai = $request->tanggal_mulai;
    $tgl_akhir = $request->tanggal_akhir;
    $user = Auth('sanctum')->user();    
    $karyawan_id = $user->karyawan_id;
    $cariOutlet = KaryawanModel::find($karyawan_id);     
    $cabang_id = $cariOutlet->cabang_id;

    if(is_null($cabang_id)){
      echo response_fail($message = "Outlet Karyawan tidak ditemukan", [], $status_header = 200);
      exit();
    }

    $whereRaw="1=1";$total=0;
    if(!is_null($cabang_id)) $whereRaw.=" AND cabang.id = '$cabang_id'";    
    if(!is_null($tgl_mulai)) $whereRaw.=" AND manifestJemput.tgl_manifest BETWEEN '$tgl_mulai' AND '$tgl_akhir'";
    
    $cekData = ManifestJemputModel::join("karyawan","manifestJemput.karyawan_id","=","karyawan.id")
      ->leftJoin("outlet","manifestJemput.outlet_id","=","outlet.id")
      ->join("cabang","outlet.port_id","=","cabang.id")
      ->join("manifestJemput_detail","manifestJemput.code","=","manifestJemput_detail.code")
      ->join("kendaraan","manifestJemput.kendaraan_id","=","kendaraan.id")
      ->groupBy("manifestJemput.karyawan_id")
      ->orderBy("manifestJemput_detail.warehouse_at","DESC")
      ->where("manifestJemput_detail.status_utama","manifest_jemput_penerimaan")
      ->whereRaw($whereRaw)->get(['karyawan.no_hp','kendaraan.no_plat','manifestJemput_detail.pengiriman_id','manifestJemput.*','outlet.name AS nama_outlet','karyawan.name AS nama_karyawan','karyawan.id AS id_kurir','karyawan.no_hp']);    
    if($cekData->count()){  
      foreach ($cekData as $key => $ambilData){              
        $code = $ambilData->code;                        
        $cekData = ManifestJemputModel::join("karyawan","manifestJemput.karyawan_id","=","karyawan.id")
          ->leftJoin("outlet","manifestJemput.outlet_id","=","outlet.id")
          ->join("cabang","outlet.port_id","=","cabang.id")
          ->join("manifestJemput_detail","manifestJemput.code","=","manifestJemput_detail.code")
          ->join("kendaraan","manifestJemput.kendaraan_id","=","kendaraan.id")          
          ->where("manifestJemput_detail.status_utama","manifest_jemput_penerimaan")
          ->whereRaw($whereRaw)->get(['karyawan.no_hp','kendaraan.no_plat','manifestJemput_detail.pengiriman_id','manifestJemput.*','outlet.name AS nama_outlet','karyawan.name AS nama_karyawan','karyawan.id AS id_kurir','karyawan.no_hp']);    
        $total = $cekData->count();
        
        $data[] = array(
          'id_kurir'=>$ambilData->karyawan_id,
          'nama'=>$ambilData->nama_karyawan,
          'total_barang'=>$total,
          'nomor_plat'=>$ambilData->no_plat,
          'no_hp'=>$ambilData->no_hp,                   
          'manifest'=>$this->daftarManifestRiwayatJemput($ambilData->karyawan_id,$tgl_mulai,$tgl_akhir)
        );      
      }
      return response()->json(['status' => 1,'message' => 'success','data' => $data], 200);
    }
    return response()->json(['status' => 0,'message' => 'Data not found','data' => $data], 200);  
  }
  public function daftarManifestRiwayatJemput($karyawan_id,$tgl_mulai,$tgl_akhir)
  {
    $data = array();    
    $whereRaw="1=1";$total=0;
    if(!is_null($karyawan_id)) $whereRaw.=" AND manifestJemput.karyawan_id = '$karyawan_id'";            
    if(!is_null($tgl_mulai)) $whereRaw.=" AND manifestJemput.tgl_manifest BETWEEN '$tgl_mulai' AND '$tgl_akhir'";
    $cekData = ManifestJemputModel::join("karyawan","manifestJemput.karyawan_id","=","karyawan.id")
      ->leftJoin("outlet","manifestJemput.outlet_id","=","outlet.id")
      ->join("manifestJemput_detail","manifestJemput.code","=","manifestJemput_detail.code")
      ->where("manifestJemput_detail.status_utama","manifest_jemput_penerimaan")
      ->groupBy("manifestJemput.code")
      ->whereRaw($whereRaw)->get(['manifestJemput_detail.pengiriman_id','manifestJemput.*','outlet.name AS nama_outlet','karyawan.name AS nama_karyawan','karyawan.id AS id_kurir','karyawan.no_hp']);    
    if($cekData->count()){  
      foreach ($cekData as $key => $ambilData){              
        $code = $ambilData->code;  
        $cekData = ManifestJemputModel::join("karyawan","manifestJemput.karyawan_id","=","karyawan.id")    
          ->join("manifestJemput_detail","manifestJemput.code","=","manifestJemput_detail.code")
          ->join("pengiriman","manifestJemput_detail.pengiriman_id","=","pengiriman.id")
          ->where("manifestJemput_detail.status_utama","manifest_jemput_penerimaan")
          ->where("manifestJemput_detail.code",$code)->get(['pengiriman.*']);                        
        $total = $cekData->count();
        
        $data[] = array(
          'id'=>$ambilData->id,
          'no_manifest'=>$ambilData->code,
          'nama_outlet'=>$ambilData->nama_outlet,
          'total_barang'=>$total,
          'barang'=>$this->cekBarangRiwayatJemputDetail($code)
        );      
      }
      return $data;
    }
    return response()->json(['status' => 0,'message' => 'Data not found','data' => $data], 200);
  }
  public function cekBarangRiwayatJemputDetail($code){
    $dataBarang = array();
    $cekData = ManifestJemputModel::join("karyawan","manifestJemput.karyawan_id","=","karyawan.id")    
      ->join("manifestJemput_detail","manifestJemput.code","=","manifestJemput_detail.code")
      ->join("pengiriman","manifestJemput_detail.pengiriman_id","=","pengiriman.id")
      ->where("manifestJemput_detail.status_utama","manifest_jemput_penerimaan")
      ->where("manifestJemput_detail.code",$code)->get(['pengiriman.*','manifestJemput_detail.status_utama']);          
    if($cekData){
      foreach($cekData AS $key => $row){
        $cariKel = cariDaerah_helper($row->id_kelurahan_penerima,'kel','kel');               
        $cariKec = cariDaerah_helper($row->id_kecamatan_penerima,'kec','kec');               
        $dataBarang[] = array(
          "id"=>$row->id,
          "stt"=>$row->stt_number,
          "nama_penerima"=>$row->nama_penerima,
          "kelurahan"=> $cariKel,
          "kecamatan"=> $cariKec,
          "no_hp"=>$row->no_hp_penerima,
          "status"=> $row->status_utama??"",
        );
      }
      return $dataBarang;
    }
    return response()->json(['status' => 0,'message' => 'Data not found','data' => $data], 200);
  }




  public function riwayat_jemput_kurir(Request $request)
  {
    $data = array();
    $tgl_mulai = $request->tanggal_mulai;
    $tgl_akhir = $request->tanggal_akhir;
    $user = Auth('sanctum')->user();    
    $karyawan_id = $user->karyawan_id;    
    $whereRaw="1=1";$total=0;
    if(!is_null($karyawan_id)) $whereRaw.=" AND manifestJemput.karyawan_id = '$karyawan_id'";    
    if(!is_null($tgl_mulai)) $whereRaw.=" AND manifestJemput.tgl_manifest BETWEEN '$tgl_mulai' AND '$tgl_akhir'";
    
    $cekData = ManifestJemputModel::join("karyawan","manifestJemput.karyawan_id","=","karyawan.id")
      ->leftJoin("outlet","manifestJemput.outlet_id","=","outlet.id")
      ->join("manifestJemput_detail","manifestJemput.code","=","manifestJemput_detail.code")
      ->where("manifestJemput_detail.status","penerimaan")
      ->orderBy("manifestJemput_detail.warehouse_at","DESC")
      ->whereRaw($whereRaw)->get(['manifestJemput_detail.pengiriman_id','manifestJemput.*','outlet.name AS nama_outlet','karyawan.name AS nama_karyawan','karyawan.id AS id_kurir','karyawan.no_hp']);    
    if($cekData->count()){  
      foreach ($cekData as $key => $ambilData){              
        $code = $ambilData->code;  
        $cekData = ManifestJemputModel::join("karyawan","manifestJemput.karyawan_id","=","karyawan.id")    
          ->join("manifestJemput_detail","manifestJemput.code","=","manifestJemput_detail.code")
          ->join("pengiriman","manifestJemput_detail.pengiriman_id","=","pengiriman.id")
          ->where("manifestJemput_detail.status","penerimaan")
          ->where("manifestJemput_detail.code",$code)->get(['pengiriman.*']);                        
        $total = $cekData->count();
        
        $data[] = array(
          'id'=>$ambilData->id,
          'no_manifest'=>$ambilData->code,
          'nama_outlet'=>$ambilData->nama_outlet,
          'total_barang'=>$total,
          'barang'=>$this->cekBarangRiwayatJemput($code)
        );      
      }
      return response()->json(['status' => 1,'message' => 'success','data' => $data], 200);
    }
    return response()->json(['status' => 0,'message' => 'Data not found','data' => $data], 200);
    
  } 
  public function cekBarangRiwayatJemput($code){
    $dataBarang = array();
    $cekData = ManifestJemputModel::join("karyawan","manifestJemput.karyawan_id","=","karyawan.id")    
      ->join("manifestJemput_detail","manifestJemput.code","=","manifestJemput_detail.code")
      ->join("pengiriman","manifestJemput_detail.pengiriman_id","=","pengiriman.id")
      ->where("manifestJemput_detail.status","penerimaan")
      ->where("manifestJemput_detail.code",$code)->get(['pengiriman.*','manifestJemput_detail.status_utama']);          
    if($cekData){
      foreach($cekData AS $key => $row){
        $cariKel = cariDaerah_helper($row->id_kelurahan_penerima,'kel','kel');               
        $cariKec = cariDaerah_helper($row->id_kecamatan_penerima,'kec','kec');               
        $dataBarang[] = array(
          "id"=>$row->id,
          "stt"=>$row->stt_number,
          "nama_penerima"=>$row->nama_penerima,
          "kelurahan"=> $cariKel,
          "kecamatan"=> $cariKec,
          "no_hp"=>$row->no_hp_penerima,
          "status"=> $row->status_utama??"",
        );
      }
      return $dataBarang;
    }
    return response()->json(['status' => 0,'message' => 'Data not found','data' => $data], 200);
  }
  public function riwayat_jemput_outlet(Request $request)
  {
    $data = array();
    $tgl_mulai = $request->tanggal_mulai;
    $tgl_akhir = $request->tanggal_akhir;
    $user = Auth('sanctum')->user();    
    $karyawan_id = $user->karyawan_id;
    $cariOutlet = KaryawanModel::find($karyawan_id);     
    $outlet_id = $cariOutlet->outlet_id;

    if(is_null($outlet_id)){
      echo response_fail($message = "Outlet Karyawan tidak ditemukan", [], $status_header = 200);
      exit();
    }

    $whereRaw="1=1";$total=0;
    if(!is_null($outlet_id)) $whereRaw.=" AND manifestJemput.outlet_id = '$outlet_id'";    
    if(!is_null($tgl_mulai)) $whereRaw.=" AND manifestJemput.tgl_manifest BETWEEN '$tgl_mulai' AND '$tgl_akhir'";
    
    $cekData = ManifestJemputModel::join("karyawan","manifestJemput.karyawan_id","=","karyawan.id")
      ->leftJoin("outlet","manifestJemput.outlet_id","=","outlet.id")
      ->join("manifestJemput_detail","manifestJemput.code","=","manifestJemput_detail.code")
      ->groupBy("manifestJemput.code")
      ->orderBy("manifestJemput_detail.pickup_at","DESC")
      ->where("manifestJemput_detail.status","pickup")
      ->whereRaw($whereRaw)->get(['manifestJemput_detail.pengiriman_id','manifestJemput.*','outlet.name AS nama_outlet','karyawan.name AS nama_karyawan','karyawan.id AS id_kurir','karyawan.no_hp']);    
    if($cekData->count()){  
      foreach ($cekData as $key => $ambilData){              
        $code = $ambilData->code;        
        $manifestDetail = ManifestJemputDetailModel::where("code",$code);
        $total = $cekData->count();
        
        $data[] = array(
          'id'=>$ambilData->id,
          'no_manifest'=>$ambilData->code,
          'nama_outlet'=>$ambilData->nama_outlet,
          'total_barang'=>$total,
          'barang'=>$this->cekBarangRiwayatJemputOutlet($ambilData->code)
        );      
      }
      return response()->json(['status' => 1,'message' => 'success','data' => $data], 200);
    }
    return response()->json(['status' => 0,'message' => 'Data not found','data' => $data], 200);
    
  } 
  public function cekBarangRiwayatJemputOutlet($code){
    $dataBarang = array();
    $cekData = ManifestJemputModel::join("karyawan","manifestJemput.karyawan_id","=","karyawan.id")    
      ->join("manifestJemput_detail","manifestJemput.code","=","manifestJemput_detail.code")
      ->join("pengiriman","manifestJemput_detail.pengiriman_id","=","pengiriman.id")
      ->where("manifestJemput_detail.status","pickup")
      ->where("manifestJemput_detail.code",$code)->get(['pengiriman.*']);          
    if($cekData){
      foreach($cekData AS $key => $row){
        $cariKel = cariDaerah_helper($row->id_kelurahan_penerima,'kel','kel');               
        $cariKec = cariDaerah_helper($row->id_kecamatan_penerima,'kec','kec');               
        $dataBarang[] = array(
          "id"=>$row->id,
          "stt"=>$row->stt_number,
          "nama_penerima"=>$row->nama_penerima,
          "kelurahan"=> $cariKel,
          "kecamatan"=> $cariKec,
          "no_hp"=>$row->no_hp_penerima,
          "status"=>$row->status
        );
      }
      return $dataBarang;
    }
    return response()->json(['status' => 0,'message' => 'Data not found','data' => $data], 200);
  }

  public function riwayat_lintas_kurir(Request $request)
  {
    $data = array();
    $tgl_mulai = $request->tanggal_mulai;
    $tgl_akhir = $request->tanggal_akhir;
    $user = Auth('sanctum')->user();    
    $karyawan_id = $user->karyawan_id;    
    
    $whereRaw="1=1";$total=0;
    if(!is_null($karyawan_id)) $whereRaw.=" AND manifestLintas.karyawan_id = '$karyawan_id'";    
    if(!is_null($tgl_mulai)) $whereRaw.=" AND manifestLintas.tgl_manifest BETWEEN '$tgl_mulai' AND '$tgl_akhir'";
    
    $cekData = ManifestLintasModel::join("karyawan","manifestLintas.karyawan_id","=","karyawan.id")      
      ->join("manifestLintas_detail","manifestLintas.code","=","manifestLintas_detail.code")
      ->orderBy("manifestLintas_detail.warehouse_at","DESC")
      ->where("manifestLintas_detail.status","penerimaan")
      ->whereRaw($whereRaw)->get(['manifestLintas_detail.pengiriman_id','manifestLintas.*','karyawan.name AS nama_karyawan','karyawan.id AS id_kurir','karyawan.no_hp']);    
    if($cekData->count()){  
      foreach ($cekData as $key => $ambilData){              
        $code = $ambilData->code;        
        $cekData = ManifestLintasModel::join("karyawan","manifestLintas.karyawan_id","=","karyawan.id")    
          ->join("manifestLintas_detail","manifestLintas.code","=","manifestLintas_detail.code")
          ->join("pengiriman","manifestLintas_detail.pengiriman_id","=","pengiriman.id")
          ->where("manifestLintas_detail.status","penerimaan")
          ->where("manifestLintas_detail.code",$code)->get(['pengiriman.*']);          
        $total = $cekData->count();
        
        $cariPortAsal = DB::table("cabang")->where("id",$ambilData->port_asal)->first()->name;
        $cariPortTujuan = DB::table("cabang")->where("id",$ambilData->port_tujuan)->first()->name;

        $data[] = array(
          'id'=>$ambilData->id,
          'no_manifest'=>$ambilData->code,
          'port_asal'=>$cariPortAsal,
          'port_tujuan'=>$cariPortTujuan,
          'total_barang'=>$total,
          'barang'=>$this->cekBarangRiwayatLintas($ambilData->code)
        );      
      }
      return response()->json(['status' => 1,'message' => 'success','data' => $data], 200);
    }
    return response()->json(['status' => 0,'message' => 'Data not found','data' => $data], 200);
    
  } 
  public function cekBarangRiwayatLintas($code){
    $dataBarang = array();
    $cekData = ManifestLintasModel::join("karyawan","manifestLintas.karyawan_id","=","karyawan.id")    
      ->join("manifestLintas_detail","manifestLintas.code","=","manifestLintas_detail.code")
      ->join("pengiriman","manifestLintas_detail.pengiriman_id","=","pengiriman.id")
      ->where("manifestLintas_detail.status","penerimaan")
      ->where("manifestLintas_detail.code",$code)->get(['pengiriman.*','manifestLintas_detail.status_utama']);          
    if($cekData){
      foreach($cekData AS $key => $row){
        $cariKel = cariDaerah_helper($row->id_kelurahan_penerima,'kel','kel');               
        $cariKec = cariDaerah_helper($row->id_kecamatan_penerima,'kec','kec');               
        $dataBarang[] = array(
          "id"=>$row->id,
          "stt"=>$row->stt_number,
          "nama_penerima"=>$row->nama_penerima,          
          "kecamatan"=> $cariKec,
          "no_hp"=>$row->no_hp_penerima,
          "status"=>$row->status_utama??"",
        );
      }
      return $dataBarang;
    }
    return response()->json(['status' => 0,'message' => 'Data not found','data' => $data], 200);
  }    
  public function riwayat_antar(Request $request)
  {
    $data = array();
    $tgl_mulai = $request->tanggal_mulai;
    $tgl_akhir = $request->tanggal_akhir;
    $user = Auth('sanctum')->user();        
    $karyawan_id = $user->karyawan_id;
    
    $whereRaw="1=1";$total=0;    
    if(!is_null($tgl_mulai)) $whereRaw.=" AND manifestAntar.tgl_manifest BETWEEN '$tgl_mulai' AND '$tgl_akhir'";
    
    $cekData = ManifestAntarModel::join("karyawan","manifestAntar.karyawan_id","=","karyawan.id")      
      ->join("manifestAntar_detail","manifestAntar.code","=","manifestAntar_detail.code")
      ->groupBy("manifestAntar.code")
      ->orderBy("manifestAntar_detail.diterima_at","DESC")
      ->where("manifestAntar_detail.status_utama","paket_sampai_dipenerima")
      ->whereRaw($whereRaw)->get(['manifestAntar_detail.pengiriman_id','manifestAntar.*','karyawan.name AS nama_karyawan','karyawan.id AS id_kurir','karyawan.no_hp']);          

    if($cekData->count()){  
      $kecamatan="";
      foreach ($cekData as $key => $ambilData){              
        $code = $ambilData->code;                
        $manifestDetail = ManifestAntarModel::join("karyawan","manifestAntar.karyawan_id","=","karyawan.id")    
            ->join("manifestAntar_detail","manifestAntar.code","=","manifestAntar_detail.code")
            ->join("pengiriman","manifestAntar_detail.pengiriman_id","=","pengiriman.id")
            ->where("manifestAntar_detail.status_utama","paket_sampai_dipenerima")
            ->where("manifestAntar_detail.code",$code)->get(['pengiriman.*','manifestAntar_detail.status_utama']);          
        $total = $manifestDetail->count();        

        $data[] = array(
          'id'=>$ambilData->id,
          'no_manifest'=>$ambilData->code,          
          'kecamatan'=>$this->cekCakupan($karyawan_id),          
          'total_barang'=>$total,
          'barang'=>$this->cekBarangRiwayatAntar($ambilData->code)
        );      
      }
      return response()->json(['status' => 1,'message' => 'success','data' => $data], 200);
    }
    return response()->json(['status' => 0,'message' => 'Data not found','data' => $data], 200);
    
  } 
  public function cekCakupan($karyawan_id){
    $dataArray = array();
    $cakupan = DB::table("karyawan_cakupan")->where("karyawan_id",$karyawan_id)->get();
    foreach($cakupan AS $rt){
      $kecamatan = cariDaerah_helper($rt->kecamatan_id,'kec','kec');								                    
      $dataArray[] = $kecamatan;      
    }
    return $dataArray;
  }
  public function cekBarangRiwayatAntar($code){
    $dataBarang = array();
    $cekData = ManifestAntarModel::join("karyawan","manifestAntar.karyawan_id","=","karyawan.id")    
      ->join("manifestAntar_detail","manifestAntar.code","=","manifestAntar_detail.code")
      ->join("pengiriman","manifestAntar_detail.pengiriman_id","=","pengiriman.id")
      ->where("manifestAntar_detail.status_utama","paket_sampai_dipenerima")
      ->where("manifestAntar_detail.code",$code)->get(['pengiriman.*','manifestAntar_detail.status_utama']);          
    if($cekData){
      foreach($cekData AS $key => $row){
        $cariKel = cariDaerah_helper($row->id_kelurahan_penerima,'kel','kel');               
        $cariKec = cariDaerah_helper($row->id_kecamatan_penerima,'kec','kec');               
        $dataBarang[] = array(
          "id"=>$row->id,
          "stt"=>$row->stt_number,
          "nama_penerima"=>$row->nama_penerima,
          "kelurahan"=> $cariKel,
          "kecamatan"=> $cariKec,
          "no_hp"=>$row->no_hp_penerima,
          "status"=>$row->status_utama??""
        );
      }
      return $dataBarang;
    }
    return response()->json(['status' => 0,'message' => 'Data not found','data' => $data], 200);
  }
}

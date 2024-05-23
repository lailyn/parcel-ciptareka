<?php

namespace App\Http\Controllers;

use App\Models\WarehouseModel;
use App\Models\ManifestModel;
use App\Models\ManifestDetailModel;
use App\Models\CabangModel;
use App\Models\PengirimanModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class WarehouseController extends Controller
{
	var $set    = "warehouse";
	var $title      = "Warehouse";
	var $subtitle   = "Warehouse Baru";
	var $folder = "transaksi/warehouse";

	public function index(){
		$warehouseData = WarehouseModel::All();
		$data['title']  = $this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "view";
		$data['no_plat']    = "";        
		return view($this->folder.'/index',$data)->with('warehouse', $warehouseData);
	}
	public function insert(){
		$data['title']  = "Add ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "insert";                
		return view($this->folder.'/insert',$data);
	}
	public function generate(Request $request){
		$data['title']  = "Generate ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "generate";        
		$data['no_plat'] = $no_plat = no_plat($request->no_plat);                
		$data['manifest'] = ManifestModel::leftJoin('kendaraan', 'kendaraan.id', '=', 'manifest.kendaraan_id')
					->leftJoin('karyawan', 'karyawan.id', '=', 'manifest.karyawan_id')                                        
					->groupBy("manifest.code")
					->where("kendaraan.no_plat",$no_plat)
					->where("manifest.status","baru")
					->get(['kendaraan.name AS kendaraan','kendaraan.no_plat','karyawan.name AS karyawan', 'manifest.*']);
		return view($this->folder.'/index',$data);
	}


	public function create(Request $request){               
		$user = Auth::user()->getAttributes();
		$jum            = $request->jum;
		$id_manifest          = $request->id_manifest;
		$data2 = ManifestModel::find($id_manifest);
				
		for ($i=1; $i <= $jum; $i++) {                                                          
			if(isset($_POST["pick_".$i])){
				$id_manifest_detail = $_POST["id_manifest_detail_".$i];                                                                                             
				$data = ManifestDetailModel::find($id_manifest_detail);             
				$data->kondisi = $_POST["kondisi_".$i];                                                                             
				$data->warehouse_by = $user['id']; 
				$data->updated_at = $waktu = $data->warehouse_at = Carbon::now()->toDateTimeString();               
				$data->save();              

				$ids = $_POST["pengiriman_id_".$i];
				$data3 = PengirimanModel::find($ids);
				$data3->port_id =  $request->port_id;
				$data3->status =  "diterima_port_tujuan";             
				$data3->tgl_warehouse =  Carbon::now()->toDateTimeString();
				$data3->save();

				$cekPortTujuan = DB::table("cabang")->where("id",$request->port_id);
				$portTujuan = ($cekPortTujuan->count()>0)?$cekPortTujuan->first()->name:'';
				simpanStatus($ids,$waktu,"diterima_port_tujuan","Paket sudah diterima di Port Tujuan ".$portTujuan);
			}      
		}   
				
		$data2->status = "warehouse";
		$data2->save();

		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('warehouse.index');                   
	}
	
	public function proses($id){
		$data['title']  = "Proses ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "proses";
		$manifestData = ManifestModel::leftJoin("kendaraan","manifest.kendaraan_id","=","kendaraan.id")
				->leftJoin("karyawan","manifest.karyawan_id","=","karyawan.id")
				->where("manifest.id",$id)
				->get(["karyawan.name as karyawan","kendaraan.no_plat","kendaraan.name AS kendaraan","manifest.*","manifest.code AS manifest_code"]);
		$data['manifestDataDetail'] = ManifestModel::join("manifest_detail","manifest.code","=","manifest_detail.code")
				->join("pengiriman","pengiriman.id","=","manifest_detail.pengiriman_id")                
				->where("manifest.id",$id)
				->get(["manifest.*","manifest.id AS id_manifest","manifest_detail.id AS id_manifest_detail","manifest.code AS manifest_code","pengiriman.code AS kode_tr","pengiriman.*"]);
		return view($this->folder.'/detail',$data)->with('manifest', $manifestData);
	}    

}


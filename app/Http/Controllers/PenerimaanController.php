<?php

namespace App\Http\Controllers;

use App\Models\PickupModel;
use App\Models\PickupDetailModel;
use App\Models\KaryawanModel;
use App\Models\CabangModel;
use App\Models\KendaraanDanPlatModel;
use App\Models\PengirimanModel;
use App\Models\OutletModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PenerimaanController extends Controller
{
	var $set    = "penerimaan";
	var $title      = "Penerimaan";
	var $subtitle   = "Penerimaan Baru";
	var $folder = "transaksi/penerimaan";

	public function index(){
		$pickupData = PickupModel::All();
		$data['title']  = $this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "view"; 		
		$data['pengiriman'] = PengirimanModel::leftJoin('jenis_tarif', 'jenis_tarif.id', '=', 'pengiriman.jenis_tarif_id')                                       					
					->join("pickup_detail",'pengiriman.id','=','pickup_detail.pengiriman_id')
					->join("pickup",'pickup.code','=','pickup_detail.code')
					->leftJoin('kendaraan', 'kendaraan.id', '=', 'pickup.kendaraan_id')
					->leftJoin('karyawan', 'karyawan.id', '=', 'pickup.karyawan_id')					
					->leftJoin('outlet', 'outlet.id', '=', 'pickup.outlet_id')										
					->whereRaw("pickup_detail.penerimaan_at IS NOT NULL")
					->get(['jenis_tarif.name AS jenis_tarif','kendaraan.name AS kendaraan','outlet.name AS outlet','karyawan.name AS karyawan','pengiriman.code AS kode_tr', 'pengiriman.*']);
		return view($this->folder.'/index',$data)->with('pickup', $pickupData);
	}
	public function dataAjax(Request $request)
  {
		$data = [];

		if($request->has('term')){
			$term = $request->term;
			$query = OutletModel::where("outlet.name","like","%$term%")
					->where("status",1)					
					->get(['outlet.*']);
			foreach($query AS $isiData){
				$data[] = array(
					"id"=>$isiData->id,
					"text"=>$isiData->name
				);
			}
		}
		return response()->json($data);
  }
	public function generate(Request $request){
		$data['title']  = "Generate ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "generate";        
		$data['kendaraan_id'] = $kendaraan_id = $request->kendaraan_id;
		$data['port_id'] = $port_id = $request->port_id;
		$data['karyawan_id'] = $karyawan_id = $request->karyawan_id;
		$data['tgl_pickup'] = $tgl_pickup = $request->tgl_pickup;
		$data['outlet_id'] = $outlet_id = $request->outlet_id;	
		$data['kendaraan'] = KendaraanDanPlatModel::where("status",1)->get();		
		$data['cabang'] = CabangModel::All();
		$data['outlet'] = OutletModel::where("status",1)->get();		
		$data['karyawan'] = KaryawanModel::where("status",1)->get();			
		$data['pengiriman'] = PengirimanModel::leftJoin('jenis_tarif', 'jenis_tarif.id', '=', 'pengiriman.jenis_tarif_id')                                       					
					->join("pickup_detail",'pengiriman.id','=','pickup_detail.pengiriman_id')
					->join("pickup",'pickup.code','=','pickup_detail.code')
					->leftJoin('kendaraan', 'kendaraan.id', '=', 'pickup.kendaraan_id')
					->leftJoin('karyawan', 'karyawan.id', '=', 'pickup.karyawan_id')					
					->leftJoin('outlet', 'outlet.id', '=', 'pickup.outlet_id')					
					->whereRaw("pickup_detail.penerimaan_at IS NULL")
					->where("pickup.karyawan_id",$karyawan_id)
					->where("pickup.kendaraan_id",$kendaraan_id)
					->where("pickup.outlet_id",$outlet_id)
					->whereRaw("LEFT(pickup_detail.pickup_at,10) = '$tgl_pickup'")
					->get(['jenis_tarif.name AS jenis_tarif','kendaraan.name AS kendaraan','outlet.name AS outlet','karyawan.name AS karyawan', 'pickup.id AS id_pickup','pickup_detail.id AS id_pickup_detail','pengiriman.code AS kode_tr', 'pengiriman.*']);		
		return view($this->folder.'/insert',$data);
	}
	public function insert(){		
		$data['title']  = "Tambah ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "insert";	
		$data['tgl_pickup'] = date("Y-m-d");
		$data['outlet_id'] = "";    
		$data['kendaraan_id'] = "";    	
		$data['karyawan_id'] = "";    	
		$data['port_id'] = "";    	
		$data['kendaraan'] = KendaraanDanPlatModel::where("status",1)->get();		
		$data['cabang'] = CabangModel::All();
		$data['outlet'] = OutletModel::where("status",1)->get();		
		$data['karyawan'] = KaryawanModel::where("status",1)->get();				
		return view($this->folder.'/insert',$data);
	}


	public function create(Request $request){				
		$user = Auth::user()->getAttributes();
		$jum 			= $request->jum;
		$id_pickup 			= $request->id_pickup;
		$data2 = PickupModel::find($id_pickup);
				
		for ($i=1; $i <= $jum; $i++) { 															
			if(isset($_POST["pick_".$i])){
				$id_pickup_detail = $_POST["id_pickup_detail_".$i];																				
				$data = PickupDetailModel::find($id_pickup_detail);				
				$data->penerimaan_by = $user['id']; 
				$data->updated_at = $waktu = $data->penerimaan_at = Carbon::now()->toDateTimeString();				
				$data->save();				

				$ids = $_POST["pengiriman_id_".$i];
				$data3 = PengirimanModel::find($ids);
				$data3->port_id =  $request->port_id;
				$data3->status =  "penerimaan_from_outlet";				
				$data3->tgl_penerimaan =  Carbon::now()->toDateTimeString();
				$data3->save();

				$cekPort = CabangModel::find($request->port_id)->first();			
				simpanStatus($ids,$waktu,"penerimaan_from_outlet","Paket sudah diterima di Port ".$cekPort->name);
			}      
		}	
		
		$data2->port_id =  $request->port_id;
		$data2->status = "penerimaan";
		$data2->save();

		session()->put('msg', setMsg("Successed!","success"));        
    return redirect()->route('penerimaan.index');					
	}

}


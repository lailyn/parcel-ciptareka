<?php

namespace App\Http\Controllers;

use App\Models\PickupModel;
use App\Models\PickupDetailModel;
use App\Models\KaryawanModel;
use App\Models\KendaraanDanPlatModel;
use App\Models\PengirimanModel;
use App\Models\OutletModel;
use App\Models\OutletModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PickupController extends Controller
{
	var $set    = "pickup";
	var $title      = "Pickup";
	var $subtitle   = "Pickup Baru";
	var $folder = "transaksi/pickup";

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
					->whereRaw("pickup_detail.penerimaan_at IS NULL")
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
		$data['kendaraan_id'] = $request->kendaraan_id;
		$data['karyawan_id'] = $request->karyawan_id;
		$data['tgl_pickup'] = $request->tgl_pickup;
		$data['outlet_id'] = $outlet_id = $request->outlet_id;	
		$data['kendaraan'] = KendaraanDanPlatModel::where("status",1)->get();		
		$data['outlet'] = OutletModel::where("status",1)->get();		
		$data['karyawan'] = KaryawanModel::where("status",1)->get();			
		$data['pengiriman'] = PengirimanModel::leftJoin('jenis_tarif', 'jenis_tarif.id', '=', 'pengiriman.jenis_tarif_id')                                       					
					->where("pengiriman.status_bayar",'lunas')->where("pengiriman.status","baru")->where("pengiriman.outlet_id",$outlet_id)
					->get(['jenis_tarif.name AS jenis_tarif','pengiriman.code AS kode_tr','pengiriman.*']);
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
		$data['kendaraan'] = KendaraanDanPlatModel::where("status",1)->get();		
		$data['outlet'] = OutletModel::where("status",1)->get();		
		$data['karyawan'] = KaryawanModel::where("status",1)->get();		
		return view($this->folder.'/insert',$data);
	}


	public function create(Request $request){		
		$data2 = new PickupModel();
		$jum 			= $request->jum;
		
		$codes = cariKode_helper("pickup");
		$data2->code = $codes;
		for ($i=1; $i <= $jum; $i++) { 															
			if(isset($_POST["pick_".$i])){
				$data = new PickupDetailModel();
				$data->code = $codes;				
				$data->pengiriman_id = $ids = $_POST["pengiriman_id_".$i];																				
				$data->pickup_at = $waktu = Carbon::now()->toDateTimeString();
				$data->save();				

				$data3 = PengirimanModel::find($ids);
				$data3->status =  "pickup_from_outlet";
				$data3->tgl_pickup = Carbon::now()->toDateString();
				$data3->save();

				$cekOutlet = OutletModel::find($request->outlet_id)->first();
				simpanStatus($ids,$waktu,"pickup_from_outlet","Paket sudah keluar dari Outlet ".$cekOutlet->name);
			}      
		}	

		

		$objectName = 'bukti';
    $request->validate([$objectName => 'required|mimes:jpeg,jpg,png,pdf|max:1000']);    
    if($request->file()) {
      $fileName = time().'_'.$request->$objectName->getClientOriginalName();        
      $upload = $request->$objectName->move(public_path('ima49es/pickup'), $fileName);        
      $data2->bukti = $fileName;                
    }

		$data2->karyawan_id = $request->karyawan_id;        
		$data2->kendaraan_id = $request->kendaraan_id;        
		$data2->outlet_id = $request->outlet_id;        
		$data2->status = "baru";
		$data2->save();

		session()->put('msg', setMsg("Successed!","success"));        
    return redirect()->route('pickup.index');					
	}
}


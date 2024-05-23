<?php

namespace App\Http\Controllers;

use App\Models\ManifestJemputModel;
use App\Models\ManifestJemputDetailModel;
use App\Models\KaryawanModel;
use App\Models\CabangModel;
use App\Models\KendaraanDanPlatModel;
use App\Models\PengirimanModel;
use App\Models\OutletModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use PDF;
use DB;

class ManifestJemputController extends Controller
{
	var $set    		= "manifestJemput";
	var $title      = "Manifest Jemput";
	var $subtitle   = "Manifest Jemput";
	var $folder 		= "transaksi/manifestJemput";

	public function edit($id){
		$data['title']  = "Edit ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = "manifestJemput-list";
		$data['set']    = "edit";
		$manifestData = ManifestJemputModel::find($id);		
		$data['cabang'] = CabangModel::All();		
		$data['kendaraan_id'] = $manifestData->kendaraan_id;
		$data['outlet_id'] = $manifestData->outlet_id;		
		$data['karyawan_id'] = $manifestData->karyawan_id;
		$data['tgl_manifest'] = $manifestData->tgl_manifest;
		$data['keterangan'] = $manifestData->keterangan;				
		$data['kendaraan'] = KendaraanDanPlatModel::where("status",1)->get();       		
		$data['outlet'] = OutletModel::where("status",1)->get();        
		$data['karyawan'] = KaryawanModel::where("status",1)->get();        		
		$data['manifestDetail'] = ManifestJemputModel::join("manifestJemput_detail","manifestJemput.code","=","manifestJemput_detail.code")
				->join("pengiriman","pengiriman.id","=","manifestJemput_detail.pengiriman_id")
				->leftJoin('jenis_tarif', 'jenis_tarif.id', '=', 'pengiriman.jenis_tarif_id')
				->leftJoin("kendaraan","manifestJemput.kendaraan_id","=","kendaraan.id")
				->leftJoin("karyawan","manifestJemput.karyawan_id","=","karyawan.id")
				->leftJoin("outlet","outlet.id","=","manifestJemput.outlet_id")
				->where("manifestJemput.id",$id)
				->get(['manifestJemput_detail.id AS ids','jenis_tarif.name AS jenis_tarif',"karyawan.name as karyawan","kendaraan.name AS kendaraan","outlet.name AS outlet","manifestJemput.*","manifestJemput.code AS manifest_code","pengiriman.code AS kode_tr","pengiriman.*"]);
		$data['pengiriman'] = PengirimanModel::leftJoin('jenis_tarif', 'jenis_tarif.id', '=', 'pengiriman.jenis_tarif_id')                                                          										
					->whereRaw("pengiriman.id NOT IN (SELECT pengiriman_id FROM manifestJemput_detail)")
					->where("pengiriman.status_bayar",'lunas')->where("pengiriman.outlet_id",$manifestData->outlet_id)
					->where("pengiriman.status","baru")
					->get(['jenis_tarif.name AS jenis_tarif','pengiriman.code AS kode_tr','pengiriman.*']);
		return view($this->folder.'/edit',$data)->with('manifest', $manifestData);
	}
	public function deleteDetail($id){
		$data = ManifestJemputDetailModel::find($id);
		$ids = $data->first()->code;
		$pengiriman_id = $data->first()->pengiriman_id;
		$id = ManifestJemputModel::where("code",$ids)->first()->id;

		$data3 = PengirimanModel::find($pengiriman_id);
		$data3->status =  "baru";
		$data3->tgl_manifest_jemput =  NULL;
		$data3->save();

		hapusStatus($pengiriman_id,"manifest_jemput");

		$data->delete();
		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('manifestJemput.edit',$id);
	}
	public function lock($id){
    $data = ManifestJemputModel::find($id);
    $data->status = "locked";            
    $data->locked_at = $data->updated_at = Carbon::now()->toDateTimeString();
    $data->save();    
    session()->put('msg', setMsg("Successed!","success"));        
    return redirect()->route('manifestJemput.list');
  }
	public function list(){		
		$data['title']  = $this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = "manifestJemput-list";
		$data['set']    = "view";       
		$data['manifest'] = ManifestJemputModel::leftJoin('kendaraan', 'kendaraan.id', '=', 'manifestJemput.kendaraan_id')
					->leftJoin('karyawan', 'karyawan.id', '=', 'manifestJemput.karyawan_id')                                        
					->leftJoin("outlet","outlet.id","=","manifestJemput.outlet_id")
					->groupBy("manifestJemput.code")
					->orderBy("manifestJemput.created_at","desc")
					->get(['kendaraan.name AS kendaraan','kendaraan.no_plat',"outlet.name AS outlet",'karyawan.name AS karyawan', 'manifestJemput.*']);		
		return view($this->folder.'/index',$data);
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
		$data['isi']    = "manifestJemput-insert";
		$data['set']    = "generate";        
		$data['kendaraan_id'] = $request->kendaraan_id;
		$data['outlet_id'] = $request->outlet_id;		
		$data['karyawan_id'] = $request->karyawan_id;
		$data['tgl_manifest'] = $request->tgl_manifest;
		$data['keterangan'] = $request->keterangan;				
		$data['kendaraan'] = KendaraanDanPlatModel::where("status",1)->get();       		
		$data['karyawan'] = KaryawanModel::where("status",1)->get();            
		$data['outlet'] = OutletModel::where("status",1)->get();        
		$data['pengiriman'] = PengirimanModel::leftJoin('jenis_tarif', 'jenis_tarif.id', '=', 'pengiriman.jenis_tarif_id')                                                          										
					->whereRaw("pengiriman.id NOT IN (SELECT pengiriman_id FROM manifestJemput_detail)")
					->where("pengiriman.status_bayar",'lunas')->where("pengiriman.outlet_id",$request->outlet_id)->where("pengiriman.status","baru")
					->get(['jenis_tarif.name AS jenis_tarif','pengiriman.code AS kode_tr','pengiriman.*']);
		return view($this->folder.'/insert',$data);
	}
	public function insert(){       
		$data['title']  = "Tambah ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = "manifestJemput-insert";
		$data['set']    = "insert"; 
		$data['tgl_manifest'] = date("Y-m-d");
		$data['port_asal'] = "";    
		$data['port_tujuan'] = "";    
		$data['outlet_id'] = "";    
		$data['estimasi'] = "";    
		$data['keterangan'] = "";    
		$data['kendaraan_id'] = "";     
		$data['karyawan_id'] = "";      
		$data['kendaraan'] = KendaraanDanPlatModel::where("status",1)->get();       
		$data['outlet'] = OutletModel::where("status",1)->get();        
		$data['karyawan'] = KaryawanModel::where("status",1)->get();        		
		return view($this->folder.'/insert',$data);
	}
	public function addItem($id,$codes){
		$user = Auth::user()->getAttributes();
		$data = new ManifestJemputDetailModel();
		$data->code = $codes;
		$data->pengiriman_id = $id;
		$data->manifest_at = $waktu = Carbon::now()->toDateTimeString();
		$data->manifest_by = $user['id'];
		$data->status =  "manifested";
		$data->status_utama =  "manifest_jemput";
		$data->save();              

		$data3 = PengirimanModel::find($id);
		$data3->status =  "manifest_jemput";
		$data3->tgl_manifest_jemput =  Carbon::now()->toDateString();
		$data3->save();
			
		$cekManifest = ManifestJemputModel::where("code",$codes)->first();
		$manifest_id = $cekManifest->id;
		$karyawan_id = $cekManifest->karyawan_id;
		$cekKaryawan = DB::table("karyawan")->where("id",$karyawan_id);
		$nama_karyawan = ($cekKaryawan->count()>0)?$cekKaryawan->first()->name:"";		

		simpanStatus($id,$waktu,"manifest_jemput","Paket sudah manifest jemput pada ".$waktu." oleh ".$nama_karyawan);
		
		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('manifestJemput.edit',$manifest_id);
	}

	public function cekPort($manifestJemput_detail_id){

		$data = ManifestJemputDetailModel::find($manifestJemput_detail_id);								
		$codes = $data->code;
		$ids = $data->pengiriman_id;
		return $codes."/".$ids;
	}

	public function create(Request $request){       
		$user = Auth::user()->getAttributes();
		$data2 = new ManifestJemputModel();
		$jum         = $request->jum;
		$karyawan_id = $request->karyawan_id;
		$outlet_id = $request->outlet_id;
		$cekSlug = OutletModel::join("cabang","outlet.port_id","=","cabang.id")->where("outlet.id",$outlet_id)->get(["cabang.slug"]);
		$lokasi_asal = ($cekSlug->count()>0)?$cekSlug->first()->slug:"";
		$cekKaryawan = DB::table("karyawan")->where("id",$karyawan_id);
		$nama_karyawan = ($cekKaryawan->count()>0)?$cekKaryawan->first()->name:"";
		// JKTMDN1230701001	
		$codes = cariKode_manifest("manifestJemput",1,$lokasi_asal,$lokasi_asal);	
		$data2->code = $codes;		

		for ($i=1; $i <= $jum; $i++) {                                                          
			if(isset($_POST["pick_".$i])){
				$data = new ManifestJemputDetailModel();
				$data->code = $codes;
				$data->pengiriman_id = $ids = $_POST["pengiriman_id_".$i];                                                                              
				$data->manifest_at = $waktu = Carbon::now()->toDateTimeString();
				$data->manifest_by = $user['id'];
				$data->status =  "manifested";
				$data->status_utama =  "manifest_jemput";
				$data->save();              

				$data3 = PengirimanModel::find($ids);
				$data3->status =  "manifest_jemput";
				$data3->tgl_manifest_jemput =  Carbon::now()->toDateString();
				$data3->save();
				
				simpanStatus($ids,$waktu,"manifest_jemput","Paket sudah manifest jemput pada ".$waktu." oleh ".$nama_karyawan);
			}      
		}   
		

		$data2->tgl_manifest = Carbon::now()->toDateString();
		$data2->total_paket = $jum;
		$data2->kendaraan_id = $request->kendaraan_id;        
		$data2->karyawan_id = $karyawan_id;        		
		$data2->outlet_id = $outlet_id;
		$data2->keterangan = $request->keterangan;        		
		$data2->status = "baru";
		$data2->save();

		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('manifestJemput.list');                   
	}
	public function cetak($id){
		$manifestData = ManifestJemputModel::join("manifestJemput_detail","manifestJemput.code","=","manifestJemput_detail.code")
				->join("pengiriman","pengiriman.id","=","manifestJemput_detail.pengiriman_id")
				->leftJoin('jenis_tarif', 'jenis_tarif.id', '=', 'pengiriman.jenis_tarif_id')
				->leftJoin("kendaraan","manifestJemput.kendaraan_id","=","kendaraan.id")
				->leftJoin("karyawan","manifestJemput.karyawan_id","=","karyawan.id")
				->leftJoin("outlet","outlet.id","=","manifestJemput.outlet_id")
				->where("manifestJemput.id",$id)
				->get(["karyawan.name as karyawan",'jenis_tarif.name AS jenis_tarif',"kendaraan.name AS kendaraan","outlet.name AS outlet","manifestJemput.*","manifestJemput.code AS manifest_code","pengiriman.code AS kode_tr","pengiriman.*"]);
		// return view($this->folder.'/cetak')->with('manifest', $manifestData);
		$pdf = PDF::loadview($this->folder.'/cetak',['manifest'=>$manifestData]);		
		return $pdf->download('cetak-Manifest.pdf');
	}
	public function detail($id){
		$data['title']  = "Detail ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = "manifestJemput-list";
		$data['set']    = "detail";
		$manifestData = ManifestJemputModel::find($id);		
		$data['cabang'] = CabangModel::All();		
		$data['kendaraan_id'] = $manifestData->kendaraan_id;
		$data['outlet_id'] = $manifestData->outlet_id;		
		$data['karyawan_id'] = $manifestData->karyawan_id;
		$data['tgl_manifest'] = $manifestData->tgl_manifest;
		$data['keterangan'] = $manifestData->keterangan;				
		$data['kendaraan'] = KendaraanDanPlatModel::where("status",1)->get();       		
		$data['outlet'] = OutletModel::where("status",1)->get();        
		$data['karyawan'] = KaryawanModel::where("status",1)->get();        		
		$data['manifestDetail'] = ManifestJemputModel::join("manifestJemput_detail","manifestJemput.code","=","manifestJemput_detail.code")
				->join("pengiriman","pengiriman.id","=","manifestJemput_detail.pengiriman_id")
				->leftJoin('jenis_tarif', 'jenis_tarif.id', '=', 'pengiriman.jenis_tarif_id')
				->leftJoin("kendaraan","manifestJemput.kendaraan_id","=","kendaraan.id")
				->leftJoin("karyawan","manifestJemput.karyawan_id","=","karyawan.id")
				->leftJoin("outlet","outlet.id","=","manifestJemput.outlet_id")
				->where("manifestJemput.id",$id)
				->get(['manifestJemput_detail.status AS sta','manifestJemput_detail.id AS ids','jenis_tarif.name AS jenis_tarif',"karyawan.name as karyawan","kendaraan.name AS kendaraan","outlet.name AS outlet","manifestJemput.*","manifestJemput.code AS manifest_code","pengiriman.code AS kode_tr","pengiriman.*"]);		
		return view($this->folder.'/detail',$data)->with('manifest', $manifestData);
	}
	public function pickup($id){
		$data['title']  = "Pickup ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = "manifestJemput-list";
		$data['set']    = "detail";
		$manifestData = ManifestJemputModel::find($id);		
		$data['cabang'] = CabangModel::All();		
		$data['kendaraan_id'] = $manifestData->kendaraan_id;
		$data['outlet_id'] = $manifestData->outlet_id;		
		$data['karyawan_id'] = $manifestData->karyawan_id;
		$data['tgl_manifest'] = $manifestData->tgl_manifest;
		$data['keterangan'] = $manifestData->keterangan;				
		$data['kendaraan'] = KendaraanDanPlatModel::where("status",1)->get();       		
		$data['outlet'] = OutletModel::where("status",1)->get();        
		$data['karyawan'] = KaryawanModel::where("status",1)->get();        		
		$data['manifestDetail'] = ManifestJemputModel::join("manifestJemput_detail","manifestJemput.code","=","manifestJemput_detail.code")
				->join("pengiriman","pengiriman.id","=","manifestJemput_detail.pengiriman_id")
				->leftJoin('jenis_tarif', 'jenis_tarif.id', '=', 'pengiriman.jenis_tarif_id')
				->leftJoin("kendaraan","manifestJemput.kendaraan_id","=","kendaraan.id")
				->leftJoin("karyawan","manifestJemput.karyawan_id","=","karyawan.id")
				->leftJoin("outlet","outlet.id","=","manifestJemput.outlet_id")
				->where("manifestJemput.id",$id)
				->get(['manifestJemput_detail.status AS sta','manifestJemput_detail.id AS ids','jenis_tarif.name AS jenis_tarif',"karyawan.name as karyawan","kendaraan.name AS kendaraan","outlet.name AS outlet","manifestJemput.*","manifestJemput.code AS manifest_code","pengiriman.code AS kode_tr","pengiriman.*"]);		
		return view($this->folder.'/pickup',$data)->with('manifest', $manifestData);
	}

	public function updatePickup(Request $request){       
		$user = Auth::user()->getAttributes();		
		$jum         = $request->jum;		
		for ($i=1; $i <= $jum; $i++) {                                                          
			if(isset($_POST["pick_".$i])){
				$manifestJemput_detail_id = $_POST["manifestJemput_detail_id_".$i];                                                                              
				$data = ManifestJemputDetailModel::find($manifestJemput_detail_id);								
				$codes = $data->code;
				$ids = $data->pengiriman_id;
				$data->updated_at = $data->pickup_at = $waktu = Carbon::now()->toDateTimeString();
				$data->pickup_by = $user['id'];
				$data->status =  "pickup";
				$data->status_utama =  "manifest_jemput_pickup";
				$data->save();             	

				$cekManifest = ManifestJemputModel::where("code",$codes)->first();				
				$karyawan_id = $cekManifest->karyawan_id;
				$cekKaryawan = DB::table("karyawan")->where("id",$karyawan_id);
				$nama_karyawan = ($cekKaryawan->count()>0)?$cekKaryawan->first()->name:"";												

				simpanStatus($ids,$waktu,"manifest_jemput_pickup","Paket telah diserahkan ke kurir ".$nama_karyawan." pada ".$waktu);
			}      
		}   		

		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('manifestJemput.list');                   
	}


	public function penerimaan($id){
		$data['title']  = "Penerimaan Gudang ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = "manifestJemput-list";
		$data['set']    = "detail";
		$manifestData = ManifestJemputModel::find($id);		
		$data['cabang'] = CabangModel::All();		
		$data['kendaraan_id'] = $manifestData->kendaraan_id;
		$data['outlet_id'] = $manifestData->outlet_id;		
		$data['karyawan_id'] = $manifestData->karyawan_id;
		$data['tgl_manifest'] = $manifestData->tgl_manifest;
		$data['keterangan'] = $manifestData->keterangan;				
		$data['kendaraan'] = KendaraanDanPlatModel::where("status",1)->get();       		
		$data['outlet'] = OutletModel::where("status",1)->get();        
		$data['karyawan'] = KaryawanModel::where("status",1)->get();        		
		$data['manifestDetail'] = ManifestJemputModel::join("manifestJemput_detail","manifestJemput.code","=","manifestJemput_detail.code")
				->join("pengiriman","pengiriman.id","=","manifestJemput_detail.pengiriman_id")
				->leftJoin('jenis_tarif', 'jenis_tarif.id', '=', 'pengiriman.jenis_tarif_id')
				->leftJoin("kendaraan","manifestJemput.kendaraan_id","=","kendaraan.id")
				->leftJoin("karyawan","manifestJemput.karyawan_id","=","karyawan.id")
				->leftJoin("outlet","outlet.id","=","manifestJemput.outlet_id")
				->where("manifestJemput.id",$id)
				->get(['manifestJemput_detail.status AS sta','manifestJemput_detail.id AS ids','jenis_tarif.name AS jenis_tarif',"karyawan.name as karyawan","kendaraan.name AS kendaraan","outlet.name AS outlet","manifestJemput.*","manifestJemput.code AS manifest_code","pengiriman.code AS kode_tr","pengiriman.*"]);		
		return view($this->folder.'/penerimaan',$data)->with('manifest', $manifestData);
	}

	public function updatePenerimaan(Request $request){       
		$user = Auth::user()->getAttributes();		
		$jum         = $request->jum;		
		for ($i=1; $i <= $jum; $i++) {                                                          
			if(isset($_POST["pick_".$i])){
				$manifestJemput_detail_id = $_POST["manifestJemput_detail_id_".$i];                                                                              
				$data = ManifestJemputDetailModel::find($manifestJemput_detail_id);								
				$codes = $data->code;
				$ids = $data->pengiriman_id;
				$data->updated_at = $data->warehouse_at = $waktu = Carbon::now()->toDateTimeString();
				$data->warehouse_by = $user['id'];
				$data->status	 =  "penerimaan";
				$data->status_utama =  "manifest_jemput_penerimaan";
				$data->save();             											

				$cekManifest = ManifestJemputModel::where("code",$codes)->first();				
				$outlet_id = $cekManifest->outlet_id;
				$cekPort = DB::table("outlet")->join("cabang","cabang.id","=","outlet.port_id")
							->where("outlet.id",$outlet_id)->get(['cabang.name AS port']);
				$port = ($cekPort->count()>0)?$cekPort->first()->port:"";												

				simpanStatus($ids,$waktu,"manifest_jemput_penerimaan","Paket telah sampai di Port ".$port." pada ".$waktu);
			}      
		}   		

		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('manifestJemput.list');                   
	}
}


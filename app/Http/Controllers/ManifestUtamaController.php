<?php

namespace App\Http\Controllers;

use App\Models\ManifestUtamaModel;
use App\Models\ManifestLintasModel;
use App\Models\ManifestUtamaDetailModel;
use App\Models\ManifestLintasDetailModel;
use App\Models\KaryawanModel;
use App\Models\CabangModel;
use App\Models\KendaraanDanPlatModel;
use App\Models\PengirimanModel;
use App\Models\OutletModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use PDF;
use DB;

class ManifestUtamaController extends Controller
{
	var $set    		= "manifestUtama";
	var $title      = "Manifest Utama";
	var $subtitle   = "Manifest Utama";
	var $folder 		= "transaksi/manifestUtama";

	public function edit($id){
		$data['title']  = "Edit ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = "manifestUtama-list";
		$data['set']    = "edit";
		$manifestData = ManifestUtamaModel::find($id);		
		$data['cabang'] = CabangModel::All();		
		$data['kendaraan_id'] = $manifestData->kendaraan_id;
		$data['port_asal'] = $manifestData->port_asal;		
		$data['port_tujuan'] = $manifestData->port_tujuan;		
		$data['karyawan_id'] = $manifestData->karyawan_id;
		$data['tgl_manifest'] = $manifestData->tgl_manifest;
		$data['keterangan'] = $manifestData->keterangan;				
		$data['kendaraan'] = KendaraanDanPlatModel::where("status",1)->get();       		
		$data['cabang'] = CabangModel::All();
		$data['karyawan'] = KaryawanModel::where("status",1)->get();        		
		$data['manifestDetail'] = ManifestUtamaModel::join("manifestUtama_detail","manifestUtama.code","=","manifestUtama_detail.code")
				->leftJoin("manifestLintas","manifestUtama_detail.manifestLintas_id","=","manifestLintas.id")
				->leftJoin("manifestLintas_detail","manifestUtama_detail.code","=","manifestLintas.code")
				->leftJoin("kendaraan","manifestUtama.kendaraan_id","=","kendaraan.id")
				->leftJoin("karyawan","manifestUtama.karyawan_id","=","karyawan.id")				
				->where("manifestUtama.id",$id)
				->groupBy("manifestLintas.code")
				->get(['manifestUtama_detail.id AS ids',"karyawan.name as karyawan","kendaraan.name AS kendaraan","manifestUtama.*","manifestUtama_detail.code AS manifest_code"]);
		// dd($data['manifestDetail']);
		$data['manifestMentah'] = ManifestLintasModel::where("manifestLintas.status",'locked')
					->where("manifestLintas.kendaraan_id",$manifestData->kendaraan_id)
					->where("manifestLintas.karyawan_id",$manifestData->karyawan_id)					
					->where("manifestLintas.status_awb",NULL)
					->get();				
		// $data['manifestDetail'] = ManifestUtamaModel::join("manifestUtama_detail","manifestUtama.code","=","manifestUtama_detail.code")
		// 		->join("manifestLintas","manifestUtama_detail.manifestLintas_id","=","manifestLintas.id")
		// 		->join("manifestLintas_detail","manifestLintas.code","=","manifestLintas_detail.code")
		// 		->join("pengiriman","manifestLintas_detail.pengiriman_id","=","pengiriman.id")
		// 		->leftJoin("kendaraan","manifestUtama.kendaraan_id","=","kendaraan.id")
		// 		->leftJoin("karyawan","manifestUtama.karyawan_id","=","karyawan.id")								
		// 		->where("manifestUtama.id",$id)
		// 		->groupBy("manifestLintas.code")
		// 		->get(['pengiriman.port_asal AS p_asal','pengiriman.port_tujuan AS p_tujuan','manifestUtama_detail.id AS ids','manifestUtama_detail.status AS sta',"karyawan.name as karyawan","kendaraan.name AS kendaraan","manifestUtama.*","manifestLintas.code AS manifest_code"]);				
		return view($this->folder.'/edit',$data)->with('manifest', $manifestData);
	}
	public function open($id, Request $request){
		$data['title']  = "Open & Edit ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = "manifestUtama-list";
		$data['set']    = "open";
		$manifestData = ManifestUtamaModel::find($id);		
		$data['cabang'] = CabangModel::All();		
		$data['kendaraan_id'] = $manifestData->kendaraan_id;
		$data['port_asal'] = $manifestData->port_asal;		
		$data['port_tujuan'] = $manifestData->port_tujuan;		
		$data['port_singgah'] = $request->port_singgah;
		$data['karyawan_id'] = $manifestData->karyawan_id;
		$data['tgl_manifest'] = $manifestData->tgl_manifest;
		$data['keterangan'] = $manifestData->keterangan;				
		$data['kendaraan'] = KendaraanDanPlatModel::where("status",1)->get();       		
		$data['cabang'] = CabangModel::All();
		$data['karyawan'] = KaryawanModel::where("status",1)->get();        		
		$data['manifestDetail'] = ManifestUtamaModel::join("manifestUtama_detail","manifestUtama.code","=","manifestUtama_detail.code")
				->leftJoin("manifestLintas","manifestUtama_detail.manifestLintas_id","=","manifestLintas.id")
				->leftJoin("manifestLintas_detail","manifestLintas_detail.code","=","manifestLintas.code")				
				->leftJoin("pengiriman","manifestLintas_detail.pengiriman_id","=","pengiriman.id")
				->leftJoin("kendaraan","manifestUtama.kendaraan_id","=","kendaraan.id")
				->leftJoin("karyawan","manifestUtama.karyawan_id","=","karyawan.id")								
				->where("manifestUtama.id",$id)				
				->groupBy("manifestUtama_detail.manifestLintas_id")
				->get(["pengiriman.port_asal","pengiriman.port_tujuan",'manifestUtama_detail.status AS status_manifest','manifestUtama_detail.warehouse_at','manifestUtama_detail.id AS ids',"karyawan.name as karyawan","kendaraan.name AS kendaraan","manifestLintas.code AS manifest_code"]);								
		// dd($data['manifestDetail']);		
		$data['manifestMentah'] = ManifestLintasModel::where("manifestLintas.status",'locked')
					->where("manifestLintas.kendaraan_id",$manifestData->kendaraan_id)
					->where("manifestLintas.karyawan_id",$manifestData->karyawan_id)					
					->where("manifestLintas.status_awb",NULL)
					->get();				
		return view($this->folder.'/open',$data)->with('manifest', $manifestData);
	}
	public function dropManifest($id,$port_singgah){
		$user = Auth::user()->getAttributes();
		$data = ManifestUtamaDetailModel::find($id);
		$data->status = "dropped";
		$data->warehouse_by = $user['id'];
		$data->warehouse_at = $data->updated_at = Carbon::now()->toDateTimeString();
		$ids = $data->first()->manifestLintas_id;		

		$codes = $data->first()->code;
		$cekId = ManifestUtamaModel::where("code",$codes)->first();
		$idss = $cekId->id;
		$kendaraan_id = $cekId->kendaraan_id;
		$karyawan_id = $cekId->karyawan_id;
		$port_asal = $cekId->port_asal;
		$port_tujuan = $cekId->port_tujuan;

		$data4 = ManifestLintasModel::find($ids);
		$data4->status_awb = "dropped";          
		$data4->dropped_at = Carbon::now()->toDateTimeString();
		$data4->dropped_by = $user['id'];
		$data4->save();

		$data->save();

		simpanHistory($codes,$ids,$kendaraan_id,$karyawan_id,$port_asal,$port_tujuan,$port_singgah,"penerimaan",$user['id']);
		
		session()->put('msg', setMsg("Successed!","success"));        		
		return redirect()->route('manifestUtama.open',[$idss, 'port_singgah' => $port_singgah])->with('port_singgah', $port_singgah);
	}
	public function deleteDetail($id){
		$data = ManifestUtamaDetailModel::find($id);
		$ids = $data->first()->manifestLintas_id;		

		$data4 = ManifestLintasModel::find($ids);
		$data4->status_awb = NULL;          
		$data4->setAwb_at = NULL;
		$data4->setAwb_by = NULL;
		$data4->save();

		$data->delete();
		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('manifestUtama.edit',$ids);
	}
	public function lock($id){
    $data = ManifestUtamaModel::find($id);
    $data->status = "locked";            
    $data->locked_at = $data->updated_at = Carbon::now()->toDateTimeString();
    $data->save();    
    session()->put('msg', setMsg("Successed!","success"));        
    return redirect()->route('manifestUtama.list');
  }
	public function list(){		
		$data['title']  = $this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = "manifestUtama-list";
		$data['set']    = "view";      
		$data['cabang'] = CabangModel::All(); 
		$data['manifest'] = ManifestUtamaModel::leftJoin('kendaraan', 'kendaraan.id', '=', 'manifestUtama.kendaraan_id')
					->leftJoin('karyawan', 'karyawan.id', '=', 'manifestUtama.karyawan_id')                                        					
					->groupBy("manifestUtama.code")
					->orderBy("manifestUtama.created_at","desc")
					->get(['kendaraan.name AS kendaraan','kendaraan.no_plat','karyawan.name AS karyawan', 'manifestUtama.*']);		
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
		$data['isi']    = "manifestUtama-insert";
		$data['set']    = "generate";        
		$data['kendaraan_id'] = $request->kendaraan_id;
		$data['port_asal'] = $request->port_asal;		
		$data['port_tujuan'] = $request->port_tujuan;		
		$data['karyawan_id'] = $request->karyawan_id;
		$data['tgl_manifest'] = $request->tgl_manifest;
		$data['keterangan'] = $request->keterangan;				
		$data['kendaraan'] = KendaraanDanPlatModel::where("status",1)->get();       		
		$data['karyawan'] = KaryawanModel::where("status",1)->get();            
		$data['cabang'] = CabangModel::All();
		$data['manifest'] = ManifestLintasModel::where("manifestLintas.status",'locked')
					->where("manifestLintas.kendaraan_id",$request->kendaraan_id)
					->where("manifestLintas.karyawan_id",$request->karyawan_id)					
					->where("manifestLintas.status_awb",NULL)
					->get();		
		// dd($data['manifest']);
		return view($this->folder.'/insert',$data);
	}
	public function insert(){       
		$data['title']  = "Tambah ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = "manifestUtama-insert";
		$data['set']    = "insert"; 
		$data['tgl_manifest'] = date("Y-m-d");
		$data['port_asal'] = "";    
		$data['port_tujuan'] = "";    		
		$data['keterangan'] = "";    
		$data['kendaraan_id'] = "";     
		$data['karyawan_id'] = "";      
		$data['kendaraan'] = KendaraanDanPlatModel::where("status",1)->get();       
		$data['cabang'] = CabangModel::All();
		$data['karyawan'] = KaryawanModel::where("status",1)->get();        		
		return view($this->folder.'/insert',$data);
	}
	public function addItem($id,$codes){
		$user = Auth::user()->getAttributes();
		$data = new ManifestUtamaDetailModel();
		$data->code = $codes;
		$data->manifestLintas_id = $id;
		$data->status =  "manifested";
		$data->manifest_at = $waktu = Carbon::now()->toDateTimeString();
		$data->manifest_by = $user['id'];
		$data->save();              

		$data4 = ManifestLintasModel::find($id);
		$data4->status_awb = "manifested";          
		$data4->setAwb_at = Carbon::now()->toDateTimeString();
		$data4->setAwb_by = $user['id'];
		$data4->save();		

		$amb = ManifestUtamaModel::where("code",$codes)->first();
		// dd($amb);
		$manifest_id = $amb->id;
		
		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('manifestUtama.edit',$manifest_id);
	}

	public function cekPort($codes){
		$cekManifest = ManifestUtamaModel::where("code",$codes)->first();				
		$outlet_id = $cekManifest->outlet_id;
		$cekPort = DB::table("outlet")->join("cabang","cabang.id","=","outlet.port_id")
					->where("outlet.id",$outlet_id)->get(['cabang.name AS port']);
		$port = ($cekPort->count()>0)?$cekPort->first()->port:"";												
		return $port;
	}

	public function addManifest($id,$codes,$port_singgah){
		$user = Auth::user()->getAttributes();

		$data = new ManifestUtamaDetailModel();
		$data->code = $codes;
		$data->manifestLintas_id = $id;
		$data->manifest_at = $waktu = Carbon::now()->toDateTimeString();
		$data->manifest_by = $user['id'];
		$data->status =  "manifested";
		$data->save();    

		$data4 = ManifestLintasModel::find($id);
		$data4->status_awb = "manifested";          
		$data4->setAwb_at = Carbon::now()->toDateTimeString();
		$data4->setAwb_by = $user['id'];
		$data4->save();

		$ambilData = $data4->first();

		$cekPengiriman = DB::table("manifestLintas")->join("manifestLintas_detail AS mdl","mdl.code","=","manifestLintas.code")
			->where("manifestLintas.id",$id)->get(["mdl.pengiriman_id"]);
		foreach ($cekPengiriman as $key => $isi){
			$data3 = PengirimanModel::find($isi->pengiriman_id);	
			$data3->status =  "manifest_utama";
			$data3->tgl_warehouse =  Carbon::now()->toDateString();
			$data3->save();
		}		
		
		$idss = ManifestUtamaModel::where("code",$codes)->first()->id;

		simpanHistory($codes,$id,$ambilData->kendaraan_id,$ambilData->karyawan_id,$ambilData->port_asal,$ambilData->port_tujuan,$port_singgah,"pickup",$user['id']);

		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('manifestUtama.open',[$idss, 'port_singgah' => $port_singgah])->with('port_singgah', $port_singgah);
	}
	public function create(Request $request){       
		$user = Auth::user()->getAttributes();
		$data2 = new ManifestUtamaModel();
		$jum         = $request->jum;
		$karyawan_id = $request->karyawan_id;
		$port_asal = $request->port_asal;
		$cekSlugAsal = CabangModel::where("cabang.id",$port_asal);
		$lokasi_asal = ($cekSlugAsal->count()>0)?$cekSlugAsal->first()->slug:"";

		$port_tujuan = $request->port_tujuan;
		$cekSlugTujuan = CabangModel::where("cabang.id",$port_tujuan);
		$lokasi_tujuan = ($cekSlugTujuan->count()>0)?$cekSlugTujuan->first()->slug:"";

		$cekKaryawan = DB::table("karyawan")->where("id",$karyawan_id);
		$nama_karyawan = ($cekKaryawan->count()>0)?$cekKaryawan->first()->name:"";
		// JKTMDN1230701001	
		// $codes = cariKode_awb("manifestUtama",$lokasi_asal,$lokasi_tujuan);	
		$codes = cariKode_manifest("manifestUtama",0,$lokasi_asal,$lokasi_tujuan);	
		$data2->code = $codes;		

		for ($i=1; $i <= $jum; $i++) {                                                          
			if(isset($_POST["pick_".$i])){
				$data = new ManifestUtamaDetailModel();
				$data->code = $codes;
				$data->manifestLintas_id = $ids = $_POST["manifestLintas_id_".$i];                                                                              
				$data->manifest_at = $waktu = Carbon::now()->toDateTimeString();
				$data->manifest_by = $user['id'];
				$data->status =  "manifested";
				$data->save();    

				$data4 = ManifestLintasModel::find($ids);
				$data4->status_awb = "manifested";          
				$data4->setAwb_at = Carbon::now()->toDateTimeString();
				$data4->setAwb_by = $user['id'];
				$data4->save();

				$cekPengiriman = DB::table("manifestLintas")->join("manifestLintas_detail AS mdl","mdl.code","=","manifestLintas.code")
					->where("manifestLintas.id",$ids)->get(["mdl.pengiriman_id"]);
				foreach ($cekPengiriman as $key => $isi){
					$data3 = PengirimanModel::find($isi->pengiriman_id);	
					$data3->status =  "manifest_utama";
					$data3->tgl_warehouse =  Carbon::now()->toDateString();
					$data3->save();
				}						
				
				// simpanStatus($ids,$waktu,"manifest_utama","Paket sudah di-antar ke Port tujuan pada ".$waktu." oleh ".$nama_karyawan);				
			}      
		}   

		simpanHistory($codes,$ids,$request->kendaraan_id,$karyawan_id,$port_asal,$port_tujuan,$port_asal,"pickup",$user['id']);
		

		$data2->tgl_manifest = Carbon::now()->toDateString();		
		$data2->kendaraan_id = $request->kendaraan_id;        
		$data2->karyawan_id = $karyawan_id;        		
		$data2->port_asal = $port_asal;
		$data2->port_tujuan = $port_tujuan;
		$data2->keterangan = $request->keterangan;        		
		$data2->status = "baru";
		$data2->save();

		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('manifestUtama.list');                   
	}
	public function cetak($id){
		$manifestData = ManifestUtamaModel::join("manifestUtama_detail","manifestUtama.code","=","manifestUtama_detail.code")
				->join("manifestLintas","manifestUtama_detail.manifestLintas_id","=","manifestLintas.id")
				->leftJoin("kendaraan","manifestUtama.kendaraan_id","=","kendaraan.id")
				->leftJoin("karyawan","manifestUtama.karyawan_id","=","karyawan.id")				
				->where("manifestUtama.id",$id)
				->get(["karyawan.name as karyawan","kendaraan.name AS kendaraan","manifestUtama.*","manifestUtama.code AS manifest_code"]);
		// return view($this->folder.'/cetak')->with('manifest', $manifestData);
		$pdf = PDF::loadview($this->folder.'/cetak',['manifest'=>$manifestData]);		
		return $pdf->download('cetak-Manifest.pdf');
	}
	public function detail($id){
		$data['title']  = "Detail ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = "manifestUtama-list";
		$data['set']    = "detail";
		$manifestData = ManifestUtamaModel::find($id);		
		$data['cabang'] = CabangModel::All();		
		$data['kendaraan_id'] = $manifestData->kendaraan_id;
		$data['port_asal'] = $manifestData->port_asal;		
		$data['port_tujuan'] = $manifestData->port_tujuan;				
		$data['karyawan_id'] = $manifestData->karyawan_id;
		$data['tgl_manifest'] = $manifestData->tgl_manifest;
		$data['keterangan'] = $manifestData->keterangan;				
		$data['code'] = $manifestData->code;				
		$data['kendaraan'] = KendaraanDanPlatModel::where("status",1)->get();       				
		$data['karyawan'] = KaryawanModel::where("status",1)->get();        		
		$data['manifestDetail'] = ManifestUtamaModel::join("manifestUtama_detail","manifestUtama.code","=","manifestUtama_detail.code")
				->join("manifestLintas","manifestUtama_detail.manifestLintas_id","=","manifestLintas.id")
				->join("manifestLintas_detail","manifestLintas.code","=","manifestLintas_detail.code")
				->join("pengiriman","manifestLintas_detail.pengiriman_id","=","pengiriman.id")
				->leftJoin("kendaraan","manifestUtama.kendaraan_id","=","kendaraan.id")
				->leftJoin("karyawan","manifestUtama.karyawan_id","=","karyawan.id")								
				->where("manifestUtama.id",$id)
				->groupBy("manifestLintas.code")
				->get(['pengiriman.port_asal AS p_asal','pengiriman.port_tujuan AS p_tujuan','manifestUtama_detail.id AS ids','manifestUtama_detail.status AS sta',"karyawan.name as karyawan","kendaraan.name AS kendaraan","manifestUtama.*","manifestLintas.code AS manifest_code"]);				
		return view($this->folder.'/detail',$data)->with('manifest', $manifestData);
	}
	public function riwayat($id){
		$data['title']  = "Riwayat ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = "manifestUtama-list";
		$data['set']    = "riwayat";
		$manifestData = ManifestUtamaModel::find($id);		
		$data['cabang'] = CabangModel::All();		
		$data['kendaraan_id'] = $manifestData->kendaraan_id;
		$data['port_asal'] = $manifestData->port_asal;		
		$data['port_tujuan'] = $manifestData->port_tujuan;				
		$data['karyawan_id'] = $manifestData->karyawan_id;
		$data['tgl_manifest'] = $manifestData->tgl_manifest;
		$data['keterangan'] = $manifestData->keterangan;				
		$data['code'] = $manifestData->code;				
		$data['kendaraan'] = KendaraanDanPlatModel::where("status",1)->get();       				
		$data['karyawan'] = KaryawanModel::where("status",1)->get();        		
		$data['manifestDetail'] = ManifestUtamaModel::join("manifestUtama_detail","manifestUtama.code","=","manifestUtama_detail.code")
				->join("manifestLintas","manifestUtama_detail.manifestLintas_id","=","manifestLintas.id")				
				->leftJoin("kendaraan","manifestUtama.kendaraan_id","=","kendaraan.id")
				->leftJoin("karyawan","manifestUtama.karyawan_id","=","karyawan.id")								
				->where("manifestUtama.id",$id)
				->groupBy("manifestUtama_detail.manifestLintas_id")
				->get(['manifestUtama_detail.status AS status_manifest','manifestUtama_detail.id AS ids',"karyawan.name as karyawan","kendaraan.name AS kendaraan","manifestUtama.*","manifestLintas.code AS manifest_code"])->first();						
		$data['manifestHistory'] = DB::table("manifestUtama_history")
					->leftJoin("manifestLintas","manifestUtama_history.manifestLintas_id","=","manifestLintas.id")
					->where("manifestUtama_history.awb_number",$manifestData->code)->get(["manifestUtama_history.*","manifestLintas.code AS manifest_code"]);
		return view($this->folder.'/riwayat',$data)->with('manifest', $manifestData);
	}
	public function pickup($id){
		$data['title']  = "Pickup ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = "manifestUtama-list";
		$data['set']    = "detail";
		$manifestData = ManifestUtamaModel::find($id);		
		$data['cabang'] = CabangModel::All();		
		$data['kendaraan_id'] = $manifestData->kendaraan_id;
		$data['port_asal'] = $manifestData->port_asal;		
		$data['port_tujuan'] = $manifestData->port_tujuan;				
		$data['karyawan_id'] = $manifestData->karyawan_id;
		$data['tgl_manifest'] = $manifestData->tgl_manifest;
		$data['keterangan'] = $manifestData->keterangan;				
		$data['kendaraan'] = KendaraanDanPlatModel::where("status",1)->get();       		
		$data['cabang'] = CabangModel::All();		
		$data['karyawan'] = KaryawanModel::where("status",1)->get();        		
		$data['manifestDetail'] = ManifestUtamaModel::join("manifestUtama_detail","manifestUtama.code","=","manifestUtama_detail.code")
				->join("pengiriman","pengiriman.id","=","manifestUtama_detail.pengiriman_id")
				->leftJoin('jenis_tarif', 'jenis_tarif.id', '=', 'pengiriman.jenis_tarif_id')
				->leftJoin("kendaraan","manifestUtama.kendaraan_id","=","kendaraan.id")
				->leftJoin("karyawan","manifestUtama.karyawan_id","=","karyawan.id")				
				->where("manifestUtama.id",$id)
				->get(['manifestUtama_detail.status AS sta','manifestUtama_detail.id AS ids','jenis_tarif.name AS jenis_tarif',"karyawan.name as karyawan","kendaraan.name AS kendaraan","manifestUtama.*","manifestUtama.code AS manifest_code","pengiriman.code AS kode_tr","pengiriman.*"]);		
		return view($this->folder.'/pickup',$data)->with('manifest', $manifestData);
	}

	public function updatePickup(Request $request){       
		$user = Auth::user()->getAttributes();		
		$jum         = $request->jum;		
		for ($i=1; $i <= $jum; $i++) {                                                          
			if(isset($_POST["pick_".$i])){
				$manifestUtama_detail_id = $_POST["manifestUtama_detail_id_".$i];                                                                              
				$data = ManifestUtamaDetailModel::find($manifestUtama_detail_id);								
				$codes = $data->code;
				$ids = $data->pengiriman_id;
				$data->updated_at = $data->pickup_at = $waktu = Carbon::now()->toDateTimeString();
				$data->pickup_by = $user['id'];
				$data->status =  "pickup";
				$data->status_utama =  "manifest_lintas_pickup";
				$data->save();             	

				$cekManifest = ManifestUtamaModel::where("code",$codes)->first();				
				$karyawan_id = $cekManifest->karyawan_id;
				$cekKaryawan = DB::table("karyawan")->where("id",$karyawan_id);
				$nama_karyawan = ($cekKaryawan->count()>0)?$cekKaryawan->first()->name:"";												

				simpanStatus($ids,$waktu,"manifest_lintas_pickup","Paket telah dibawa oleh kurir ".$nama_karyawan." ke Port Tujuan pada ".$waktu);
			}      
		}   		

		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('manifestUtama.list');                   
	}


	public function penerimaan($id){
		$data['title']  = "Penerimaan Gudang ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = "manifestUtama-list";
		$data['set']    = "detail";
		$manifestData = ManifestUtamaModel::find($id);		
		$data['cabang'] = CabangModel::All();		
		$data['kendaraan_id'] = $manifestData->kendaraan_id;
		$data['port_asal'] = $manifestData->port_asal;		
		$data['port_tujuan'] = $manifestData->port_tujuan;				
		$data['karyawan_id'] = $manifestData->karyawan_id;
		$data['tgl_manifest'] = $manifestData->tgl_manifest;
		$data['keterangan'] = $manifestData->keterangan;				
		$data['kendaraan'] = KendaraanDanPlatModel::where("status",1)->get();       				
		$data['karyawan'] = KaryawanModel::where("status",1)->get();        		
		$data['manifestDetail'] = ManifestUtamaModel::join("manifestUtama_detail","manifestUtama.code","=","manifestUtama_detail.code")
				->join("pengiriman","pengiriman.id","=","manifestUtama_detail.pengiriman_id")
				->leftJoin('jenis_tarif', 'jenis_tarif.id', '=', 'pengiriman.jenis_tarif_id')
				->leftJoin("kendaraan","manifestUtama.kendaraan_id","=","kendaraan.id")
				->leftJoin("karyawan","manifestUtama.karyawan_id","=","karyawan.id")				
				->where("manifestUtama.id",$id)
				->get(['manifestUtama_detail.status AS sta','manifestUtama_detail.id AS ids','jenis_tarif.name AS jenis_tarif',"karyawan.name as karyawan","kendaraan.name AS kendaraan","manifestUtama.*","manifestUtama.code AS manifest_code","pengiriman.code AS kode_tr","pengiriman.*"]);		
		return view($this->folder.'/penerimaan',$data)->with('manifest', $manifestData);
	}

	public function updatePenerimaan(Request $request){       
		$user = Auth::user()->getAttributes();		
		$jum         = $request->jum;		
		for ($i=1; $i <= $jum; $i++) {                                                          
			if(isset($_POST["pick_".$i])){
				$manifestUtama_detail_id = $_POST["manifestUtama_detail_id_".$i];                                                                              
				$data = ManifestUtamaDetailModel::find($manifestUtama_detail_id);								
				$codes = $data->code;
				$ids = $data->pengiriman_id;
				$data->updated_at = $data->pickup_at = $waktu = Carbon::now()->toDateTimeString();
				$data->pickup_by = $user['id'];
				$data->status =  "penerimaan";
				$data->status_utama =  "manifest_lintas_penerimaan";
				$data->save();             											

				$cekManifest = ManifestUtamaModel::where("code",$codes)->first();				
				$outlet_id = $cekManifest->outlet_id;
				$cekPort = DB::table("outlet")->join("cabang","cabang.id","=","outlet.port_id")
							->where("outlet.id",$outlet_id)->get(['cabang.name AS port']);
				$port = ($cekPort->count()>0)?$cekPort->first()->port:"";												

				simpanStatus($ids,$waktu,"manifest_lintas_penerimaan","Paket telah sampai di Port Tujuan ".$port." pada ".$waktu);
			}      
		}   		

		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('manifestUtama.list');                   
	}
}


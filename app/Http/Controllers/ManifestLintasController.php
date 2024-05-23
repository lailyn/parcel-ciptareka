<?php

namespace App\Http\Controllers;

use App\Models\ManifestLintasModel;
use App\Models\ManifestJemputModel;
use App\Models\ManifestLintasDetailModel;
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

class ManifestLintasController extends Controller
{
	var $set    		= "manifestLintas";
	var $title      = "Manifest Lintas";
	var $subtitle   = "Manifest Lintas";
	var $folder 		= "transaksi/manifestLintas";

	public function edit($id){
		$data['title']  = "Edit ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = "manifestLintas-list";
		$data['set']    = "edit";
		$manifestData = ManifestLintasModel::find($id);		
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
		$data['manifestDetail'] = ManifestLintasModel::join("manifestLintas_detail","manifestLintas.code","=","manifestLintas_detail.code")
				->join("pengiriman","pengiriman.id","=","manifestLintas_detail.pengiriman_id")
				->leftJoin('jenis_tarif', 'jenis_tarif.id', '=', 'pengiriman.jenis_tarif_id')
				->leftJoin("kendaraan","manifestLintas.kendaraan_id","=","kendaraan.id")
				->leftJoin("karyawan","manifestLintas.karyawan_id","=","karyawan.id")				
				->where("manifestLintas.id",$id)
				->get(['manifestLintas_detail.id AS ids','jenis_tarif.name AS jenis_tarif',"karyawan.name as karyawan","kendaraan.name AS kendaraan","manifestLintas.*","manifestLintas.code AS manifest_code","pengiriman.code AS kode_tr","pengiriman.*"]);
		$data['manifestMentah'] = ManifestJemputModel::join('manifestJemput_detail AS mjd', 'mjd.code', '=', 'manifestJemput.code')                                                          															
					->join("pengiriman","mjd.pengiriman_id","=","pengiriman.id")
					->leftJoin('jenis_tarif', 'jenis_tarif.id', '=', 'pengiriman.jenis_tarif_id')
					->where("mjd.status",'penerimaan')->where("pengiriman.port_asal",$manifestData->port_asal)
					->where("pengiriman.port_tujuan",$manifestData->port_tujuan)
					->where("pengiriman.status","manifest_jemput")					
					->get(['jenis_tarif.name AS jenis_tarif','manifestJemput.*','pengiriman.code AS kode_tr','pengiriman.*']);		
		return view($this->folder.'/edit',$data)->with('manifest', $manifestData);
	}
	public function deleteDetail($id){
		$data = ManifestLintasDetailModel::find($id);
		$ids = $data->first()->code;
		$pengiriman_id = $data->first()->pengiriman_id;
		$id = ManifestLintasModel::where("code",$ids)->first()->id;

		$data3 = PengirimanModel::find($pengiriman_id);
		$data3->status =  "manifest_jemput";
		$data3->tgl_manifest_lintas =  NULL;
		$data3->save();

		hapusStatus($pengiriman_id,"manifest_jemput");

		$data->delete();
		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('manifestLintas.edit',$id);
	}
	public function lock($id){
    $data = ManifestLintasModel::find($id);
    $data->status = "locked";            
    $data->locked_at = $data->updated_at = Carbon::now()->toDateTimeString();
    $data->save();    
    session()->put('msg', setMsg("Successed!","success"));        
    return redirect()->route('manifestLintas.list');
  }
	public function list(){		
		$data['title']  = $this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = "manifestLintas-list";
		$data['set']    = "view";       
		$data['manifest'] = ManifestLintasModel::leftJoin('kendaraan', 'kendaraan.id', '=', 'manifestLintas.kendaraan_id')
					->leftJoin('karyawan', 'karyawan.id', '=', 'manifestLintas.karyawan_id')                                        					
					->groupBy("manifestLintas.code")
					->orderBy("manifestLintas.created_at","desc")
					->get(['kendaraan.name AS kendaraan','kendaraan.no_plat','karyawan.name AS karyawan', 'manifestLintas.*']);		
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
		$data['isi']    = "manifestLintas-insert";
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
		$data['manifest'] = ManifestJemputModel::join('manifestJemput_detail AS mjd', 'mjd.code', '=', 'manifestJemput.code')                                                          															
					->join("pengiriman","mjd.pengiriman_id","=","pengiriman.id")
					->leftJoin('jenis_tarif', 'jenis_tarif.id', '=', 'pengiriman.jenis_tarif_id')
					->where("mjd.status",'penerimaan')->where("pengiriman.port_asal",$request->port_asal)
					->where("pengiriman.port_tujuan",$request->port_tujuan)
					->where("pengiriman.status","manifest_jemput")					
					->get(['jenis_tarif.name AS jenis_tarif','manifestJemput.*','pengiriman.code AS kode_tr','pengiriman.*']);
		return view($this->folder.'/insert',$data);
	}
	public function insert(){       
		$data['title']  = "Tambah ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = "manifestLintas-insert";
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
		$data = new ManifestLintasDetailModel();
		$data->code = $codes;
		$data->pengiriman_id = $id;
		$data->status =  "manifested";
		$data->status_utama =  "manifest_lintas";
		$data->manifest_at = $waktu = Carbon::now()->toDateTimeString();
		$data->manifest_by = $user['id'];
		$data->save();              

		$data3 = PengirimanModel::find($id);
		$data3->status =  "manifest_lintas";
		$data3->tgl_manifest_lintas =  Carbon::now()->toDateString();
		$data3->save();
			
		$cekManifest = ManifestLintasModel::where("code",$codes)->first();
		$manifest_id = $cekManifest->id;
		$karyawan_id = $cekManifest->karyawan_id;
		$cekKaryawan = DB::table("karyawan")->where("id",$karyawan_id);
		$nama_karyawan = ($cekKaryawan->count()>0)?$cekKaryawan->first()->name:"";		

		simpanStatus($id,$waktu,"manifest_lintas","Paket sudah di-antar ke Port tujuan pada ".$waktu." oleh ".$nama_karyawan);
		
		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('manifestLintas.edit',$manifest_id);
	}

	public function cekPort($codes){
		$cekManifest = ManifestLintasModel::where("code",$codes)->first();				
		$outlet_id = $cekManifest->outlet_id;
		$cekPort = DB::table("outlet")->join("cabang","cabang.id","=","outlet.port_id")
					->where("outlet.id",$outlet_id)->get(['cabang.name AS port']);
		$port = ($cekPort->count()>0)?$cekPort->first()->port:"";												
		return $port;
	}

	public function create(Request $request){       
		$user = Auth::user()->getAttributes();
		$data2 = new ManifestLintasModel();
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
		$codes = cariKode_manifest("manifestLintas",2,$lokasi_asal,$lokasi_tujuan);	
		$data2->code = $codes;		

		for ($i=1; $i <= $jum; $i++) {                                                          
			if(isset($_POST["pick_".$i])){
				$data = new ManifestLintasDetailModel();
				$data->code = $codes;
				$data->pengiriman_id = $ids = $_POST["pengiriman_id_".$i];                                                                              
				$data->manifest_at = $waktu = Carbon::now()->toDateTimeString();
				$data->manifest_by = $user['id'];
				$data->status =  "manifested";
				$data->status_utama =  "manifest_lintas";
				$data->save();              

				$data3 = PengirimanModel::find($ids);
				$data3->status =  "manifest_lintas";
				$data3->tgl_manifest_lintas =  Carbon::now()->toDateString();
				$data3->save();
				
				simpanStatus($ids,$waktu,"manifest_lintas","Paket sudah di-antar ke Port tujuan pada ".$waktu." oleh ".$nama_karyawan);
			}      
		}   
		

		$data2->tgl_manifest = Carbon::now()->toDateString();
		$data2->total_paket = $jum;
		$data2->kendaraan_id = $request->kendaraan_id;        
		$data2->karyawan_id = $karyawan_id;        		
		$data2->port_asal = $port_asal;
		$data2->port_tujuan = $port_tujuan;
		$data2->keterangan = $request->keterangan;        		
		$data2->status = "baru";
		$data2->save();

		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('manifestLintas.list');                   
	}
	public function cetak($id){
		$manifestData = ManifestLintasModel::join("manifestLintas_detail","manifestLintas.code","=","manifestLintas_detail.code")
				->join("pengiriman","pengiriman.id","=","manifestLintas_detail.pengiriman_id")
				->leftJoin('jenis_tarif', 'jenis_tarif.id', '=', 'pengiriman.jenis_tarif_id')
				->leftJoin("kendaraan","manifestLintas.kendaraan_id","=","kendaraan.id")
				->leftJoin("karyawan","manifestLintas.karyawan_id","=","karyawan.id")				
				->where("manifestLintas.id",$id)
				->get(["karyawan.name as karyawan",'jenis_tarif.name AS jenis_tarif',"kendaraan.name AS kendaraan","manifestLintas.*","manifestLintas.code AS manifest_code","pengiriman.code AS kode_tr","pengiriman.*"]);
		// return view($this->folder.'/cetak')->with('manifest', $manifestData);
		$pdf = PDF::loadview($this->folder.'/cetak',['manifest'=>$manifestData]);		
		return $pdf->download('cetak-Manifest.pdf');
	}
	public function detail($id){
		$data['title']  = "Detail ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = "manifestLintas-list";
		$data['set']    = "detail";
		$manifestData = ManifestLintasModel::find($id);		
		$data['cabang'] = CabangModel::All();		
		$data['kendaraan_id'] = $manifestData->kendaraan_id;
		$data['port_asal'] = $manifestData->port_asal;		
		$data['port_tujuan'] = $manifestData->port_tujuan;				
		$data['karyawan_id'] = $manifestData->karyawan_id;
		$data['tgl_manifest'] = $manifestData->tgl_manifest;
		$data['keterangan'] = $manifestData->keterangan;				
		$data['kendaraan'] = KendaraanDanPlatModel::where("status",1)->get();       				
		$data['karyawan'] = KaryawanModel::where("status",1)->get();        		
		$data['manifestDetail'] = ManifestLintasModel::join("manifestLintas_detail","manifestLintas.code","=","manifestLintas_detail.code")
				->join("pengiriman","pengiriman.id","=","manifestLintas_detail.pengiriman_id")
				->leftJoin('jenis_tarif', 'jenis_tarif.id', '=', 'pengiriman.jenis_tarif_id')
				->leftJoin("kendaraan","manifestLintas.kendaraan_id","=","kendaraan.id")
				->leftJoin("karyawan","manifestLintas.karyawan_id","=","karyawan.id")								
				->where("manifestLintas.id",$id)
				->get(['manifestLintas_detail.id AS ids','manifestLintas_detail.status AS sta','jenis_tarif.name AS jenis_tarif',"karyawan.name as karyawan","kendaraan.name AS kendaraan","manifestLintas.*","manifestLintas.code AS manifest_code","pengiriman.code AS kode_tr","pengiriman.*"]);		
		return view($this->folder.'/detail',$data)->with('manifest', $manifestData);
	}
	public function pickup($id){
		$data['title']  = "Pickup ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = "manifestLintas-list";
		$data['set']    = "detail";
		$manifestData = ManifestLintasModel::find($id);		
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
		$data['manifestDetail'] = ManifestLintasModel::join("manifestLintas_detail","manifestLintas.code","=","manifestLintas_detail.code")
				->join("pengiriman","pengiriman.id","=","manifestLintas_detail.pengiriman_id")
				->leftJoin('jenis_tarif', 'jenis_tarif.id', '=', 'pengiriman.jenis_tarif_id')
				->leftJoin("kendaraan","manifestLintas.kendaraan_id","=","kendaraan.id")
				->leftJoin("karyawan","manifestLintas.karyawan_id","=","karyawan.id")				
				->where("manifestLintas.id",$id)
				->get(['manifestLintas_detail.status AS sta','manifestLintas_detail.id AS ids','jenis_tarif.name AS jenis_tarif',"karyawan.name as karyawan","kendaraan.name AS kendaraan","manifestLintas.*","manifestLintas.code AS manifest_code","pengiriman.code AS kode_tr","pengiriman.*"]);		
		return view($this->folder.'/pickup',$data)->with('manifest', $manifestData);
	}

	public function updatePickup(Request $request){       
		$user = Auth::user()->getAttributes();		
		$jum         = $request->jum;		
		for ($i=1; $i <= $jum; $i++) {                                                          
			if(isset($_POST["pick_".$i])){
				$manifestLintas_detail_id = $_POST["manifestLintas_detail_id_".$i];                                                                              
				$data = ManifestLintasDetailModel::find($manifestLintas_detail_id);								
				$codes = $data->code;
				$ids = $data->pengiriman_id;
				$data->updated_at = $data->pickup_at = $waktu = Carbon::now()->toDateTimeString();
				$data->pickup_by = $user['id'];
				$data->status =  "pickup";
				$data->status_utama =  "manifest_lintas_pickup";
				$data->save();             	

				$cekManifest = ManifestLintasModel::where("code",$codes)->first();				
				$karyawan_id = $cekManifest->karyawan_id;
				$cekKaryawan = DB::table("karyawan")->where("id",$karyawan_id);
				$nama_karyawan = ($cekKaryawan->count()>0)?$cekKaryawan->first()->name:"";												

				simpanStatus($ids,$waktu,"manifest_lintas_pickup","Paket telah dibawa oleh kurir ".$nama_karyawan." ke Port Tujuan pada ".$waktu);
			}      
		}   		

		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('manifestLintas.list');                   
	}


	public function penerimaan($id){
		$data['title']  = "Penerimaan Gudang ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = "manifestLintas-list";
		$data['set']    = "detail";
		$manifestData = ManifestLintasModel::find($id);		
		$data['cabang'] = CabangModel::All();		
		$data['kendaraan_id'] = $manifestData->kendaraan_id;
		$data['port_asal'] = $manifestData->port_asal;		
		$data['port_tujuan'] = $manifestData->port_tujuan;				
		$data['karyawan_id'] = $manifestData->karyawan_id;
		$data['tgl_manifest'] = $manifestData->tgl_manifest;
		$data['keterangan'] = $manifestData->keterangan;				
		$data['kendaraan'] = KendaraanDanPlatModel::where("status",1)->get();       				
		$data['karyawan'] = KaryawanModel::where("status",1)->get();        		
		$data['manifestDetail'] = ManifestLintasModel::join("manifestLintas_detail","manifestLintas.code","=","manifestLintas_detail.code")
				->join("pengiriman","pengiriman.id","=","manifestLintas_detail.pengiriman_id")
				->leftJoin('jenis_tarif', 'jenis_tarif.id', '=', 'pengiriman.jenis_tarif_id')
				->leftJoin("kendaraan","manifestLintas.kendaraan_id","=","kendaraan.id")
				->leftJoin("karyawan","manifestLintas.karyawan_id","=","karyawan.id")				
				->where("manifestLintas.id",$id)
				->get(['manifestLintas_detail.status AS sta','manifestLintas_detail.id AS ids','jenis_tarif.name AS jenis_tarif',"karyawan.name as karyawan","kendaraan.name AS kendaraan","manifestLintas.*","manifestLintas.code AS manifest_code","pengiriman.code AS kode_tr","pengiriman.*"]);		
		return view($this->folder.'/penerimaan',$data)->with('manifest', $manifestData);
	}

	public function updatePenerimaan(Request $request){       
		$user = Auth::user()->getAttributes();		
		$jum         = $request->jum;		
		for ($i=1; $i <= $jum; $i++) {                                                          
			if(isset($_POST["pick_".$i])){
				$manifestLintas_detail_id = $_POST["manifestLintas_detail_id_".$i];                                                                              
				$data = ManifestLintasDetailModel::find($manifestLintas_detail_id);								
				$codes = $data->code;
				$ids = $data->pengiriman_id;
				$data->updated_at = $data->pickup_at = $waktu = Carbon::now()->toDateTimeString();
				$data->pickup_by = $user['id'];
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
		}   		

		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('manifestLintas.list');                   
	}
}


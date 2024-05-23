<?php

namespace App\Http\Controllers;

use App\Models\PaketModel;
use App\Models\PeriodeModel;
use App\Models\PaketDetailModel;
use App\Models\ManifestLintasDetailModel;
use App\Models\KaryawanModel;
use App\Models\CabangModel;
use App\Models\KendaraanDanPlatModel;
use App\Models\KaryawanCakupanModel;
use App\Models\PengirimanModel;
use App\Models\OutletModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use PDF;
use DB;

class PaketController extends Controller
{
	var $set    		= "paket";
	var $title      = "Master Paket";
	var $subtitle   = "Master Paket";
	var $folder 		= "master/paket";
	public function index(){
		$paketData = PaketModel::join("periode","paket.periode_id","=","periode.id")->get(["paket.*","periode.name AS periode"]);
		$data['title']  = $this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "view";		
		return view($this->folder.'/index',$data)->with('paket', $paketData);
	}
	public function insert(){       
		$data['title']  = "Tambah ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = "paket";
		$data['set']    = "insert"; 
		$data['periode'] = PeriodeModel::where("status",1)->get();		
		return view($this->folder.'/insert',$data);
	}
	public function create(Request $request){       		
		$data2 = new PaketModel();			
		$data2->created_at = Carbon::now()->toDateString();
		$data2->name = $request->name;
		$data2->jenis_paket = $request->jenis_paket;        
		$data2->periode_id = $request->periode_id;        						
		$data2->iuran = $request->iuran;        		
		$data2->tgl_awal = $request->tgl_awal;        		
		$data2->tgl_akhir = $request->tgl_akhir;        		
		$data2->lama_iuran = $request->lama_iuran;        		
		$data2->jenis_lama = $request->jenis_lama;        		
		$data2->status =  (!is_null($request->status))?$request->status:0;               
		$data2->save();

		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('paket.index');                   
	}
	public function update($id, Request $request){       
		
		$data2 = PaketModel::find($id);			
		$data2->updated_at = Carbon::now()->toDateString();
		$data2->name = $request->name;
		$data2->jenis_paket = $request->jenis_paket;        
		$data2->periode_id = $request->periode_id;        						
		$data2->iuran = $request->iuran;        		
		$data2->tgl_awal = $request->tgl_awal;        		
		$data2->tgl_akhir = $request->tgl_akhir;        		
		$data2->lama_iuran = $request->lama_iuran;        		
		$data2->jenis_lama = $request->jenis_lama;        		
		$data2->status =  (!is_null($request->status))?$request->status:0;               
		$data2->save();

		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('paket.index');                   
	}


	public function edit($id){
		$data['title']  = "Edit ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = "insert";
		$data['set']    = "edit";
		$paketData = PaketModel::find($id);		
		$data['periode'] = PeriodeModel::where("status",1)->get();		
		return view($this->folder.'/insert',$data)->with('paket', $paketData);
	}



	public function deleteDetail($id){
		$data = PaketDetailModel::find($id);
		$ids = $data->first()->code;
		$pengiriman_id = $data->first()->pengiriman_id;
		$id = PaketModel::where("code",$ids)->first()->id;

		$data3 = PengirimanModel::find($pengiriman_id);
		$data3->status =  "manifest_lintas";
		$data3->tgl_manifest_antar =  NULL;
		$data3->save();

		hapusStatus($pengiriman_id,"manifest_lintas");

		$data->delete();
		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('paket.edit',$id);
	}	
	public function list(){		
		$data['title']  = $this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = "paket-list";
		$data['set']    = "view";       
		$data['manifest'] = PaketModel::leftJoin('kendaraan', 'kendaraan.id', '=', 'paket.kendaraan_id')
					->leftJoin('karyawan', 'karyawan.id', '=', 'paket.karyawan_id')                                        					
					->groupBy("paket.code")
					->orderBy("paket.created_at","desc")
					->get(['kendaraan.name AS kendaraan','kendaraan.no_plat','karyawan.name AS karyawan', 'paket.*']);		
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
		$data['isi']    = "paket-insert";
		$data['set']    = "generate";        
		$data['port_tujuan'] = $request->port_tujuan;		
		$data['kendaraan_id'] = $request->kendaraan_id;		
		$data['karyawan_id'] = $request->karyawan_id;
		$data['tgl_manifest'] = $request->tgl_manifest;
		$data['keterangan'] = $request->keterangan;				
		$data['kendaraan'] = KendaraanDanPlatModel::where("status",1)->get();       		
		$data['karyawan'] = KaryawanModel::where("status",1)->get();            
		$data['cabang'] = CabangModel::All();
		$cek = KaryawanCakupanModel::where("karyawan_id",$request->karyawan_id)->get();        
		$where = "";$data_camat = "";$datas="";
		if(!empty($cek)){
			foreach($cek AS $rt){                
				if($data_camat!='') $data_camat.=",";
				$data_camat .= $rt->kecamatan_id;

				$kecamatan = cariDaerah_helper($rt->kecamatan_id,'kec');                                                                            
				if($datas!='') $datas.=", ";
				$datas .= $kecamatan;
			}          
			$where = "pengiriman.id_kecamatan_penerima IN ($data_camat)";
		}
		$data['cakupan'] = $datas;
		$data['manifest'] = ManifestLintasModel::join('manifestLintas_detail AS mjd', 'mjd.code', '=', 'manifestLintas.code')                                                          															
					->join("pengiriman","mjd.pengiriman_id","=","pengiriman.id")
					->leftJoin('jenis_tarif', 'jenis_tarif.id', '=', 'pengiriman.jenis_tarif_id')
					->where("mjd.status",'penerimaan')					
					->where("pengiriman.status","manifest_utama")					
					->whereRaw($where)
					->get(['jenis_tarif.name AS jenis_tarif','manifestLintas.*','pengiriman.code AS kode_tr','pengiriman.*']);
		return view($this->folder.'/insert',$data);
	}
	
	public function cekCakupan(Request $request){
		$data = "";
		$karyawan_id = $request->karyawan_id;
		$cek = KaryawanCakupanModel::where("karyawan_id",$karyawan_id)->get();        
		if(!empty($cek)){
			foreach($cek AS $rt){
				$kecamatan = cariDaerah_helper($rt->kecamatan_id,'kec');                                                                            
				if($data!='') $data.=", ";
				$data .= $kecamatan;
			}                    
		}
		return $data;
	}
	public function addItem($id,$codes){
		$user = Auth::user()->getAttributes();
		$data = new PaketDetailModel();
		$data->code = $codes;
		$data->pengiriman_id = $id;
		$data->status =  "manifested";
		$data->status_utama =  "manifest_antar";
		$data->dikirim_at = $waktu = Carbon::now()->toDateTimeString();
		$data->dikirim_by = $user['id'];
		$data->save();              

		$data3 = PengirimanModel::find($id);
		$data3->status =  "manifest_antar";
		$data3->tgl_manifest_antar =  Carbon::now()->toDateString();
		$data3->save();
			
		$cekManifest = PaketModel::where("code",$codes)->first();
		$manifest_id = $cekManifest->id;
		$karyawan_id = $cekManifest->karyawan_id;
		$cekKaryawan = DB::table("karyawan")->where("id",$karyawan_id);
		$nama_karyawan = ($cekKaryawan->count()>0)?$cekKaryawan->first()->name:"";		

		simpanStatus($id,$waktu,"manifest_antar","Paket sudah diserahkan ke Kurir pada ".$waktu);
		
		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('paket.edit',$manifest_id);
	}

	public function cekPort($codes){
		$cekManifest = PaketModel::where("code",$codes)->first();				
		$outlet_id = $cekManifest->outlet_id;
		$cekPort = DB::table("outlet")->join("cabang","cabang.id","=","outlet.port_id")
					->where("outlet.id",$outlet_id)->get(['cabang.name AS port']);
		$port = ($cekPort->count()>0)?$cekPort->first()->port:"";												
		return $port;
	}

	
	public function cetak($id){
		$manifestData = PaketModel::join("paket_detail","paket.code","=","paket_detail.code")
				->join("pengiriman","pengiriman.id","=","paket_detail.pengiriman_id")
				->leftJoin('jenis_tarif', 'jenis_tarif.id', '=', 'pengiriman.jenis_tarif_id')
				->leftJoin("kendaraan","paket.kendaraan_id","=","kendaraan.id")
				->leftJoin("karyawan","paket.karyawan_id","=","karyawan.id")				
				->where("paket.id",$id)
				->get(["karyawan.name as karyawan",'jenis_tarif.name AS jenis_tarif',"kendaraan.name AS kendaraan","paket.*","paket.code AS manifest_code","pengiriman.code AS kode_tr","pengiriman.*"]);
		// return view($this->folder.'/cetak')->with('manifest', $manifestData);
		$pdf = PDF::loadview($this->folder.'/cetak',['manifest'=>$manifestData]);		
		return $pdf->download('cetak-Manifest.pdf');
	}
	public function detail($id){
		$data['title']  = "Detail ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = "paket-list";
		$data['set']    = "detail";
		$manifestData = PaketModel::find($id);		
		$data['cabang'] = CabangModel::All();		
		$data['kendaraan_id'] = $manifestData->kendaraan_id;
		$cek = KaryawanCakupanModel::where("karyawan_id",$manifestData->karyawan_id)->get();        
		$where = "";$data_camat = "";$datas="";
		if(!empty($cek)){
			foreach($cek AS $rt){                
				if($data_camat!='') $data_camat.=",";
				$data_camat .= $rt->kecamatan_id;

				$kecamatan = cariDaerah_helper($rt->kecamatan_id,'kec');                                                                            
				if($datas!='') $datas.=", ";
				$datas .= $kecamatan;
			}          
			$where = "pengiriman.id_kelurahan_penerima IN ($data_camat)";
		}
		$data['cakupan'] = $datas;
		$data['port_tujuan'] = $manifestData->port_tujuan;				
		$data['karyawan_id'] = $manifestData->karyawan_id;
		$data['tgl_manifest'] = $manifestData->tgl_manifest;
		$data['keterangan'] = $manifestData->keterangan;				
		$data['kendaraan'] = KendaraanDanPlatModel::where("status",1)->get();       				
		$data['karyawan'] = KaryawanModel::where("status",1)->get();        		
		$data['manifestDetail'] = PaketModel::join("paket_detail","paket.code","=","paket_detail.code")
				->join("pengiriman","pengiriman.id","=","paket_detail.pengiriman_id")
				->leftJoin('jenis_tarif', 'jenis_tarif.id', '=', 'pengiriman.jenis_tarif_id')
				->leftJoin("kendaraan","paket.kendaraan_id","=","kendaraan.id")
				->leftJoin("karyawan","paket.karyawan_id","=","karyawan.id")								
				->where("paket.id",$id)
				->get(['paket_detail.id AS ids','paket_detail.status AS sta','jenis_tarif.name AS jenis_tarif',"karyawan.name as karyawan","kendaraan.name AS kendaraan","paket.*","paket.code AS manifest_code","pengiriman.code AS kode_tr","pengiriman.*"]);		
		return view($this->folder.'/detail',$data)->with('manifest', $manifestData);
	}
	public function pickup($id){
		$data['title']  = "Pickup ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = "paket-list";
		$data['set']    = "detail";
		$manifestData = PaketModel::find($id);		
		$data['cabang'] = CabangModel::All();		
		$data['kendaraan_id'] = $manifestData->kendaraan_id;
		$data['port_asal'] = $manifestData->port_asal;		
		$data['kendaraan_id'] = $manifestData->kendaraan_id;
		$cek = KaryawanCakupanModel::where("karyawan_id",$manifestData->karyawan_id)->get();        
		$where = "";$data_camat = "";$datas="";
		if(!empty($cek)){
			foreach($cek AS $rt){                
				if($data_camat!='') $data_camat.=",";
				$data_camat .= $rt->kecamatan_id;

				$kecamatan = cariDaerah_helper($rt->kecamatan_id,'kec');                                                                            
				if($datas!='') $datas.=", ";
				$datas .= $kecamatan;
			}          
			$where = "pengiriman.id_kelurahan_penerima IN ($data_camat)";
		}
		$data['cakupan'] = $datas;
		$data['port_tujuan'] = $manifestData->port_tujuan;				
		$data['karyawan_id'] = $manifestData->karyawan_id;
		$data['tgl_manifest'] = $manifestData->tgl_manifest;
		$data['keterangan'] = $manifestData->keterangan;				
		$data['kendaraan'] = KendaraanDanPlatModel::where("status",1)->get();       		
		$data['cabang'] = CabangModel::All();		
		$data['karyawan'] = KaryawanModel::where("status",1)->get();        		
		$data['manifestDetail'] = PaketModel::join("paket_detail","paket.code","=","paket_detail.code")
				->join("pengiriman","pengiriman.id","=","paket_detail.pengiriman_id")
				->leftJoin('jenis_tarif', 'jenis_tarif.id', '=', 'pengiriman.jenis_tarif_id')
				->leftJoin("kendaraan","paket.kendaraan_id","=","kendaraan.id")
				->leftJoin("karyawan","paket.karyawan_id","=","karyawan.id")				
				->where("paket.id",$id)
				->get(['paket_detail.status AS sta','paket_detail.id AS ids','jenis_tarif.name AS jenis_tarif',"karyawan.name as karyawan","kendaraan.name AS kendaraan","paket.*","paket.code AS manifest_code","pengiriman.code AS kode_tr","pengiriman.*"]);		
		return view($this->folder.'/pickup',$data)->with('manifest', $manifestData);
	}

	public function updatePickup(Request $request){       
		$user = Auth::user()->getAttributes();		
		$jum         = $request->jum;		
		for ($i=1; $i <= $jum; $i++) {                                                          
			if(isset($_POST["pick_".$i])){
				$paket_detail_id = $_POST["paket_detail_id_".$i];                                                                              
				$data = PaketDetailModel::find($paket_detail_id);								
				$codes = $data->code;
				$ids = $data->pengiriman_id;
				$data->updated_at = $data->pickup_at = $waktu = Carbon::now()->toDateTimeString();
				$data->pickup_by = $user['id'];
				$data->status =  "pickup";
				$data->status_utama =  "manifest_antar_pickup";
				$data->save();             	

				$cekManifest = PaketModel::where("code",$codes)->first();				
				$karyawan_id = $cekManifest->karyawan_id;
				$cekKaryawan = DB::table("karyawan")->where("id",$karyawan_id);
				$nama_karyawan = ($cekKaryawan->count()>0)?$cekKaryawan->first()->name:"";												

				simpanStatus($ids,$waktu,"manifest_antar_pickup","Paket telah dibawa oleh kurir ".$nama_karyawan." ke alamat Tujuan pada ".$waktu);
			}      
		}   		

		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('paket.list');                   
	}


	public function penyerahan($id){
		$data['title']  = "Penerimaan Gudang ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = "paket-list";
		$data['set']    = "detail";
		$manifestData = PaketModel::find($id);		
		$data['cabang'] = CabangModel::All();		
		$data['kendaraan_id'] = $manifestData->kendaraan_id;
		$cek = KaryawanCakupanModel::where("karyawan_id",$manifestData->karyawan_id)->get();        
		$where = "";$data_camat = "";$datas="";
		if(!empty($cek)){
			foreach($cek AS $rt){                
				if($data_camat!='') $data_camat.=",";
				$data_camat .= $rt->kecamatan_id;

				$kecamatan = cariDaerah_helper($rt->kecamatan_id,'kec');                                                                            
				if($datas!='') $datas.=", ";
				$datas .= $kecamatan;
			}          
			$where = "pengiriman.id_kelurahan_penerima IN ($data_camat)";
		}
		$data['cakupan'] = $datas;
		$data['port_tujuan'] = $manifestData->port_tujuan;						
		$data['karyawan_id'] = $manifestData->karyawan_id;
		$data['tgl_manifest'] = $manifestData->tgl_manifest;
		$data['keterangan'] = $manifestData->keterangan;				
		$data['kendaraan'] = KendaraanDanPlatModel::where("status",1)->get();       				
		$data['karyawan'] = KaryawanModel::where("status",1)->get();        		
		$data['manifestDetail'] = PaketModel::join("paket_detail","paket.code","=","paket_detail.code")
				->join("pengiriman","pengiriman.id","=","paket_detail.pengiriman_id")
				->leftJoin('jenis_tarif', 'jenis_tarif.id', '=', 'pengiriman.jenis_tarif_id')
				->leftJoin("kendaraan","paket.kendaraan_id","=","kendaraan.id")
				->leftJoin("karyawan","paket.karyawan_id","=","karyawan.id")				
				->where("paket.id",$id)
				->get(["paket_detail.penerima","paket_detail.pengiriman_id", "paket_detail.diterima_at","paket_detail.ditunda_at",'paket_detail.status AS sta','paket_detail.id AS ids','jenis_tarif.name AS jenis_tarif',"karyawan.name as karyawan","kendaraan.name AS kendaraan","paket.*","paket.code AS manifest_code","pengiriman.code AS kode_tr","pengiriman.*"]);		
		return view($this->folder.'/penyerahan',$data)->with('manifest', $manifestData);
	}

	public function updatePenyerahan(Request $request){       
		$user = Auth::user()->getAttributes();		
		$jum         = $request->jum;		
		for ($i=1; $i <= $jum; $i++) {                                                          
			if(isset($_POST["pick_".$i])){
				$paket_detail_id = $_POST["paket_detail_id_".$i];                                                                              
				$data = PaketDetailModel::find($paket_detail_id);								
				$codes = $data->code;
				$ids = $data->pengiriman_id;
				$data->updated_at = $data->pickup_at = $waktu = Carbon::now()->toDateTimeString();
				$data->pickup_by = $user['id'];
				$data->status =  "penyerahan";
				$data->status_utama =  "manifest_lintas_penerimaan";
				$data->save();             											

				$cekManifest = PaketModel::where("code",$codes)->first();				
				$outlet_id = $cekManifest->outlet_id;
				$cekPort = DB::table("outlet")->join("cabang","cabang.id","=","outlet.port_id")
							->where("outlet.id",$outlet_id)->get(['cabang.name AS port']);
				$port = ($cekPort->count()>0)?$cekPort->first()->port:"";												

				simpanStatus($ids,$waktu,"manifest_lintas_penerimaan","Paket telah sampai di Port Tujuan ".$port." pada ".$waktu);
			}      
		}   		

		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('paket.list');                   
	}
	public function simpanDiterima($id,Request $request){        
		
    $data = PaketDetailModel::where("pengiriman_id",$id)->first();    
    $data->penerima = $penerima = $request->penerima;
    $data->diterima_at = $data->updated_at = Carbon::now()->toDateTimeString();
    $data->status_utama = "paket_sampai_dipenerima";

    $objectName = 'gambar';
    $request->validate([$objectName => 'required|mimes:jpeg,jpg,png|max:1000']);    
    if($request->file()) {
      $fileName = time().'_'.$request->$objectName->getClientOriginalName();        
      $upload = $request->$objectName->move(public_path('ima49es/delivery'), $fileName);        
      $data->gambar = $fileName;                
    }

    
    $data->save();

    $data2 = PengirimanModel::find($id);
    $data2->updated_at = $data2->tgl_delivered = $waktu = Carbon::now()->toDateTimeString();
    $data2->status = "paket_sampai_dipenerima";
    $data2->save();
    
		simpanStatus($id,$waktu,"paket_sampai_dipenerima","Paket sudah diterima ".$penerima);

		$manifestId = PaketModel::where("code",$data->code)->first()->id;
    session()->put('msg', setMsg("Successed!","success"));        
    return redirect()->route('paket.penyerahan',$manifestId);
           
  }
  public function simpanTidakDiterima($id,Request $request){        
		
    $data = PaketDetailModel::where("pengiriman_id",$id)->first();
    $data->alasan = $alasan = $request->alasan;
    $data->ditunda_at = $waktu = $data->updated_at = Carbon::now()->toDateTimeString();
    $data->status_utama = "paket_ditunda_penyerahan";
    
    $data->save();
    
		simpanStatus($id,$waktu,"paket_ditunda_penyerahan","Paket belum diterima karena alasan: ".$alasan);

    session()->put('msg', setMsg("Successed!","success"));        
    return redirect()->route('paket.list');
           
  }
	public function barangDiterima($id){
		$data['title']  = $this->title;
		$data['subtitle']  = "Barang Diterima";
		$data['isi']    = $this->set;
		$data['set']    = "barangDiterima";     
		$data['pengiriman_id']    = $id;
		return view($this->folder.'/barangDiterima',$data);
	}

	public function barangTidakDiterima($id){
		$data['title']  = $this->title;
		$data['subtitle']  = "Barang Tidak Diterima";
		$data['isi']    = $this->set;
		$data['set']    = "barangTidakDiterima";     
		$data['pengiriman_id']    = $id;
		return view($this->folder.'/barangTidakDiterima',$data);
	}
}


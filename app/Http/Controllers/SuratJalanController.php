<?php

namespace App\Http\Controllers;

use App\Models\SuratJalanModel;
use App\Models\SuratJalanDetailModel;
use App\Models\KaryawanModel;
use App\Models\PengirimanModel;
use App\Models\ManifestModel;
use App\Models\KaryawanCakupanModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SuratJalanController extends Controller
{
	var $set    = "suratJalan";
	var $title      = "SuratJalan";
	var $subtitle   = "SuratJalan Baru";
	var $folder = "transaksi/suratJalan";

	public function index(){
		$suratJalanData = SuratJalanModel::leftJoin("surat_jalan_detail AS sjd","sjd.code","=","surat_jalan.code")
					->join("pengiriman","sjd.pengiriman_id","=","pengiriman.id")
					->join("manifest_detail AS md","sjd.pengiriman_id","=","md.pengiriman_id")					
					->join("karyawan","surat_jalan.karyawan_id","=","karyawan.id")
					->groupBy("surat_jalan.code")
					->get(["surat_jalan.*","karyawan.name AS kurir","md.code AS manifest_code","pengiriman.jumlah_paket","pengiriman.id_kelurahan_penerima","pengiriman.total_tagihan","pengiriman.awb_number","pengiriman.nama_pengirim","pengiriman.nama_penerima"]);
		$data['title']  = $this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "view";
		// dd($user = Auth::user()->getAttributes());                   
		return view($this->folder.'/index',$data)->with('suratJalan', $suratJalanData);
	}
	public function insert(){
		$data['title']  = "Add ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "insert";
		$data['tgl_surat']    = date("Y-m-d");
		$data['karyawan'] = KaryawanModel::where("status",1)->get();        
		$data['karyawan_id'] = "";
		$data['cakupan'] = "";
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
	public function generate(Request $request){
		$data['title']  = "Generate ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "generate";        
		$data['karyawan'] = KaryawanModel::where("status",1)->get();        
		$data['tgl_surat'] = $request->tgl_surat;
		$data['karyawan_id'] = $request->karyawan_id;
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
			$where = "pengiriman.id_kelurahan_penerima IN ($data_camat)";
		}
		$data['cakupan'] = $datas;
		$data['manifest'] = ManifestModel::leftJoin('kendaraan', 'kendaraan.id', '=', 'manifest.kendaraan_id')
					->leftJoin('karyawan', 'karyawan.id', '=', 'manifest.karyawan_id')                                        
					->leftJoin("manifest_detail","manifest.code","=","manifest_detail.code")
					->leftJoin("pengiriman","manifest_detail.pengiriman_id","=","pengiriman.id")                
					->where("manifest.status","warehouse")
					->where("pengiriman.status","diterima_port_tujuan")
					// ->whereRaw($where)
					->get(['kendaraan.name AS kendaraan',"manifest.id AS id_manifest","manifest_detail.id AS id_manifest_detail",'kendaraan.no_plat','karyawan.name AS karyawan', 'manifest.*','pengiriman.*']);        
		return view($this->folder.'/insert',$data);
	}
	public function create(Request $request){       
		$user = Auth::user()->getAttributes();
		$data2 = new SuratJalanModel;		
		$jum   = $request->karyawan_id;
		
		$codes = "SJM".cariKode_helper("surat_jalan");
		$data2->code = $codes;        
		for ($i=1; $i <= $jum; $i++) {                                                          
			if(isset($_POST["pick_".$i])){
				$data = new SuratJalanDetailModel;
				$data->code = $codes;
				$data->pengiriman_id = $ids = $_POST["pengiriman_id_".$i];                                                                              
				$data->dikirim_at = Carbon::now()->toDateTimeString();                
				$data->save();              

				$data3 = PengirimanModel::find($ids);
				$data3->status =  "diantar_ke_lokasi";
				$data3->tgl_pengantaran =  Carbon::now()->toDateString();
				$data3->save();

			}      
		}   
		

		$data2->tgl_surat = Carbon::now()->toDateString();
		$data2->total_paket = $jum;        
		$data2->karyawan_id = $request->karyawan_id;                        
		$data2->keterangan = $request->keterangan;              
		$data2->status = "baru";
		$data2->save();

		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('suratJalan.index');                   
	}
	
	public function detail(){
		$data['title']  = $this->title;
		$data['subtitle']  = "Detail";
		$data['isi']    = $this->set;
		$data['set']    = "detail";     
		return view($this->folder.'/detail',$data);
	}

}


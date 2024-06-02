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
		$data['isi']    = $this->set;
		$data['set']    = "insert"; 
		$data['periode'] = PeriodeModel::where("status",1)->get();		
		return view($this->folder.'/insert',$data);
	}
	public function addDetail($id){       
		$data['title']  = "Tambah Detail ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "detail";
		$paketData = PaketModel::find($id);		
		$data['periode'] = PeriodeModel::where("status",1)->get();		
		return view($this->folder.'/insert',$data)->with('paket', $paketData);
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
		$data2->deskripsi = $request->deskripsi;        		
		$data2->status =  (!is_null($request->status))?$request->status:0;               
		$data2->save();

		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('paket.index');                   
	}
	public function saveDetail($id, Request $request){       		
		$data2 = new PaketDetailModel;					
		$data2->paket_id = $id;
		$data2->produk_id = $request->produk_id;        
		$data2->jumlah = $request->jumlah;        		
		$data2->created_at = Carbon::now()->toDateString();
		$data2->save();

		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('paket.addDetail',$id);                   
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
		$data2->deskripsi = $request->deskripsi;        		      		
		$data2->status =  (!is_null($request->status))?$request->status:0;               
		$data2->save();

		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('paket.index');                   
	}


	public function edit($id){
		$data['title']  = "Edit ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "edit";
		$paketData = PaketModel::find($id);		
		$data['periode'] = PeriodeModel::where("status",1)->get();		
		return view($this->folder.'/insert',$data)->with('paket', $paketData);
	}
	public function deleteDetail($id,$ids){
		$data = PaketDetailModel::find($id);		
		$data->delete();
		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('paket.addDetail',$ids);
	}		
}


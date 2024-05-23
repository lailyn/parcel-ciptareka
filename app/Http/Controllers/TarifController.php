<?php

namespace App\Http\Controllers;

use App\Models\TarifModel;
use App\Models\TarifDetailModel;
use App\Models\JenisTarifModel;
use App\Models\KelurahanModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TarifController extends Controller
{
	var $set    = "tarif";
	var $title      = "Tarif";
	var $subtitle   = "Master Tarif";
	var $folder = "master/tarif";

	public function index(){
		$tarifData = TarifModel::join('tarif_detail', 'tarif_detail.tarif_id', '=', 'tarif.id')                                       
					->join("jenis_tarif","jenis_tarif.id","=","tarif_detail.jenis_tarif_id")
					->where("tarif_detail.status",1)
					->get(['jenis_tarif.name AS jenis_tarif','tarif_detail.*','jenis_tarif.id AS ids','jenis_tarif.status AS sta', 'tarif.*']);
		$data['title']  = $this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "view";
		// dd($user = Auth::user()->getAttributes());                   
		return view($this->folder.'/index',$data)->with('tarif', $tarifData);
	}
	public function history(){
		$tarifData = TarifModel::join('tarif_detail', 'tarif_detail.tarif_id', '=', 'tarif.id')                                       
					->join("jenis_tarif","jenis_tarif.id","=","tarif_detail.jenis_tarif_id")
					->where("tarif_detail.status",0)
					->get(['jenis_tarif.name AS jenis_tarif','tarif_detail.*', 'tarif.*']);
		$data['title']  = $this->title;
		$data['subtitle']  = "Riwayat";
		$data['isi']    = $this->set;
		$data['set']    = "view";		
		return view($this->folder.'/history',$data)->with('tarif', $tarifData);
	}
	public function insert(){
		$data['title']  = "Add ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "insert";
		$data['jenisTarif'] = JenisTarifModel::where("status",1)->get();
		$data['tujuan'] = $data['asal'] = KelurahanModel::join('location_subdistrict', 'location_subdistrict.id_subdistrict', '=', 'location_village.subdistrict_id')
					->join('location_cities', 'location_cities.id_cities', '=', 'location_subdistrict.id_cities')
					->join('location_states', 'location_states.id_states', '=', 'location_cities.id_states')                   
					->get(['location_states.states', 'location_cities.cities','location_subdistrict.subdistrict','location_village.*']);
		return view($this->folder.'/insert',$data);
	}
	public function create(Request $request){        
		$data = new TarifModel;
		$data->asal = $request->asal;        
		$data->tujuan = $request->tujuan;                        
		$data->status =  1;
		$data->cod = 1;
		$data->created_at = Carbon::now()->toDateTimeString();
		$data->save();
		$id_baru = $data->id;

		$jenisTarif = JenisTarifModel::where("status",1)->get();		
		$jum = $request->jum;		
		for ($i=1; $i <= $jum; $i++) {             
			$cost = $_POST["cost_".$i];
			if($cost>0){		
				$data2 = new TarifDetailModel;
				$data2->tarif_id = $id_baru;    
				$data2->cost = ubahRupiah($cost);    
				$data2->jenis_tarif_id = $_POST["jenis_tarif_id_".$i];				
				$data2->durasi = $_POST["durasi_".$i];				
				$data2->tgl_mulai = $_POST["tgl_mulai_".$i];				
				$data2->jam_mulai = $_POST["jam_mulai_".$i];				
				$data2->tgl_akhir = $_POST["tgl_akhir_".$i];				
				$data2->jam_akhir = $_POST["jam_akhir_".$i];				
				$data2->status =  1;
				$data2->save();        
			}
		}        
		               
		session()->put('msg', setMsg("Successed!","success"));        

		return redirect()->route('tarif.index');
	}
	public function delete($id){
		$data = TarifDetailModel::find($id);
		$data->delete();
		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('tarif.index');
	}
	public function nonaktif($id){
		$data = TarifDetailModel::find($id);
		$data->status = 0;
		$data->save();
		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('tarif.index');
	}
	public function edit($id){
		$data['title']  = "Edit ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "edit";
		$tarifData = TarifModel::find($id);
		$data['jenisTarif'] = JenisTarifModel::where("status",1)->get();
		$data['tujuan'] = $data['asal'] = KelurahanModel::join('location_subdistrict', 'location_subdistrict.id_subdistrict', '=', 'location_village.subdistrict_id')
					->join('location_cities', 'location_cities.id_cities', '=', 'location_subdistrict.id_cities')
					->join('location_states', 'location_states.id_states', '=', 'location_cities.id_states')                   
					->get(['location_states.states', 'location_cities.cities','location_subdistrict.subdistrict','location_village.*']);
		return view($this->folder.'/edit',$data)->with('tarif', $tarifData);
	}
	public function update($id, Request $request){
		$data = TarifModel::find($id);
		$data->name = "";        
		$data->jenis_tarif_id = $request->jenis_tarif_id;                
		$data->cost = ubahRupiah($request->cost);        		
		$data->durasi = $request->durasi;        
		$data->tgl_mulai = $request->tgl_mulai;                        
		$data->tgl_akhir = $request->tgl_akhir;                        
		$data->status =  (!is_null($request->status))?$request->status:0;           
		$data->updated_at = Carbon::now()->toDateTimeString();
		$data->save();    
		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('tarif.index');
	}

}


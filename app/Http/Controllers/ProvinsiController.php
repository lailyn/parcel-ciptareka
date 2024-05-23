<?php

namespace App\Http\Controllers;

use App\Models\ProvinsiModel;
use App\Models\KabupatenModel;
use App\Models\KecamatanModel;
use App\Models\KelurahanModel;
use App\Models\NegaraModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProvinsiController extends Controller
{
	var $set        = "provinsi";
	var $title      = "Provinsi";
	var $subtitle   = "Master Wilayah";
	var $folder     = "master/provinsi";

	public function index(){
		$provinsiData = ProvinsiModel::join('location_countries', 'location_states.id_country', '=', 'location_countries.id')                   
					->get(['location_countries.name AS country', 'location_states.*']);
		$data['title']  = $this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "view";
		// dd($user = Auth::user()->getAttributes());                   
		return view($this->folder.'/index',$data)->with('provinsi', $provinsiData);
	}
	public function insert(){
		$data['title']  = "Add ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "insert";
		$data['negara'] = NegaraModel::All();
		return view($this->folder.'/insert',$data);
	}
	public function create(Request $request){
		$data = new ProvinsiModel;        
		$data->id_country = $request->id_country;     
		$data->states = $request->states;             
		$data->id_states = $request->id_states;             		
		$cek = ProvinsiModel::find($request->id_states);        
		if($cek) session()->put('msg', setMsg("Duplicate ID!","danger"));                        
		$data->save();
		session()->put('msg', setMsg("Successed!","success"));                
		return redirect()->route('provinsi.index');
	}
	public function delete($id){
		$data = ProvinsiModel::find($id);
		$data->delete();
		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('provinsi.index');
	}
	public function edit($id){
		$data['title']  = "Edit ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "edit";
		$provinsiData = ProvinsiModel::find($id);
		$data['negara'] = NegaraModel::All();
		return view($this->folder.'/insert',$data)->with('provinsi', $provinsiData);
	}
	public function update($id, Request $request){
		$data = ProvinsiModel::find($id);
		$data->id_states = $request->id_states;     
		$data->id_country = $request->id_country;     
		$data->states = $request->states;               
		if($id!=$request->id_states){
			$cek = ProvinsiModel::find($request->id_states);
			if($cek->count()>0){
				session()->put('msg', setMsg("Duplicate ID!","danger"));
				return redirect()->route('provinsi.edit',$id);
				exit();
			}    
		}
		$data->save();    
		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('provinsi.index');
	}
	public function provinsi(Request $request)
	{
		$id_country	= $request->id_country;		
		$id_states	= $request->id_states;		
		$dt_prov = ProvinsiModel::select("location_states.*")->where("location_states.id_country","=",$id_country)->get();						
		echo "<option value=''>- choose -</option>";
		foreach ($dt_prov as $key => $dt) {						
			if($id_states!='' && $id_states==$dt->id_states) $se = "selected";
      	else $se="";     
			echo "<option $se value='$dt->id_states'>$dt->states</option>";
		}
	}
	public function kab(Request $request)
	{
		$id_states	= $request->id_states;		
		$id_cities	= $request->id_cities;		
		$dt_kab = KabupatenModel::select("location_cities.*")->where("location_cities.id_states","=",$id_states)->get();						
		echo "<option value=''>- choose -</option>";
		foreach ($dt_kab as $key => $dt) {						
			if($id_cities!='' && $id_cities==$dt->id_cities) $se = "selected";
      	else $se="";     
			echo "<option $se value='$dt->id_cities'>$dt->cities</option>";
		}
	}
	public function kec(Request $request)
	{
		$id_cities	= $request->id_cities;		
		$id_subdistrict	= $request->id_subdistrict;		
		$dt_kec = KecamatanModel::select("location_subdistrict.*")->where("location_subdistrict.id_cities","=",$id_cities)->get();						
		echo "<option value=''>- choose -</option>";
		foreach ($dt_kec as $key => $dt) {						
			if($id_subdistrict!='' && $id_subdistrict==$dt->id_subdistrict) $se = "selected";
      	else $se="";     
			echo "<option $se value='$dt->id_subdistrict'>$dt->subdistrict</option>";
		}
	}
	public function kel(Request $request)
	{
		$id_subdistrict	= $request->id_subdistrict;		
		$id_village	= $request->id_village;		
		$dt_kel = KelurahanModel::select("location_village.*")->where("location_village.subdistrict_id","=",$id_subdistrict)->get();						
		echo "<option value=''>- choose -</option>";
		foreach ($dt_kel as $key => $dt) {						
			if($id_village!='' && $id_village==$dt->id_village) $se = "selected";
      	else $se="";     
			echo "<option $se value='$dt->id_village'>$dt->village</option>";
		}
	}

}


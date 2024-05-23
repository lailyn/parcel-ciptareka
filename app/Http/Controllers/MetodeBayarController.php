<?php

namespace App\Http\Controllers;

use App\Models\MetodeBayarModel;
use App\Models\JenisMetodeModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MetodeBayarController extends Controller
{
	var $set    		= "metode_bayar";
	var $title      = "Metode Bayar";
	var $subtitle   = "Master Tarif";
	var $folder 		= "master/metode_bayar";

	public function index(){
        $metode_bayarData = MetodeBayarModel::join('jenis_metode', 'jenis_metode.id', '=', 'metode_bayar.jenis_metode_id')                   
                    ->get(['jenis_metode.name AS jenis_metode', 'metode_bayar.*']);       		
		$data['title']  = $this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "view";
		// dd($user = Auth::user()->getAttributes());                   
		return view($this->folder.'/index',$data)->with('metode_bayar', $metode_bayarData);
	}
	public function insert(){
		$data['title']  = "Add ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "insert";
        $data['jenisMetode'] = JenisMetodeModel::where('status',1)->get();
		return view($this->folder.'/insert',$data);
	}
	public function create(Request $request){
		$data = new MetodeBayarModel;
		$data->slug = $request->slug;        		
        $data->jenis_metode_id = $request->jenis_metode_id;   
		$data->name = $request->name;   
		$data->status =  (!is_null($request->status))?$request->status:0;                           
		$data->created_at = Carbon::now()->toDateTimeString();
		$data->save();

		session()->put('msg', setMsg("Successed!","success"));        

		return redirect()->route('metode_bayar.index');
	}
	public function delete($id){
		$data = MetodeBayarModel::find($id);
		$data->delete();
		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('metode_bayar.index');
	}
	public function edit($id){
		$data['title']  = "Edit ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "edit";
		$metode_bayarData = MetodeBayarModel::find($id);
        $data['jenisMetode'] = JenisMetodeModel::where('status',1)->get();  
		return view($this->folder.'/insert',$data)->with('metode_bayar', $metode_bayarData);
	}
	public function update($id, Request $request){
		$data = MetodeBayarModel::find($id);
		$data->slug = $request->slug;        		
		$data->name = $request->name;      
        $data->jenis_metode_id = $request->jenis_metode_id;     
		$data->status =  (!is_null($request->status))?$request->status:0;                   
		$data->updated_at = Carbon::now()->toDateTimeString();
		$data->save();    
		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('metode_bayar.index');
	}

}

<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LevelController extends Controller
{
	var $set        = "level";
	var $title      = "Level";
	var $subtitle   = "Master Level";
	var $folder     = "master/level";

	public function index(){
		$levelData     = LevelModel::orderBy('name', 'ASC')->get();
		$data['title']  = $this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "view";
		// dd($user = Auth::user()->getAttributes());                   
		return view($this->folder.'/index',$data)->with('level', $levelData);
	}
	public function insert(){
		$data['title']  = "Add ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "insert";
		return view($this->folder.'/insert',$data);
	}
	public function create(Request $request){
		$data = new LevelModel;
		$data->persen_parcel = $request->persen_parcel;        
		$data->persen_thr = $request->persen_thr;        
		$data->name = $request->name;        				
		$data->save();

		session()->put('msg', setMsg("Successed!","success"));        

		return redirect()->route('level.index');
	}
	public function delete($id){
		$data = LevelModel::find($id);
		$data->delete();
		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('level.index');
	}
	public function edit($id){
		$data['title']  = "Edit ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "edit";
		$levelData = LevelModel::find($id);
		return view($this->folder.'/insert',$data)->with('level', $levelData);
	}
	public function update($id, Request $request){
		$data = LevelModel::find($id);
		$data->persen_parcel = $request->persen_parcel;        
		$data->persen_thr = $request->persen_thr;        
		$data->name = $request->name;        		
		$data->save();    
		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('level.index');
	}

}

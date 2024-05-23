<?php

namespace App\Http\Controllers;

use App\Models\JenisMetodeModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class JenisMetodeController extends Controller
{
    var $set        = "jenis_metode";
    var $title      = "Jenis Metode";
    var $subtitle   = "Master Metode";
    var $folder     = "master/jenis_metode";

    public function index(){
        $jenis_metodeData     = JenisMetodeModel::orderBy('name', 'ASC')->get();
        $data['title']  = $this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "view";
        // dd($user = Auth::user()->getAttributes());                   
        return view($this->folder.'/index',$data)->with('jenis_metode', $jenis_metodeData);
    }
    public function insert(){
        $data['title']  = "Add ".$this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "insert";
        return view($this->folder.'/insert',$data);
    }
    public function create(Request $request){
        $data = new JenisMetodeModel;
        $data->name = $request->name;        
        $data->status =  (!is_null($request->status))?$request->status:0;           
        $data->created_at = Carbon::now()->toDateTimeString();
        $data->save();

        session()->put('msg', setMsg("Successed!","success"));        

        return redirect()->route('jenis_metode.index');
    }
    public function delete($id){
        $data = JenisMetodeModel::find($id);
        $data->delete();
        session()->put('msg', setMsg("Successed!","success"));        
        return redirect()->route('jenis_metode.index');
    }
    public function edit($id){
        $data['title']  = "Edit ".$this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "edit";
        $jenis_metodeData = JenisMetodeModel::find($id);
        return view($this->folder.'/insert',$data)->with('jenis_metode', $jenis_metodeData);
    }
    public function update($id, Request $request){
        $data = JenisMetodeModel::find($id);
        $data->name = $request->name;        
        $data->status =  (!is_null($request->status))?$request->status:0;           
        $data->updated_at = Carbon::now()->toDateTimeString();
        $data->save();    
        session()->put('msg', setMsg("Successed!","success"));        
        return redirect()->route('jenis_metode.index');
    }
}

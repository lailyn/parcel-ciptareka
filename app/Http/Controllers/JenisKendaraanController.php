<?php

namespace App\Http\Controllers;

use App\Models\JenisKendaraanModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class JenisKendaraanController extends Controller
{
    var $set        = "jenis_kendaraan";
    var $title      = "Jenis Kendaraan";
    var $subtitle   = "Master Kendaraan";
    var $folder     = "master/jenis_kendaraan";

    public function index(){
        $jenis_kendaraanData     = JenisKendaraanModel::orderBy('name', 'ASC')->get();
        $data['title']  = $this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "view";
        // dd($user = Auth::user()->getAttributes());                   
        return view($this->folder.'/index',$data)->with('jenis_kendaraan', $jenis_kendaraanData);
    }
    public function insert(){
        $data['title']  = "Add ".$this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "insert";
        return view($this->folder.'/insert',$data);
    }
    public function create(Request $request){
        $data = new JenisKendaraanModel;
        $data->name = $request->name;        
        $data->status =  (!is_null($request->status))?$request->status:0;           
        $data->created_at = Carbon::now()->toDateTimeString();
        $data->save();

        session()->put('msg', setMsg("Successed!","success"));        

        return redirect()->route('jenis_kendaraan.index');
    }
    public function delete($id){
        $data = JenisKendaraanModel::find($id);
        $data->delete();
        session()->put('msg', setMsg("Successed!","success"));        
        return redirect()->route('jenis_kendaraan.index');
    }
    public function edit($id){
        $data['title']  = "Edit ".$this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "edit";
        $jenis_kendaraanData = JenisKendaraanModel::find($id);
        return view($this->folder.'/insert',$data)->with('jenis_kendaraan', $jenis_kendaraanData);
    }
    public function update($id, Request $request){
        $data = JenisKendaraanModel::find($id);
        $data->name = $request->name;        
        $data->status =  (!is_null($request->status))?$request->status:0;                
        $data->updated_at = Carbon::now()->toDateTimeString();
        $data->save();    
        session()->put('msg', setMsg("Successed!","success"));        
        return redirect()->route('jenis_kendaraan.index');
    }

}

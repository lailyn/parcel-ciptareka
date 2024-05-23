<?php

namespace App\Http\Controllers;

use App\Models\KendaraanDanPlatModel;
use App\Models\JenisKendaraanModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KendaraanDanPlatController extends Controller
{
    var $set        = "kendaraan_dan_plat";
    var $title      = "Kendaraan dan Plat";
    var $subtitle   = "Master Kendaraan";
    var $folder     = "master/kendaraan_dan_plat";

    public function index(){
        $kendaraan_dan_platData = KendaraanDanPlatModel::join('jenis_kendaraan', 'jenis_kendaraan.id', '=', 'kendaraan.jenis_kendaraan_id')                   
                    ->get(['jenis_kendaraan.name AS jenis_kendaraan', 'kendaraan.*']);               
        $data['title']  = $this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "view";
        // dd($user = Auth::user()->getAttributes());                   
        return view($this->folder.'/index',$data)->with('kendaraan_dan_plat', $kendaraan_dan_platData);
    }
    public function insert(){
        $data['title']  = "Add ".$this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "insert";
        $data['jenisKendaraan'] = JenisKendaraanModel::where('status',1)->get();        
        return view($this->folder.'/insert',$data);
    }
    public function create(Request $request){
        $data = new KendaraanDanPlatModel;
        $data->name = $request->name;        
        $data->jenis_kendaraan_id = $request->jenis_kendaraan_id;        
        $data->deskripsi = $request->deskripsi;        
        $data->no_plat = no_plat($request->no_plat);        
        $data->status =  (!is_null($request->status))?$request->status:0;               
        $data->created_at = Carbon::now()->toDateTimeString();
        $data->save();

        session()->put('msg', setMsg("Successed!","success"));        

        return redirect()->route('kendaraan_dan_plat.index');
    }
    public function delete($id){
        $data = KendaraanDanPlatModel::find($id);
        $data->delete();
        session()->put('msg', setMsg("Successed!","success"));        
        return redirect()->route('kendaraan_dan_plat.index');
    }
    public function edit($id){
        $data['title']  = "Edit ".$this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "edit";
        $kendaraan_dan_platData = KendaraanDanPlatModel::find($id);
        $data['jenisKendaraan'] = JenisKendaraanModel::where('status',1)->get();        
        return view($this->folder.'/insert',$data)->with('kendaraan_dan_plat', $kendaraan_dan_platData);
    }
    public function update($id, Request $request){
        $data = KendaraanDanPlatModel::find($id);
        $data->name = $request->name;        
        $data->jenis_kendaraan_id = $request->jenis_kendaraan_id;        
        $data->deskripsi = $request->deskripsi;        
        $data->no_plat = no_plat($request->no_plat);        
        $data->status =  (!is_null($request->status))?$request->status:0;           
        $data->updated_at = Carbon::now()->toDateTimeString();
        $data->save();    
        session()->put('msg', setMsg("Successed!","success"));        
        return redirect()->route('kendaraan_dan_plat.index');
    }

}

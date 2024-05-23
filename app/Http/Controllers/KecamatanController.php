<?php

namespace App\Http\Controllers;

use App\Models\KabupatenModel;
use App\Models\KecamatanModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KecamatanController extends Controller
{
    var $set        = "kecamatan";
    var $title      = "Kecamatan";
    var $subtitle   = "Master Wilayah";
    var $folder     = "master/kecamatan";

    public function index(){        
        $kecamatanData = KecamatanModel::join('location_cities', 'location_cities.id_cities', '=', 'location_subdistrict.id_cities')                   
                    ->get(['location_cities.cities', 'location_subdistrict.*']);
        $data['title']  = $this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "view";
        // dd($user = Auth::user()->getAttributes());                   
        return view($this->folder.'/index',$data)->with('kecamatan', $kecamatanData);
    }
    public function insert(){
        $data['title']  = "Add ".$this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "insert";
        $data['cities'] = KabupatenModel::All();        
        return view($this->folder.'/insert',$data);
    }
    public function create(Request $request){
        $data = new KecamatanModel;
        $data->id_subdistrict = $request->id_subdistrict;     
        $data->id_cities = $request->id_cities;     
        $data->subdistrict = $request->subdistrict;               
        $cek = KecamatanModel::find($request->id_subdistrict);        
        if($cek){ 
            session()->put('msg', setMsg("Duplicate ID!","danger"));                
            return redirect()->route('kecamatan.index');
        }

        
        $data->save();
        session()->put('msg', setMsg("Successed!","success"));               
        return redirect()->route('kecamatan.index');
    }
    public function delete($id){
        $data = KecamatanModel::find($id);
        $data->delete();
        session()->put('msg', setMsg("Successed!","success"));        
        return redirect()->route('kecamatan.index');
    }
    public function edit($id){
        $data['title']  = "Edit ".$this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "edit";
        $kecamatanData = KecamatanModel::find($id);
        $data['cities'] = KabupatenModel::All();
        return view($this->folder.'/insert',$data)->with('kecamatan', $kecamatanData);
    }
    public function update($id, Request $request){
        $data = KecamatanModel::find($id);
        $data->id_subdistrict = $request->id_subdistrict;     
        $data->id_cities = $request->id_cities;     
        $data->subdistrict = $request->subdistrict;               
        if($id!=$request->id_subdistrict){
            $cek = KecamatanModel::find($request->id_subdistrict);                    
            if($cek) session()->put('msg', setMsg("Duplicate ID!","danger"));                                            
            return redirect()->route('kecamatan.index');
        }                
        $data->save();    
        session()->put('msg', setMsg("Successed!","success"));        
        return redirect()->route('kecamatan.index');
    }
}

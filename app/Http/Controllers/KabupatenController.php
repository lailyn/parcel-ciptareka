<?php

namespace App\Http\Controllers;

use App\Models\KabupatenModel;
use App\Models\ProvinsiModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KabupatenController extends Controller
{
    var $set        = "kabupaten";
    var $title      = "Kab/Kota";
    var $subtitle   = "Master Wilayah";
    var $folder     = "master/kabupaten";

    public function index(){        
        $kabupatenData = KabupatenModel::join('location_states', 'location_states.id_states', '=', 'location_cities.id_states')                   
                    ->get(['location_states.states', 'location_cities.*']);
        $data['title']  = $this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "view";
        // dd($user = Auth::user()->getAttributes());                   
        return view($this->folder.'/index',$data)->with('kabupaten', $kabupatenData);
    }
    public function insert(){
        $data['title']  = "Add ".$this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "insert";
        $data['provinsi'] = ProvinsiModel::All();
        return view($this->folder.'/insert',$data);
    }
    public function create(Request $request){
        $data = new KabupatenModel;
        $data->id_cities = $request->id_cities;     
        $data->id_states = $request->id_states;     
        $data->postal_code = "";                  
        $data->cities = $request->cities;                  
        $cek = KabupatenModel::find($request->id_cities);        
        if($cek){ 
            session()->put('msg', setMsg("Duplicate ID!","danger"));                
            return redirect()->route('kabupaten.index');
        }
        $data->save();
        session()->put('msg', setMsg("Successed!","success"));                            
        return redirect()->route('kabupaten.index');
    }
    public function delete($id){
        $data = KabupatenModel::find($id);
        $data->delete();
        session()->put('msg', setMsg("Successed!","success"));        
        return redirect()->route('kabupaten.index');
    }
    public function edit($id){
        $data['title']  = "Edit ".$this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "edit";
        $kabupatenData = KabupatenModel::find($id);
        $data['provinsi'] = ProvinsiModel::All();
        return view($this->folder.'/insert',$data)->with('kabupaten', $kabupatenData);
    }
    public function update($id, Request $request){
        $data = KabupatenModel::find($id);
        $data->id_cities = $request->id_cities;     
        $data->id_states = $request->id_states;     
        $data->postal_code = "";                  
        $data->cities = $request->cities;                  
        if($id!=$request->id_cities){
            $cek = KabupatenModel::find($request->id_cities);                    
            if($cek) session()->put('msg', setMsg("Duplicate ID!","danger"));                                            
            return redirect()->route('kabupaten.index');
        }
        
        $data->save();
        session()->put('msg', setMsg("Successed!","success"));                    
        return redirect()->route('kabupaten.index');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\JadwalPickupModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class JadwalPickupController extends Controller
{
    var $set        = "jadwal_pickup";
    var $title      = "Jadwal Pickup";
    var $subtitle   = "Master Jadwal Pickup";
    var $folder     = "master/jadwal_pickup";

    public function index(){
        $jadwal_pickupData     = JadwalPickupModel::orderBy('name', 'ASC')->get();
        $data['title']  = $this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "view";
        // dd($user = Auth::user()->getAttributes());                   
        return view($this->folder.'/index',$data)->with('jadwal_pickup', $jadwal_pickupData);
    }
    public function insert(){
        $data['title']  = "Add ".$this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "insert";
        return view($this->folder.'/insert',$data);
    }
    public function create(Request $request){
        $data = new JadwalPickupModel;
        $data->status =  (!is_null($request->status))?$request->status:0;               
        $data->name = $request->name;        
        $data->created_at = Carbon::now()->toDateTimeString();
        $data->save();

        session()->put('msg', setMsg("Successed!","success"));        

        return redirect()->route('jadwal_pickup.index');
    }
    public function delete($id){
        $data = JadwalPickupModel::find($id);
        $data->delete();
        session()->put('msg', setMsg("Successed!","success"));        
        return redirect()->route('jadwal_pickup.index');
    }
    public function edit($id){
        $data['title']  = "Edit ".$this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "edit";
        $jadwal_pickupData = JadwalPickupModel::find($id);
        return view($this->folder.'/insert',$data)->with('jadwal_pickup', $jadwal_pickupData);
    }
    public function update($id, Request $request){
        $data = JadwalPickupModel::find($id);
        $data->status =  (!is_null($request->status))?$request->status:0;           
        $data->name = $request->name;        
        $data->updated_at = Carbon::now()->toDateTimeString();
        $data->save();    
        session()->put('msg', setMsg("Successed!","success"));        
        return redirect()->route('jadwal_pickup.index');
    }

}

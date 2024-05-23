<?php

namespace App\Http\Controllers;

use App\Models\OutletModel;
use App\Models\CabangModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OutletController extends Controller
{
    var $set    = "outlet";
    var $title      = "Outlet";
    var $subtitle   = "Master Outlet";
    var $folder = "master/outlet";

    public function index(){
        $outletData     = OutletModel::leftJoin("cabang","outlet.port_id","=","cabang.id")->orderBy('name', 'ASC')->get(["outlet.*","cabang.name AS cabang"]);
        $data['title']  = $this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "view";
        // dd($user = Auth::user()->getAttributes());                   
        return view($this->folder.'/index',$data)->with('outlet', $outletData);
    }
    public function insert(){
        $data['title']  = "Add ".$this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['cabang'] = CabangModel::All();
        $data['set']    = "insert";
        return view($this->folder.'/insert',$data);
    }
    public function create(Request $request){
        $data = new OutletModel;
        $data->name = $request->name;        
        $data->alamat = $request->alamat;        
        $data->no_hp = $request->no_hp;    
        $data->port_id = $request->port_id;    
        $data->status =  (!is_null($request->status))?$request->status:0;               
        $data->created_at = Carbon::now()->toDateTimeString();
        $data->save();

        session()->put('msg', setMsg("Successed!","success"));        

        return redirect()->route('outlet.index');
    }
    public function delete($id){
        $data = OutletModel::find($id);
        $data->delete();
        session()->put('msg', setMsg("Successed!","success"));        
        return redirect()->route('outlet.index');
    }
    public function edit($id){
        $data['title']  = "Edit ".$this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['cabang'] = CabangModel::All();
        $data['set']    = "edit";
        $outletData = OutletModel::find($id);
        return view($this->folder.'/insert',$data)->with('outlet', $outletData);
    }
    public function update($id, Request $request){
        $data = OutletModel::find($id);
        $data->name = $request->name;        
        $data->alamat = $request->alamat;        
        $data->no_hp = $request->no_hp;    
        $data->port_id = $request->port_id; 
        $data->status =  (!is_null($request->status))?$request->status:0;              
        $data->updated_at = Carbon::now()->toDateTimeString();
        $data->save();    
        session()->put('msg', setMsg("Successed!","success"));        
        return redirect()->route('outlet.index');
    }

}

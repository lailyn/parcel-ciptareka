<?php

namespace App\Http\Controllers;

use App\Models\SatuanPaketModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SatuanPaketController extends Controller
{
    var $set    = "satuan_paket";
    var $title      = "Satuan Paket";
    var $subtitle   = "Master Satuan";
    var $folder = "master/satuan_paket";

    public function index(){
        $satuan_paketData     = SatuanPaketModel::orderBy('name', 'ASC')->get();
        $data['title']  = $this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "view";
        // dd($user = Auth::user()->getAttributes());                   
        return view($this->folder.'/index',$data)->with('satuan_paket', $satuan_paketData);
    }
    public function insert(){
        $data['title']  = "Add ".$this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "insert";
        return view($this->folder.'/insert',$data);
    }
    public function create(Request $request){
        $data = new SatuanPaketModel;  
        $data->name = $request->name;        
        $data->status =  (!is_null($request->status))?$request->status:0;           
        $data->created_at = Carbon::now()->toDateTimeString();
        $data->save();

        session()->put('msg', setMsg("Successed!","success"));        

        return redirect()->route('satuan_paket.index');
    }
    public function delete($id){
        $data = SatuanPaketModel::find($id);
        $data->delete();
        session()->put('msg', setMsg("Successed!","success"));        
        return redirect()->route('satuan_paket.index');
    }
    public function edit($id){
        $data['title']  = "Edit ".$this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "edit";
        $satuan_paketData = SatuanPaketModel::find($id);
        return view($this->folder.'/insert',$data)->with('satuan_paket', $satuan_paketData);
    }
    public function update($id, Request $request){
        $data = SatuanPaketModel::find($id);  
        $data->name = $request->name;        
        $data->status =  (!is_null($request->status))?$request->status:0;           
        $data->updated_at = Carbon::now()->toDateTimeString();
        $data->save();    
        session()->put('msg', setMsg("Successed!","success"));        
        return redirect()->route('satuan_paket.index');
    }

}
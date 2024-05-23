<?php

namespace App\Http\Controllers;

use App\Models\JenisBarangModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class JenisBarangController extends Controller
{
    var $set        = "jenis_barang";
    var $title      = "Jenis Barang";
    var $subtitle   = "Master Barang";
    var $folder     = "master/jenis_barang";

    public function index(){
        $jenis_barangData     = JenisBarangModel::orderBy('name', 'ASC')->get();
        $data['title']  = $this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "view";
        // dd($user = Auth::user()->getAttributes());                   
        return view($this->folder.'/index',$data)->with('jenis_barang', $jenis_barangData);
    }
    public function insert(){
        $data['title']  = "Add ".$this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "insert";
        return view($this->folder.'/insert',$data);
    }
    public function create(Request $request){
        $data = new JenisBarangModel;
        $data->name = $request->name;        
        $data->status =  (!is_null($request->status))?$request->status:0;           
        $data->created_at = Carbon::now()->toDateTimeString();
        $data->save();

        session()->put('msg', setMsg("Successed!","success"));        

        return redirect()->route('jenis_barang.index');
    }
    public function delete($id){
        $data = JenisBarangModel::find($id);
        $data->delete();
        session()->put('msg', setMsg("Successed!","success"));        
        return redirect()->route('jenis_barang.index');
    }
    public function edit($id){
        $data['title']  = "Edit ".$this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "edit";
        $jenis_barangData = JenisBarangModel::find($id);
        return view($this->folder.'/insert',$data)->with('jenis_barang', $jenis_barangData);
    }
    public function update($id, Request $request){
        $data = JenisBarangModel::find($id);
        $data->name = $request->name;        
        $data->status =  (!is_null($request->status))?$request->status:0;           
        $data->updated_at = Carbon::now()->toDateTimeString();
        $data->save();    
        session()->put('msg', setMsg("Successed!","success"));        
        return redirect()->route('jenis_barang.index');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\JabatanKaryawanModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class JabatanKaryawanController extends Controller
{
    var $set        = "jabatan_karyawan";
    var $title      = "Jabatan Karyawan";
    var $subtitle   = "Master Karyawan";
    var $folder     = "master/jabatan_karyawan";

    public function index(){
        $jabatan_karyawanData     = JabatanKaryawanModel::orderBy('name', 'ASC')->get();
        $data['title']  = $this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "view";
        // dd($user = Auth::user()->getAttributes());                   
        return view($this->folder.'/index',$data)->with('jabatan_karyawan', $jabatan_karyawanData);
    }
    public function insert(){
        $data['title']  = "Add ".$this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "insert";
        return view($this->folder.'/insert',$data);
    }
    public function create(Request $request){
        $data = new JabatanKaryawanModel;
        $data->name = $request->name;        
        $data->status =  (!is_null($request->status))?$request->status:0;           
        $data->created_at = Carbon::now()->toDateTimeString();
        $data->save();

        session()->put('msg', setMsg("Successed!","success"));        

        return redirect()->route('jabatan_karyawan.index');
    }
    public function delete($id){
        $data = JabatanKaryawanModel::find($id);
        $data->delete();
        session()->put('msg', setMsg("Successed!","success"));        
        return redirect()->route('jabatan_karyawan.index');
    }
    public function edit($id){
        $data['title']  = "Edit ".$this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "edit";
        $jabatan_karyawanData = JabatanKaryawanModel::find($id);
        return view($this->folder.'/insert',$data)->with('jabatan_karyawan', $jabatan_karyawanData);
    }
    public function update($id, Request $request){
        $data = JabatanKaryawanModel::find($id);
        $data->name = $request->name;        
        $data->status =  (!is_null($request->status))?$request->status:0;           
        $data->updated_at = Carbon::now()->toDateTimeString();
        $data->save();    
        session()->put('msg', setMsg("Successed!","success"));        
        return redirect()->route('jabatan_karyawan.index');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\JenisTarifModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class JenisTarifController extends Controller
{
    var $set        = "jenis_tarif";
    var $title      = "Jenis Tarif";
    var $subtitle   = "Master Tarif";
    var $folder     = "master/jenis_tarif";

    public function index(){
        $jenis_tarifData     = JenisTarifModel::orderBy('name', 'ASC')->get();
        $data['title']  = $this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "view";
        // dd($user = Auth::user()->getAttributes());                   
        return view($this->folder.'/index',$data)->with('jenis_tarif', $jenis_tarifData);
    }
    public function insert(){
        $data['title']  = "Add ".$this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "insert";
        return view($this->folder.'/insert',$data);
    }
    public function create(Request $request){
        $data = new JenisTarifModel;       
        $data->name = $request->name;        
        $data->deskripsi = $request->deskripsi;        
        $data->status =  (!is_null($request->status))?$request->status:0;           
        $data->created_at = Carbon::now()->toDateTimeString();
        $data->save();

        session()->put('msg', setMsg("Successed!","success"));        

        return redirect()->route('jenis_tarif.index');
    }
    public function delete($id){
        $data = JenisTarifModel::find($id);
        $data->delete();
        session()->put('msg', setMsg("Successed!","success"));        
        return redirect()->route('jenis_tarif.index');
    }
    public function edit($id){
        $data['title']  = "Edit ".$this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "edit";
        $jenis_tarifData = JenisTarifModel::find($id);
        return view($this->folder.'/insert',$data)->with('jenis_tarif', $jenis_tarifData);
    }
    public function update($id, Request $request){
        $data = JenisTarifModel::find($id); 
        $data->name = $request->name;        
        $data->deskripsi = $request->deskripsi;        
        $data->status =  (!is_null($request->status))?$request->status:0;           
        $data->updated_at = Carbon::now()->toDateTimeString();
        $data->save();    
        session()->put('msg', setMsg("Successed!","success"));        
        return redirect()->route('jenis_tarif.index');
    }

}

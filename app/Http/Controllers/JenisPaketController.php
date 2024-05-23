<?php

namespace App\Http\Controllers;

use App\Models\JenisPaketModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class JenisPaketController extends Controller
{
    var $set        = "jenis_paket";
    var $title      = "Jenis Paket";
    var $subtitle   = "Master Paket";
    var $folder     = "master/jenis_paket";

    public function index(){
        $jenis_paketData     = JenisPaketModel::orderBy('name', 'ASC')->get();
        $data['title']  = $this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "view";
        // dd($user = Auth::user()->getAttributes());                   
        return view($this->folder.'/index',$data)->with('jenis_paket', $jenis_paketData);
    }
    public function insert(){
        $data['title']  = "Add ".$this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "insert";
        return view($this->folder.'/insert',$data);
    }
    public function create(Request $request){
        $data = new JenisPaketModel;       
        $data->name = $request->name;                
        $data->status =  (!is_null($request->status))?$request->status:0;           
        $data->created_at = Carbon::now()->toDateTimeString();
        $data->save();

        session()->put('msg', setMsg("Successed!","success"));        

        return redirect()->route('jenis_paket.index');
    }
    public function delete($id){
        $data = JenisPaketModel::find($id);
        $data->delete();
        session()->put('msg', setMsg("Successed!","success"));        
        return redirect()->route('jenis_paket.index');
    }
    public function edit($id){
        $data['title']  = "Edit ".$this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "edit";
        $jenis_paketData = JenisPaketModel::find($id);
        return view($this->folder.'/insert',$data)->with('jenis_paket', $jenis_paketData);
    }
    public function update($id, Request $request){
        $data = JenisPaketModel::find($id); 
        $data->name = $request->name;                
        $data->status =  (!is_null($request->status))?$request->status:0;           
        $data->updated_at = Carbon::now()->toDateTimeString();
        $data->save();    
        session()->put('msg', setMsg("Successed!","success"));        
        return redirect()->route('jenis_paket.index');
    }

}

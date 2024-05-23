<?php

namespace App\Http\Controllers;

use App\Models\SatuanBeratModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SatuanBeratController extends Controller
{
    var $set        = "satuan_berat";
    var $title      = "Satuan Berat";
    var $subtitle   = "Master Satuan";
    var $folder     = "master/satuan_berat";

    public function index(){
        $satuan_beratData     = SatuanBeratModel::orderBy('name', 'ASC')->get();
        $data['title']  = $this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "view";
        // dd($user = Auth::user()->getAttributes());                   
        return view($this->folder.'/index',$data)->with('satuan_berat', $satuan_beratData);
    }
    public function insert(){
        $data['title']  = "Add ".$this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "insert";
        return view($this->folder.'/insert',$data);
    }
    public function create(Request $request){
        $data = new SatuanBeratModel;       
        $data->name = $request->name;        
        $data->status =  (!is_null($request->status))?$request->status:0;           
        $data->created_at = Carbon::now()->toDateTimeString();
        $data->save();

        session()->put('msg', setMsg("Successed!","success"));        

        return redirect()->route('satuan_berat.index');
    }
    public function delete($id){
        $data = SatuanBeratModel::find($id);
        $data->delete();
        session()->put('msg', setMsg("Successed!","success"));        
        return redirect()->route('satuan_berat.index');
    }
    public function edit($id){
        $data['title']  = "Edit ".$this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "edit";
        $satuan_beratData = SatuanBeratModel::find($id);
        return view($this->folder.'/insert',$data)->with('satuan_berat', $satuan_beratData);
    }
    public function update($id, Request $request){
        $data = SatuanBeratModel::find($id);  
        $data->name = $request->name;        
        $data->status =  (!is_null($request->status))?$request->status:0;           
        $data->updated_at = Carbon::now()->toDateTimeString();
        $data->save();    
        session()->put('msg', setMsg("Successed!","success"));        
        return redirect()->route('satuan_berat.index');
    }

}


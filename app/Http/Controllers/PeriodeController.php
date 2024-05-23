<?php

namespace App\Http\Controllers;

use App\Models\PeriodeModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PeriodeController extends Controller
{
    var $set        = "periode";
    var $title      = "Periode";
    var $subtitle   = "Master Periode";
    var $folder     = "master/periode";

    public function index(){
        $periodeData     = PeriodeModel::orderBy('name', 'ASC')->get();
        $data['title']  = $this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "view";
        // dd($user = Auth::user()->getAttributes());                   
        return view($this->folder.'/index',$data)->with('periode', $periodeData);
    }
    public function insert(){
        $data['title']  = "Add ".$this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "insert";
        return view($this->folder.'/insert',$data);
    }
    public function create(Request $request){
        $data = new PeriodeModel;
        $data->name = $request->name;        
        $data->status =  (!is_null($request->status))?$request->status:0;           
        $data->created_at = Carbon::now()->toDateTimeString();
        $data->save();

        session()->put('msg', setMsg("Successed!","success"));        

        return redirect()->route('periode.index');
    }
    public function delete($id){
        $data = PeriodeModel::find($id);
        $data->delete();
        session()->put('msg', setMsg("Successed!","success"));        
        return redirect()->route('periode.index');
    }
    public function edit($id){
        $data['title']  = "Edit ".$this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "edit";
        $periodeData = PeriodeModel::find($id);
        return view($this->folder.'/insert',$data)->with('periode', $periodeData);
    }
    public function update($id, Request $request){
        $data = PeriodeModel::find($id);
        $data->name = $request->name;        
        $data->status =  (!is_null($request->status))?$request->status:0;           
        $data->updated_at = Carbon::now()->toDateTimeString();
        $data->save();    
        session()->put('msg', setMsg("Successed!","success"));        
        return redirect()->route('periode.index');
    }
}

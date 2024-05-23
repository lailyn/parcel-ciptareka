<?php

namespace App\Http\Controllers;

use App\Models\NegaraModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NegaraController extends Controller
{
    var $set        = "negara";
    var $title      = "Negara";
    var $subtitle   = "Master Wilayah";
    var $folder     = "master/negara";

    public function index(){
        $negaraData     = NegaraModel::orderBy('name', 'ASC')->get();
        $data['title']  = $this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "view";
        // dd($user = Auth::user()->getAttributes());                   
        return view($this->folder.'/index',$data)->with('negara', $negaraData);
    }
    public function insert(){
        $data['title']  = "Add ".$this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "insert";        
        return view($this->folder.'/insert',$data);
    }
    public function create(Request $request){
        $data = new NegaraModel;
        $data->name = $request->name;        
        $data->status = 1;
        $data->save();

        session()->put('msg', setMsg("Successed!","success"));        

        return redirect()->route('negara.index');
    }
    public function delete($id){
        $data = NegaraModel::find($id);
        $data->delete();
        session()->put('msg', setMsg("Successed!","success"));        
        return redirect()->route('negara.index');
    }
    public function edit($id){
        $data['title']  = "Edit ".$this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "edit";
        $negaraData = NegaraModel::find($id);
        return view($this->folder.'/insert',$data)->with('negara', $negaraData);
    }
    public function update($id, Request $request){
        $data = NegaraModel::find($id);
        $data->name = $request->name;        
        $data->status = 1;
        $data->save();    
        session()->put('msg', setMsg("Successed!","success"));        
        return redirect()->route('negara.index');
    }

}

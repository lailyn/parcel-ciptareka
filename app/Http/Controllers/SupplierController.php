<?php

namespace App\Http\Controllers;

use App\Models\SupplierModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SupplierController extends Controller
{
    var $set    = "supplier";
    var $title      = "Supplier";
    var $subtitle   = "Master Supplier";
    var $folder = "master/supplier";

    public function index(){
        $supplierData     = SupplierModel::orderBy('name', 'ASC')->get();
        $data['title']  = $this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "view";
        // dd($user = Auth::user()->getAttributes());                   
        return view($this->folder.'/index',$data)->with('supplier', $supplierData);
    }
    public function insert(){
        $data['title']  = "Add ".$this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "insert";
        return view($this->folder.'/insert',$data);
    }
    public function create(Request $request){
        $data = new SupplierModel;
        $data->name = $request->name;        
        $data->alamat = $request->alamat;        
        $data->no_hp = $request->no_hp;    
        $data->status =  (!is_null($request->status))?$request->status:0;               
        $data->created_at = Carbon::now()->toDateTimeString();
        $data->save();

        session()->put('msg', setMsg("Successed!","success"));        

        return redirect()->route('supplier.index');
    }
    public function delete($id){
        $data = SupplierModel::find($id);
        $data->delete();
        session()->put('msg', setMsg("Successed!","success"));        
        return redirect()->route('supplier.index');
    }
    public function edit($id){
        $data['title']  = "Edit ".$this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "edit";
        $supplierData = SupplierModel::find($id);
        return view($this->folder.'/insert',$data)->with('supplier', $supplierData);
    }
    public function update($id, Request $request){
        $data = SupplierModel::find($id);
        $data->name = $request->name;        
        $data->alamat = $request->alamat;        
        $data->no_hp = $request->no_hp;     
        $data->status =  (!is_null($request->status))?$request->status:0;              
        $data->updated_at = Carbon::now()->toDateTimeString();
        $data->save();    
        session()->put('msg', setMsg("Successed!","success"));        
        return redirect()->route('supplier.index');
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\ProdukModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class ProdukController extends Controller
{
	var $set        = "produk";
	var $title      = "Produk";
	var $subtitle   = "Master Produk";
	var $folder     = "master/produk";

	public function index(){
		$produkData = ProdukModel::All();
		$data['title']  = $this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "view";
		// dd($user = Auth::user()->getAttributes());                   
		return view($this->folder.'/index',$data)->with('produk', $produkData);
	}
	public function insert(){
		$data['title']  = "Add ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "insert";		
		return view($this->folder.'/insert',$data);
	}
	public function create(Request $request){
		$data = new ProdukModel;
		$data->name = $request->name;        
		$data->deskripsi = $request->deskripsi;        
		$data->keterangan = $request->keterangan;        
		$data->harga_harian = $request->harga_harian;        
		$data->tgl_berlaku = $request->tgl_berlaku;        
		$data->tgl_berakhir = $request->tgl_berakhir;        		
		$data->created_at = Carbon::now()->toDateTimeString();        		
    $data->save();        

		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('produk.index');
	}
	public function delete($id){
		$data = ProdukModel::find($id);
		$data->delete();
		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('produk.index');
	}
	public function edit($id){
		$data['title']  = "Edit ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "edit";
		$produkData = ProdukModel::find($id);		
		return view($this->folder.'/insert',$data)->with('produk', $produkData);
	}
	public function update($id, Request $request){
		$data = ProdukModel::find($id);
		$data->name = $request->name;        
		$data->deskripsi = $request->deskripsi;        
		$data->keterangan = $request->keterangan;        
		$data->harga_harian = $request->harga_harian;        
		$data->tgl_berlaku = $request->tgl_berlaku;        
		$data->tgl_berakhir = $request->tgl_berakhir;        		
		$data->updated_at = Carbon::now()->toDateTimeString();
		

		$data->save();    
		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('produk.index');
	}
}

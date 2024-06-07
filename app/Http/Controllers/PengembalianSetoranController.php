<?php

namespace App\Http\Controllers;

use App\Models\PengembalianSetoranModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class PengembalianSetoranController extends Controller
{
	var $set        = "pengembalianSetoran";
	var $title      = "Pengembalian Setoran";
	var $subtitle   = "Pengembalian Setoran";
	var $folder     = "transaksi/pengembalianSetoran";

	public function index(){
		$pengembalianSetoranData = PengembalianSetoranModel::join("member_paket","pengembalianSetoran.member_paket_id","=","member_paket.id")
					->join("member","member_paket.member_id","=","member.id")
					->join("paket","member_paket.paket_id","=","paket.id")
					->orderBy('pengembalianSetoran.id','DESC')
					->get(['pengembalianSetoran.*','member.name AS namaMember','paket.name AS namaPaket']);               
		$data['title']  = $this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "view";		                  
		return view($this->folder.'/index',$data)->with('pengembalianSetoran', $pengembalianSetoranData);
	}	
	public function insert(){
		$data['title']  = "Add ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "insert";		
		$data['memberPaket'] = DB::table("member")->join("member_paket","member.id","=","member_paket.member_id")
						->join("paket","member_paket.paket_id","=","paket.id")->get(["member_paket.id","member.name AS namaMember","paket.name AS namaPaket"]);
		return view($this->folder.'/insert',$data);
	}	
	public function create(Request $request){
		$validator = $request->validate([
		  'member_paket_id' => 'required',		  
		  'nominal' => 'required',		  		  		  
		  'penerima_pengembalian' => 'required',		  
		  'presentase_manajemen' => 'required',	  
		  'presentase_partner' => 'required',		  
		]);
		if ($validator) {
			$data = new PengembalianSetoranModel;
			$data->member_paket_id = $request->member_paket_id;        			
			$data->code = generateRandomString(3).cariKode_helper("pengembalianSetoran");;        			
			$data->tgl_pengembalian = $request->tgl_pengembalian;        			
			$data->nominal = $request->nominal;        
			$data->penerima_pengembalian = $request->penerima_pengembalian;        
			$data->presentase_manajemen = $request->presentase_manajemen;        
			$data->presentase_partner = $request->presentase_partner;        
			$data->keterangan = $request->keterangan;        						
			$data->created_at = Carbon::now()->toDateTimeString();
			
			$data->save();
			session()->put('msg', setMsg("Successed!","success"));        
			return redirect()->route('pengembalianSetoran.index');
		}else{
			session()->put('msg', setMsg($validator->errors(),"danger"));        
			echo "<script>history.go(-1)</script>";
		}        
	}
	public function delete($id){
		$data = PengembalianSetoranModel::find($id);
		$data->delete();
		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('pengembalianSetoran.index');
	}
	public function edit($id){
		$data['title']  = "Edit ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "edit";
		$pengembalianSetoranData = PengembalianSetoranModel::find($id);		
		return view($this->folder.'/insert',$data)->with('pengembalianSetoran', $pengembalianSetoranData);
	}
	public function submit($id){
		$data = PengembalianSetoranModel::find($id);
		$data->submit = 1;
		$data->save();
		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('pengembalianSetoran.index');
	}
	public function update($id, Request $request){
		$validator = $request->validate([
		  'member_paket_id' => 'required',		  
		  'nominal' => 'required',		  		  		  
		  'penerima_pengembalian' => 'required',		  
		  'presentase_manajemen' => 'required',	  
		  'presentase_partner' => 'required',		  
		]);
		if ($validator) {
			$data = PengembalianSetoranModel::find($id);
			$data->member_paket_id = $request->member_paket_id;        						
			$data->tgl_pengembalian = $request->tgl_pengembalian;        			
			$data->nominal = $request->nominal;        
			$data->penerima_pengembalian = $request->penerima_pengembalian;        
			$data->presentase_manajemen = $request->presentase_manajemen;        
			$data->presentase_partner = $request->presentase_partner;        
			$data->keterangan = $request->keterangan;        						
			
			$data->save();
			session()->put('msg', setMsg("Successed!","success"));        
			return redirect()->route('pengembalianSetoran.index');
		}else{
			session()->put('msg', setMsg($validator->errors(),"danger"));        
			echo "<script>history.go(-1)</script>";
		} 		
	}
}

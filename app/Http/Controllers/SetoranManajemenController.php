<?php

namespace App\Http\Controllers;

use App\Models\SetoranManajemenModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class SetoranManajemenController extends Controller
{
	var $set        = "setoranManajemen";
	var $title      = "Setoran Manajemen";
	var $subtitle   = "Setoran Manajemen";
	var $folder     = "transaksi/setoranManajemen";

	public function index(){
		$setoranManajemenData = SetoranManajemenModel::join("member","setoranManajemen.member_id","=","member.id")
					->join("partnership","setoranManajemen.partnership_id","=","partnership.id")
					->orderBy('setoranManajemen.id','DESC')
					->get(['setoranManajemen.*','member.name AS namaMember','partnership.name AS namaPartner']);               
		$data['title']  = $this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "view";		                  
		return view($this->folder.'/index',$data)->with('setoranManajemen', $setoranManajemenData);
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
			$data = new SetoranManajemenModel;
			$data->member_paket_id = $request->member_paket_id;        			
			$data->code = generateRandomString(3).cariKode_helper("setoranManajemen");;        			
			$data->tgl_pengembalian = $request->tgl_pengembalian;        			
			$data->nominal = $request->nominal;        
			$data->penerima_pengembalian = $request->penerima_pengembalian;        
			$data->presentase_manajemen = $request->presentase_manajemen;        
			$data->presentase_partner = $request->presentase_partner;        
			$data->keterangan = $request->keterangan;        						
			$data->created_at = Carbon::now()->toDateTimeString();
			
			$data->save();
			session()->put('msg', setMsg("Successed!","success"));        
			return redirect()->route('setoranManajemen.index');
		}else{
			session()->put('msg', setMsg($validator->errors(),"danger"));        
			echo "<script>history.go(-1)</script>";
		}        
	}
	public function delete($id){
		$data = SetoranManajemenModel::find($id);
		$data->delete();
		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('setoranManajemen.index');
	}
	public function edit($id){
		$data['title']  = "Edit ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "edit";
		$setoranManajemenData = SetoranManajemenModel::find($id);		
		return view($this->folder.'/insert',$data)->with('setoranManajemen', $setoranManajemenData);
	}
	public function submit($id){
		$data = SetoranManajemenModel::find($id);
		$data->submit = 1;
		$data->save();
		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('setoranManajemen.index');
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
			$data = SetoranManajemenModel::find($id);
			$data->member_paket_id = $request->member_paket_id;        						
			$data->tgl_pengembalian = $request->tgl_pengembalian;        			
			$data->nominal = $request->nominal;        
			$data->penerima_pengembalian = $request->penerima_pengembalian;        
			$data->presentase_manajemen = $request->presentase_manajemen;        
			$data->presentase_partner = $request->presentase_partner;        
			$data->keterangan = $request->keterangan;        						
			
			$data->save();
			session()->put('msg', setMsg("Successed!","success"));        
			return redirect()->route('setoranManajemen.index');
		}else{
			session()->put('msg', setMsg($validator->errors(),"danger"));        
			echo "<script>history.go(-1)</script>";
		} 		
	}
}

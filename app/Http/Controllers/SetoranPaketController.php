<?php

namespace App\Http\Controllers;

use App\Models\SetoranPaketModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class SetoranPaketController extends Controller
{
	var $set        = "setoranPaket";
	var $title      = "Setoran Paket";
	var $subtitle   = "Setoran Paket";
	var $folder     = "transaksi/setoranPaket";

	public function index(){
		$setoranPaketData = SetoranPaketModel::join("member_paket","setoranPaket.member_paket_id","=","member_paket.id")
					->join("member","member_paket.member_id","=","member.id")
					->join("paket","member_paket.paket_id","=","paket.id")
					->orderBy('setoranPaket.id','DESC')
					->get(['setoranPaket.*','member.name AS namaMember','paket.name AS namaPaket']);               
		$data['title']  = $this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "view";		                  
		return view($this->folder.'/index',$data)->with('setoranPaket', $setoranPaketData);
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
		  'penerima_setoran' => 'required',		  		  		  
		  'tgl_setor' => 'required'		  
		]);
		if ($validator) {
			$data = new SetoranPaketModel;
			$data->member_paket_id = $request->member_paket_id;        			
			$data->code = generateRandomString(3).cariKode_helper("setoranPaket");;        			
			$data->tgl_setor = $request->tgl_setor;        			
			$data->nominal = $request->nominal;        
			$data->penerima_setoran = $request->penerima_setoran;        
			$data->keterangan = $request->keterangan;        						
			$data->created_at = Carbon::now()->toDateTimeString();
			
			$data->save();
			session()->put('msg', setMsg("Successed!","success"));        
			return redirect()->route('setoranPaket.index');
		}else{
			session()->put('msg', setMsg($validator->errors(),"danger"));        
			echo "<script>history.go(-1)</script>";
		}        
	}
	public function delete($id){
		$data = SetoranPaketModel::find($id);
		$data->delete();
		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('setoranPaket.index');
	}
	public function edit($id){
		$data['title']  = "Edit ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "edit";
		$setoranPaketData = SetoranPaketModel::find($id);		
		return view($this->folder.'/insert',$data)->with('setoranPaket', $setoranPaketData);
	}
	public function submit($id){
		$data = SetoranPaketModel::find($id);
		$data->submit = 1;
		$data->save();
		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('setoranPaket.index');
	}
	public function update($id, Request $request){
		$validator = $request->validate([
		  'member_paket_id' => 'required',		  
		  'penerima_setoran' => 'required',		  		  		  
		  'tgl_setor' => 'required'		  
		]);
		if ($validator) {
			$data = SetoranPaketModel::find($id);
			$data->member_paket_id = $request->member_paket_id;        						
			$data->tgl_setor = $request->tgl_setor;        			
			$data->nominal = $request->nominal;        
			$data->penerima_setoran = $request->penerima_setoran;        
			$data->keterangan = $request->keterangan;        						
			$data->created_at = Carbon::now()->toDateTimeString();
			
			$data->save();
			session()->put('msg', setMsg("Successed!","success"));        
			return redirect()->route('setoranPaket.index');
		}else{
			session()->put('msg', setMsg($validator->errors(),"danger"));        
			echo "<script>history.go(-1)</script>";
		} 		
	}
}

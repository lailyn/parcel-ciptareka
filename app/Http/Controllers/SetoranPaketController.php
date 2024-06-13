<?php

namespace App\Http\Controllers;

use App\Models\SetoranPaketModel;
use App\Models\SetoranPaketTmpModel;
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
		$whereRaw = "1=1";$jenis = session()->get('jenis');
		if($jenis=="partnership"){
			$partnership_id = session()->get('partnership_id');
			$whereRaw = " member.partnership_id = '$partnership_id'";
		}
		$setoranPaketData = SetoranPaketModel::join("member_paket","setoranPaket.member_paket_id","=","member_paket.id")
					->join("member","member_paket.member_id","=","member.id")
					->join("paket","member_paket.paket_id","=","paket.id")
					->orderBy('setoranPaket.id','DESC')
					->whereRaw($whereRaw)
					->get(['setoranPaket.*','member.name AS namaMember','paket.name AS namaPaket']);               
		$data['title']  = $this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "view";		                  
		return view($this->folder.'/index',$data)->with('setoranPaket', $setoranPaketData);
	}	
	public function insert(){
		$whereRaw = "1=1";$jenis = session()->get('jenis');
		if($jenis=="partnership"){
			$partnership_id = session()->get('partnership_id');
			$whereRaw = " member.partnership_id = '$partnership_id'";
		}
		$data['title']  = "Add ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "insert";			
		$data['memberPaket'] = DB::table("member")->join("member_paket","member.id","=","member_paket.member_id")
						->whereRaw($whereRaw)->join("paket","member_paket.paket_id","=","paket.id")
						->get(["member_paket.id","member.name AS namaMember","paket.name AS namaPaket"]);
		return view($this->folder.'/insert',$data);
	}
	public function addDetail(){
		$user_id = session()->get('id');					
		$whereRaw = " setoranPaket_tmp.created_by = '$user_id'";		
		$data['title']  = "Add Detail".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "insert";			
		$data['setoranPaket_tmp'] = DB::table("setoranPaket_tmp")->join("member_paket","setoranPaket_tmp.member_paket_id","=","member_paket.id")
						->join("member","member_paket.member_id","=","member.id")
						->whereRaw($whereRaw)->join("paket","member_paket.paket_id","=","paket.id")
						->get(["member.code","member_paket.id","member.name AS namaMember","paket.name AS namaPaket","paket.iuran","paket.jenis_lama"]);
		return view($this->folder.'/addDetail',$data);
	}	
	public function createAll(Request $request){	
		$user_id = session()->get('id');
		$cekTabel = DB::table("setoranPaket_tmp")->where("created_by",$user_id)->get();		
		foreach ($cekTabel as $key => $value) {			
			$ids = $key+1;
			$member_paket_id = $_POST['member_paket_id_'.$ids];
			$tgl_setor = $_POST['tgl_setor_'.$ids];
			$tgl_bayar = $_POST['tgl_bayar_'.$ids];
			$nominal = $_POST['nominal_'.$ids];
			$lama = $_POST['lama_'.$ids];
			
			$data = new SetoranPaketModel;
			$data->member_paket_id = $member_paket_id;        			
			$data->code = generateRandomString(3).cariKode_helper("setoranPaket");;        			
			$data->tgl_setor = $tgl_setor;        			
			$data->tgl_bayar = $tgl_bayar;        			
			$data->lama = $lama;        
			$data->nominal = $nominal;        
			$data->penerima_setoran = $request->penerima_setoran;        
			$data->keterangan = $request->keterangan;        						
			$data->created_at = Carbon::now()->toDateTimeString();			
			$data->save();			
		} 

		// $user_id = session()->get('id');
		// $cekTabel = DB::table("setoranPaket_tmp")->where("created_by",$user_id)->delete();		

		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('setoranPaket.index');       
	}
	public function create(Request $request){
		$user_id = session()->get('id');
		$cekTabel = DB::table("setoranPaket_tmp")->where("created_by",$user_id)->delete();		

		$jum = $request->jum;
		$dt = 0;		
		for ($i=1; $i <= $jum; $i++) { 
			if(isset($_POST['chk_'.$i])){			
				$member_paket_id = $_POST['chk_'.$i];
				$cekPartner = DB::table("member_paket")->join("member","member_paket.member_id","=","member.id")
					->where("member_paket.id",$member_paket_id)
					->get(['member.partnership_id']);
				$cekPartner->count();
				if($cekPartner->count()>0){
					$dataTmp = new SetoranPaketTmpModel;
					$dataTmp->member_paket_id = $member_paket_id;
					$dataTmp->partnership_id = $cekPartner->first()->partnership_id;
					$dataTmp->created_at = Carbon::now()->toDateTimeString();
					$dataTmp->created_by = session()->get('id');
					$dataTmp->save();
				}
				$dt++;
			}
		}	

		if($dt==0){
			session()->put('msg', setMsg("Harus pilih member paket lebih dulu","danger"));        
			echo "<script>history.go(-1)</script>";
		}else{
			session()->put('msg', setMsg("Successed!","success"));        
			return redirect()->route('setoranPaket.addDetail');
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
		return view($this->folder.'/edit',$data)->with('setoranPaket', $setoranPaketData);
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

<?php

namespace App\Http\Controllers;

use App\Models\RekonsiliasiModel;
use App\Models\RekonsiliasiDetailModel;
use App\Models\PeriodeModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class RekonsiliasiController extends Controller
{
	var $set        = "rekonsiliasi";
	var $title      = "Rekonsiliasi";
	var $subtitle   = "Rekonsiliasi";
	var $folder     = "transaksi/rekonsiliasi";

	public function index(){
		$rekonsiliasiData = RekonsiliasiModel::orderBy('periode_rekonsiliasi.id','DESC')
					->get(['periode_rekonsiliasi.*']);               
		$data['title']  = $this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "view";		                  
		return view($this->folder.'/index',$data)->with('rekonsiliasi', $rekonsiliasiData);
	}	
	public function insert(){
		$data['title']  = "Add ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "insert";
		$data['periode'] = PeriodeModel::where("status",1)->get();						
		return view($this->folder.'/insert',$data);
	}	
	public function tampilData(Request $request){		
	// public function tampilData(){		
		$data['periode'] = $periode = $request->periode;		
		$data['tgl_mulai'] = $tgl_mulai = $request->tgl_mulai;		
		$data['tgl_selesai'] = $tgl_selesai = $request->tgl_selesai;	

		$data['rekonsiliasi'] = DB::table('setoranPaket')
					->join("member_paket","setoranPaket.member_paket_id","=","member_paket.id")
					->join('member', 'member_paket.member_id', '=', 'member.id')
			    ->join('paket', 'member_paket.paket_id', '=', 'paket.id')
			    ->where('paket.periode_id', $periode)			    
			    // ->whereRaw("setoranPaket.tgl_setor BETWEEN $tgl_mulai AND $tgl_selesai")			    
			    ->whereNotNull('setoranPaket.setor_at')
			    ->groupBy('member.id')			    
			    ->get(["member.id AS ids","member.name AS namaMember"]);
		$data['selisih'] = cariSelisih($tgl_mulai,$tgl_selesai);
		return view($this->folder.'/tampilData',$data);
	}
	public function create(Request $request){
		$validator = $request->validate([
		  'partnership_id' => 'required',		  		  
		]);
		if ($validator) {
			$cek=0;
			$codes = generateRandomString(3).cariKode_helper("rekonsiliasi");;        						
			$jum  = $request->jum;			
			for ($i=1; $i <= $jum; $i++) {
				if(isset($_POST["chk_".$i])){
					
					$datas = new RekonsiliasiDetailModel;
					$datas->code = $codes;
					$datas->setoranPaket_id = $_POST["setoranPaket_id_".$i];
					$datas->created_at = Carbon::now()->toDateTimeString();
					$datas->created_by = auth()->id();										
					$datas->save();              								
					$cek++;
				}      
			}		
			
			if($cek==0){
				session()->put('msg', setMsg("Harus pilih setoran dulu","danger"));        
				return redirect()->route('rekonsiliasi.insert');
				exit();
			}
			$data = new RekonsiliasiModel;
			$data->code = $codes;					
			$data->partnership_id = $request->partnership_id;
			$data->tgl_setor = $request->tgl_setor;
			$data->penerima_setoran = $request->penerima_setoran;
			$data->keterangan = $request->keterangan;
			$data->created_at = Carbon::now()->toDateTimeString();
			$data->created_by = auth()->id();					
			$data->save();              								

			session()->put('msg', setMsg("Successed!","success"));        
			return redirect()->route('rekonsiliasi.index');
		}else{
			session()->put('msg', setMsg($validator->errors(),"danger"));        
			echo "<script>history.go(-1)</script>";
		}        
	}
	public function ubahStatus($member_id){
		$cekData = DB::table("setoranPaket")->join("member_paket","setoranPaket.member_paket_id","=","member_paket.id")
							->join("member","member_paket.member_id","=","member.id")
							->whereRaw("setoranPaket.setor_at IS NULL")->where("member.id",$member_id)->get(["setoranPaket.id"]);
		foreach ($cekData as $key => $value) {
			$datas = SetoranPaketModel::find($value->id);
			$datas->submit 		= 1;
			$datas->setor_at 	= $datas->updated_at = Carbon::now()->toDateTimeString();
			$datas->setor_by 	= $datas->updated_by = auth()->id();					
			$datas->save();
		}
	}
	public function delete($id){
		$data = RekonsiliasiModel::find($id);
		$data->delete();
		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('rekonsiliasi.index');
	}
	public function edit($id){
		$data['title']  = "Edit ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "edit";
		$rekonsiliasiData = RekonsiliasiModel::find($id);		
		return view($this->folder.'/insert',$data)->with('rekonsiliasi', $rekonsiliasiData);
	}
	public function detail($id){
		$data['title']  = "Detail ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "detail";
		$data['rekonsiliasi'] = RekonsiliasiModel::join("partnership","rekonsiliasi.partnership_id","=","partnership.id")
			->where("rekonsiliasi.id",$id)->get(["rekonsiliasi.*","partnership.name AS namaPartner"]);
		return view($this->folder.'/detail',$data);
	}
	public function approval($id){
		$data['title']  = "Approval ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "approval";
		$data['rekonsiliasi'] = RekonsiliasiModel::join("partnership","rekonsiliasi.partnership_id","=","partnership.id")
			->where("rekonsiliasi.id",$id)->get(["rekonsiliasi.*","partnership.name AS namaPartner"]);
		return view($this->folder.'/detail',$data);
	}
	public function saveApproval($id){
		$data = RekonsiliasiModel::find($id);
		$data->status = 1;
		$data->approval_status = 1;
		$data->approval_at = Carbon::now()->toDateTimeString();
		$data->updated_at = Carbon::now()->toDateTimeString();
		$data->updated_by = auth()->id();					
		$data->save();

		$dataUlang = DB::table("rekonsiliasi_detail")->where("code",$data->code)->get();
		foreach($dataUlang AS $rt){			
			$ambil = SetoranPaketModel::find($rt->setoranPaket_id);			
			$ambil->submit = 1;
			$ambil->setor_at = Carbon::now()->toDateTimeString();
			$ambil->setor_by = auth()->id();	
			$ambil->updated_at = Carbon::now()->toDateTimeString();
			$ambil->updated_by = auth()->id();			
			$ambil->save();						
		}
		
		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('rekonsiliasi.index');
	}
	public function submit($id){
		$data = RekonsiliasiModel::find($id);
		$data->submit = 1;
		$data->save();
		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('rekonsiliasi.index');
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
			$data = RekonsiliasiModel::find($id);
			$data->member_paket_id = $request->member_paket_id;        						
			$data->tgl_pengembalian = $request->tgl_pengembalian;        			
			$data->nominal = $request->nominal;        
			$data->penerima_pengembalian = $request->penerima_pengembalian;        
			$data->presentase_manajemen = $request->presentase_manajemen;        
			$data->presentase_partner = $request->presentase_partner;        
			$data->keterangan = $request->keterangan;        						
			
			$data->save();
			session()->put('msg', setMsg("Successed!","success"));        
			return redirect()->route('rekonsiliasi.index');
		}else{
			session()->put('msg', setMsg($validator->errors(),"danger"));        
			echo "<script>history.go(-1)</script>";
		} 		
	}
}

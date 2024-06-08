<?php

namespace App\Http\Controllers;

use App\Models\SetoranManajemenModel;
use App\Models\SetoranPaketModel;
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
	public function tampilData(Request $request){		
		$data['partnership_id'] = $partnership_id = $request->partnership_id;		
		$data['setoranPaket'] = DB::table('setoranPaket')
			    ->join('member_paket', 'setoranPaket.member_paket_id', '=', 'member_paket.id')
			    ->join('member', 'member_paket.member_id', '=', 'member.id')
			    ->join('paket', 'member_paket.paket_id', '=', 'paket.id')
			    ->where('member.partnership_id', $partnership_id)
			    ->where('setoranPaket.submit', 1)
			    ->whereNull('setoranPaket.setor_at')
			    ->groupBy('member.id', 'member.name', 'paket.name', 'setoranPaket.tgl_setor')
			    ->select(
			        'member.name AS namaMember',
			        DB::raw('SUM(setoranPaket.nominal) AS totalNominal'),
			        'member.id AS ids',			        
			    )
			    ->get();
		return view($this->folder.'/tampilData',$data);
	}
	public function create(Request $request){
		$validator = $request->validate([
		  'partnership_id' => 'required',		  		  
		]);
		if ($validator) {
			$codes = generateRandomString(3).cariKode_helper("setoranManajemen");;        			
			$jum  = $request->jum;
			for ($i=1; $i <= $jum; $i++) {                                                          
				if(isset($_POST["chk_".$i])){
					$data = new SetoranManajemenModel;
					$data->code = $codes;
					$data->member_id = $member_id = $_POST["member_id_".$i];                                                                              
					$data->partnership_id = $request->partnership_id;
					$data->tgl_setor = $request->tgl_setor;
					$data->penerima_setoran = $request->penerima_setoran;
					$data->keterangan = $request->keterangan;
					$data->nominal = $_POST["nominal_".$i];
					$data->created_at = Carbon::now()->toDateTimeString();
					$data->created_by = auth()->id();					
					$data->save();              					
					
					$this->ubahStatus($member_id);
				}      
			}			
			session()->put('msg', setMsg("Successed!","success"));        
			return redirect()->route('setoranManajemen.index');
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
			$datas->setor_at = $datas->updated_at = Carbon::now()->toDateTimeString();
			$datas->setor_by = $datas->updated_by = auth()->id();					
			$datas->save();
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

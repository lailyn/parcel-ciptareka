<?php

namespace App\Http\Controllers;

use App\Models\MemberModel;
use App\Models\MemberPaketModel;
use App\Models\PartnershipModel;
use App\Models\PaketModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class MemberController extends Controller
{
	var $set        = "member";
	var $title      = "Membership";
	var $subtitle   = "Membership";
	var $folder     = "master/member";

	public function index(){
		$memberData = MemberModel::orderBy('member.id','DESC')
					->get(['member.*']);               
		$data['title']  = $this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "view";		                  
		return view($this->folder.'/index',$data)->with('member', $memberData);
	}
	public function insert(){
		$data['title']  = "Add ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "insert";		
		return view($this->folder.'/insert',$data);
	}	
	public function create(Request $request){
		$validator = $request->validate([
		  'name' => 'required',		  
		  'no_ktp' => 'required',		  		  		  
		  'no_hp' => 'required',  
		  'foto' => 'mimes:jpeg,jpg,png|max:1000',
		]);
		if ($validator) {
			$data = new MemberModel;
			$data->name = $request->name;        			
			$data->code = generateRandomString(3).cariKode_helper("member");;        			
			$data->no_ktp = $request->no_ktp;        			
			$data->no_hp = $request->no_hp;        
			$data->kecamatan = $request->kecamatan;        
			$data->kota = $request->kota;        
			$data->kodepos = $request->kodepos;        
			$data->akun_instagram = $request->akun_instagram;        
			$data->akun_fb = $request->akun_fb;        
			$data->akun_tiktok = $request->akun_tiktok;        
			$data->partnership_id = $request->partnership_id;        
			$data->alamat = $request->alamat;                			
			$data->status =  (!is_null($request->status))?$request->status:0;           
			$data->created_at = Carbon::now()->toDateTimeString();

			$objectName = 'foto';
			if ($request->hasFile($objectName)) {
				$fileName = time() . '_' . $request->file($objectName)->getClientOriginalName();
				$upload = $request->file($objectName)->move(public_path('ima49es/member'), $fileName);
				$data->foto = $fileName;
			}
					
			$data->save();
			session()->put('msg', setMsg("Successed!","success"));        
			return redirect()->route('member.index');
		}else{
			session()->put('msg', setMsg($validator->errors(),"danger"));        
			echo "<script>history.go(-1)</script>";
		}        
	}
	public function delete($id){
		$data = MemberModel::find($id);
		$data->delete();
		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('member.index');
	}
	public function edit($id){
		$data['title']  = "Edit ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "edit";
		$memberData = MemberModel::find($id);		
		return view($this->folder.'/insert',$data)->with('member', $memberData);
	}
	public function update($id, Request $request){
		$validator = $request->validate([
		  'name' => 'required',		  
		  'no_ktp' => 'required',		  		  		  
		  'no_hp' => 'required',  
		  'foto' => 'mimes:jpeg,jpg,png|max:1000',
		]);
		if ($validator) {
			$data = MemberModel::find($id);
			$data->name = $request->name;        			
			$data->no_ktp = $request->no_ktp;        			
			$data->no_hp = $request->no_hp;        
			$data->kecamatan = $request->kecamatan;        
			$data->kota = $request->kota;        
			$data->kodepos = $request->kodepos;        
			$data->akun_instagram = $request->akun_instagram;        
			$data->akun_fb = $request->akun_fb;        
			$data->akun_tiktok = $request->akun_tiktok;        
			$data->partnership_id = $request->partnership_id;        
			$data->alamat = $request->alamat;                			
			$data->status =  (!is_null($request->status))?$request->status:0;     
			$data->updated_at = Carbon::now()->toDateTimeString();

			$objectName = 'foto';
			$public_path = 'ima49es/member';
			if ($request->hasFile($objectName)) {			
				$image_path = public_path($public_path."/".$data->foto);

				if (File::exists($image_path)) {
					File::delete(public_path($public_path."/".$data->foto));
				}

				$fileName = time().'_'.$request->$objectName->getClientOriginalName();        
				$upload = $request->$objectName->move(public_path($public_path), $fileName);        
				$data->foto = $fileName;
			}
			$data->save();    
			session()->put('msg', setMsg("Successed!","success"));        
			return redirect()->route('member.index');
		}else{
			session()->put('msg', setMsg($validator->errors(),"danger"));        
			echo "<script>history.go(-1)</script>";
		}
	}
	public function jadiPartner($id){		
		$request = MemberModel::find($id);		

		$cek = PartnershipModel::where("no_ktp",$request->no_ktp)->get();
		if($cek->count()){
			$data = PartnershipModel::find($cek->first()->id);
		}else{
			$data = new PartnershipModel;
			$data->code = generateRandomString(3).cariKode_helper("partnership");;        			
		}				
		$data->name = $request->name;        			
		$data->no_ktp = $request->no_ktp;        			
		$data->no_hp = $request->no_hp;        
		$data->kecamatan = $request->kecamatan;        
		$data->kota = $request->kota;        
		$data->kodepos = $request->kodepos;        
		$data->akun_instagram = $request->akun_instagram;        
		$data->akun_fb = $request->akun_fb;        
		$data->akun_tiktok = $request->akun_tiktok;        		
		$data->alamat = $request->alamat;                			
		$data->foto = $request->foto;                			
		$data->status = 1;
		$data->join_at = $data->created_at = Carbon::now()->toDateTimeString();
		
		$data->save();    
		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('member.index');
	}
	public function pilihPaket($id){       
		$data['title']  = "Pilih Paket";
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "detail";
		$memberData = MemberModel::find($id);		
		$data['paket'] = PaketModel::where("status",1)->get();		
		return view($this->folder.'/pilihPaket',$data)->with('member', $memberData);
	}
	public function saveDetail($id, Request $request){       		
		$data2 = new MemberPaketModel;					
		$data2->paket_id = $request->paket_id;
		$data2->member_id = $id;
		$data2->metode_pembayaran = $request->metode_pembayaran;        
		$data2->periode_bayar = $request->periode_bayar;        
		$data2->keterangan = $request->keterangan;        
		$data2->jumlah = $request->jumlah;        		
		$data2->status = $request->status;        		
		$data2->created_at = Carbon::now()->toDateString();
		$data2->save();

		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('member.pilihPaket',$id);                   
	}
	public function deleteDetail($id,$ids){
		$data = MemberPaketModel::find($id);		
		$data->delete();
		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('member.pilihPaket',$ids);
	}
}

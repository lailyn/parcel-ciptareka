<?php

namespace App\Http\Controllers;

use App\Models\PartnershipModel;
use App\Models\LevelModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class PartnershipController extends Controller
{
	var $set        = "partnership";
	var $title      = "Partnership";
	var $subtitle   = "Partnership";
	var $folder     = "master/partnership";

	public function index(){
		$partnershipData = PartnershipModel::orderBy('partnership.id','DESC')->join("level","partnership.level_id","=","level.id")
					->get(['level.name AS level','partnership.*']);               
		$data['title']  = $this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "view";		                  
		return view($this->folder.'/index',$data)->with('partnership', $partnershipData);
	}
	public function akun($id){		
		$amb = PartnershipModel::find($id);		

		$cek = User::where("email",$amb->no_hp)->get();
		if($cek->count()){
			$data = User::find($cek->first()->id);
		}else{
			$data = new User;
		}		
		$data->email = $amb->no_hp;        
		$data->name = $amb->name;        
		$data->no_hp =$amb->no_hp;
		$pwd = get_setting('password_default');
		$data->password = Hash::make($pwd);        		
		$data->id_user_type = 3;
		$data->jenis = "partnership";
		$data->partnership_id = $amb->id;        
		$data->status = 1;		
		$data->updated_at = Carbon::now()->toDateTimeString();
		
		$data->save();    
		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('karyawan.index');
	}
	public function insert(){
		$data['title']  = "Add ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "insert";	
		$data['level'] = LevelModel::All();	
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
			$data = new PartnershipModel;
			$data->name = $request->name;        			
			$data->code = generateRandomString(3).cariKode_helper("partnership");;        			
			$data->no_ktp = $request->no_ktp;        			
			$data->no_hp = $request->no_hp;        
			$data->level_id = $request->level_id;        
			$data->kecamatan = $request->kecamatan;        
			$data->kota = $request->kota;        
			$data->kodepos = $request->kodepos;        
			$data->akun_instagram = $request->akun_instagram;        
			$data->akun_fb = $request->akun_fb;        
			$data->akun_tiktok = $request->akun_tiktok;        
			$data->alamat = $request->alamat;                			
			$data->status =  (!is_null($request->status))?$request->status:0;           
			$data->created_at = Carbon::now()->toDateTimeString();

			$objectName = 'foto';
			if ($request->hasFile($objectName)) {
				$fileName = time() . '_' . $request->file($objectName)->getClientOriginalName();
				$upload = $request->file($objectName)->move(public_path('ima49es/partnership'), $fileName);
				$data->foto = $fileName;
			}
					
			$data->save();
			session()->put('msg', setMsg("Successed!","success"));        
			return redirect()->route('partnership.index');
		}else{
			session()->put('msg', setMsg($validator->errors(),"danger"));        
			echo "<script>history.go(-1)</script>";
		}        
	}
	public function delete($id){
		$data = PartnershipModel::find($id);
		$data->delete();
		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('partnership.index');
	}
	public function edit($id){
		$data['title']  = "Edit ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "edit";
		$data['level'] = LevelModel::All();
		$partnershipData = PartnershipModel::find($id);		
		return view($this->folder.'/insert',$data)->with('partnership', $partnershipData);
	}
	public function update($id, Request $request){
		$validator = $request->validate([
		  'name' => 'required',		  
		  'no_ktp' => 'required',		  		  		  
		  'no_hp' => 'required',  
		  'foto' => 'mimes:jpeg,jpg,png|max:1000',
		]);
		if ($validator) {
			$data = PartnershipModel::find($id);
			$data->name = $request->name;        			
			$data->no_ktp = $request->no_ktp;        			
			$data->no_hp = $request->no_hp;        
			$data->kecamatan = $request->kecamatan;        
			$data->kota = $request->kota;        
			$data->level_id = $request->level_id;        
			$data->kodepos = $request->kodepos;        
			$data->akun_instagram = $request->akun_instagram;        
			$data->akun_fb = $request->akun_fb;        
			$data->akun_tiktok = $request->akun_tiktok;        
			$data->alamat = $request->alamat;                			
			$data->status =  (!is_null($request->status))?$request->status:0;     
			$data->updated_at = Carbon::now()->toDateTimeString();

			$objectName = 'foto';
			$public_path = 'ima49es/partnership';
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
			return redirect()->route('partnership.index');
		}else{
			session()->put('msg', setMsg($validator->errors(),"danger"));        
			echo "<script>history.go(-1)</script>";
		}
	}
}

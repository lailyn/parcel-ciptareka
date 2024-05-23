<?php

namespace App\Http\Controllers;

use App\Models\KaryawanModel;
use App\Models\KaryawanCakupanModel;
use App\Models\JabatanKaryawanModel;
use App\Models\DepartemenModel;
use App\Models\CabangModel;
use App\Models\OutletModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class KaryawanController extends Controller
{
	var $set        = "karyawan";
	var $title      = "Karyawan";
	var $subtitle   = "Master Karyawan";
	var $folder     = "master/karyawan";

	public function index(){
		$karyawanData = KaryawanModel::join('jabatan', 'jabatan.id', '=', 'karyawan.jabatan_id')
					->leftJoin('departemen', 'departemen.id', '=', 'karyawan.departemen_id')
					->leftJoin('cabang', 'cabang.id', '=', 'karyawan.cabang_id')
					->leftJoin('outlet','karyawan.outlet_id','=','outlet.id')
					->orderBy('karyawan.id','DESC')
					->get(['jabatan.name AS jabatan','departemen.name AS departemen','cabang.name AS cabang','outlet.name AS outlet', 'karyawan.*']);               
		$data['title']  = $this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "view";
		// dd($user = Auth::user()->getAttributes());                   
		return view($this->folder.'/index',$data)->with('karyawan', $karyawanData);
	}
	public function insert(){
		$data['title']  = "Add ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "insert";
		$data['jabatanKaryawan'] = JabatanKaryawanModel::where('status',1)->get();
		$data['cabang'] = CabangModel::All();
		$data['departemen'] = DepartemenModel::where('status',1)->get();
		$data['outlet'] = OutletModel::where('status',1)->get();
		return view($this->folder.'/insert',$data);
	}
	public function cakupan($id){
		$data['title']  = "Add Cakupan ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "insert";       
		$data['karyawan_id']    = $id;       
		$data['cakupan'] = DB::table("karyawan_cakupan")->where("karyawan_id",$id)->get(); 
		return view($this->folder.'/cakupan',$data);
	}
	public function saveCakupan(Request $request){
		$data = new KaryawanCakupanModel;
		$data->kecamatan_id = $request->kecamatan_id;
		$data->karyawan_id = $request->karyawan_id;
		$data->save();
		session()->put('msg', setMsg("Successed!","success"));        

		return redirect()->route('karyawan.cakupan', $request->karyawan_id);
	}
	public function delCakupan($id){
		$data = KaryawanCakupanModel::find($id);
		$karyawan_id = $data->first()->karyawan_id;
		$data->delete();
		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('karyawan.cakupan', $karyawan_id);
	}
	public function create(Request $request){
		$validator = $request->validate([
		  'name' => 'required',
		  'jabatan_id' => 'required',		  		  
		  'email' => 'required',		  		  
		  'no_ktp' => 'required',
		  'no_hp' => 'required',  
		  'foto' => 'mimes:jpeg,jpg,png|max:1000',
		]);
		if ($validator) {
			$data = new KaryawanModel;
			$data->name = $request->name;        
			$data->jabatan_id = $request->jabatan_id;        
			$data->departemen_id = $request->departemen_id;        
			$data->cabang_id = $request->cabang_id;        
			$data->outlet_id = $request->outlet_id;        
			$data->jk = $request->jk;        
			$data->agama = $request->agama;        
			$data->no_ktp = $request->no_ktp;        
			$data->no_hp = $request->no_hp;        
			$data->email = $request->email;        
			$data->alamat = $request->alamat;                
			$data->nip = $request->nip;        
			$data->status =  (!is_null($request->status))?$request->status:0;           
			$data->created_at = Carbon::now()->toDateTimeString();

			$objectName = 'foto';
			if ($request->hasFile($objectName)) {
				$fileName = time() . '_' . $request->file($objectName)->getClientOriginalName();
				$upload = $request->file($objectName)->move(public_path('ima49es/karyawan'), $fileName);
				$data->foto = $fileName;
			}
					
			$data->save();
			session()->put('msg', setMsg("Successed!","success"));        
			return redirect()->route('karyawan.index');
		}else{
			session()->put('msg', setMsg($validator->errors(),"danger"));        
			echo "<script>history.go(-1)</script>";
		}        
	}
	public function delete($id){
		$data = KaryawanModel::find($id);
		$data->delete();
		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('karyawan.index');
	}
	public function edit($id){
		$data['title']  = "Edit ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "edit";
		$karyawanData = KaryawanModel::find($id);
		$data['jabatanKaryawan'] = JabatanKaryawanModel::where('status',1)->get();
		$data['cabang'] = CabangModel::All();
		$data['departemen'] = DepartemenModel::where('status',1)->get();
		$data['outlet'] = OutletModel::where('status',1)->get();
		return view($this->folder.'/insert',$data)->with('karyawan', $karyawanData);
	}
	public function update($id, Request $request){
		$validator = $request->validate([
		  'name' => 'required',
		  'jabatan_id' => 'required',		  		  
		  'email' => 'required',		  		  
		  'no_ktp' => 'required',
		  'no_hp' => 'required',  
		  'foto' => 'mimes:jpeg,jpg,png|max:1000',
		]);
		if ($validator) {
			$data = KaryawanModel::find($id);
			$data->name = $request->name;        
			$data->jabatan_id = $request->jabatan_id;        
			$data->departemen_id = $request->departemen_id;        
			$data->cabang_id = $request->cabang_id;        
			$data->outlet_id = $request->outlet_id;        
			$data->jk = $request->jk;        
			$data->agama = $request->agama;        
			$data->no_ktp = $request->no_ktp;        
			$data->email = $request->email;        
			$data->no_hp = $request->no_hp;        
			$data->alamat = $request->alamat;                
			$data->nip = $request->nip;        
			$data->status =  (!is_null($request->status))?$request->status:0;     
			$data->updated_at = Carbon::now()->toDateTimeString();

			$objectName = 'foto';
			$public_path = 'ima49es/karyawan';
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
			return redirect()->route('karyawan.index');
		}else{
			session()->put('msg', setMsg($validator->errors(),"danger"));        
			echo "<script>history.go(-1)</script>";
		}
	}
	public function akun($id){		
		$amb = KaryawanModel::find($id);		

		$cek = User::where("email",$amb->email)->get();
		if($cek->count()){
			$data = User::find($cek->first()->id);
		}else{
			$data = new User;
		}		
		$data->email = $amb->email;        
		$data->name = $amb->name;        
		$data->no_hp =$amb->no_hp;
		$pwd = get_setting('password_default');
		$data->password = Hash::make($pwd);        		
		$data->id_user_type = 2;
		$data->karyawan_id = $amb->id;        
		$data->status = 1;		
		$data->updated_at = Carbon::now()->toDateTimeString();
		
		$data->save();    
		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('karyawan.index');
	}
}

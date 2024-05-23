<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\UsersModel;
use App\Models\KaryawanModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailTes;
use Carbon\Carbon;


class ProfilController extends Controller
{

  
  public function profil(Request $request)
  {
    $user = Auth('sanctum')->user();    
    $karyawan_id = $user->karyawan_id;

    $cekData = KaryawanModel::leftJoin("jabatan","karyawan.jabatan_id","=","jabatan.id")->where("karyawan.id",$karyawan_id)->get(["karyawan.*","jabatan.name AS jabatan"]);
    
    if ($cekData->count()){
      $user = $cekData->first();
      
      $baseUrl = config('app.url');
      if(!is_null($user->foto)) $foto = $user->foto;
        else $foto = "user-default.png";

      $foto = $baseUrl."/ima49es/karyawan/".$foto;

      $data = array(
        "nama" => $user->name,
        "panggilan" => $user->name,
        "email"=> $user->email,
        "no_ktp"=> $user->no_ktp,
        "no_hp"=> $user->no_hp,
        "tipe_user"=> $user->jabatan,
        "foto_profil"=> $foto
      );
      return response()->json(['status' => 1,'message' => 'success','data' => $data], 200);
    }       
    return response()->json(['message' => 'Invalid credentials'], 401);
  }  
  public function gantiPassword(Request $request)
  {    
    $email = $request->email;
    $otp = $request->otp;
    $password_lama = $request->password_lama;
    $password_baru = $request->password_baru;

    $cekData = UsersModel::where("email",$email);

    if($cekData->count()){ 

      if($password_baru==''||$password_lama==''){
        return response()->json(['status' => 0,'message' => 'Password tidak boleh kosong','data' => []], 200);        
        exit();
      }
      
      $cekOtp = $cekData->first()->remember_token;
      if($cekOtp!=$otp){
        return response()->json(['status' => 0,'message' => 'OTP tidak ditemukan','data' => []], 200);        
        exit();
      }
      
      if(!$cekData->count()){
        return response()->json(['status' => 0,'message' => 'Email tidak ditemukan','data' => []], 200);        
        exit();
      }

      $data = UsersModel::find($cekData->first()->id);
      $data->password = Hash::make($password_baru);
      $data->save();

      return response()->json(['status' => 1,'message' => 'success','data' => []], 200);
    }       
    return response()->json(['message' => 'Invalid credentials'], 401);
  }  
  public function kirim_otp_password(Request $request){
    $rand = rand(1000,9999);
    $waktu = Carbon::now()->toDateTimeString();
    $data = UsersModel::where("email",$request->email);
    if($data->count()){
      $isi = $data->first();
      $isi->remember_token = $rand;
      $isi->updated_at = $waktu; 
      $isi->save();

      $data = array(
        "token" => $rand
      );

      return response()->json(['status' => 1,'message' => 'success','data' => $data], 200);
    }else{
      return response()->json(['status' => 0,'message' => 'Email tidak ditemukan','data' => []], 200);
    }
  }
  public function kirimEmail(){
    // $emailData = [
    //     'pesan' => 'Pesan Email', // Data yang ingin Anda sertakan dalam email
    // ];

    // Mail::to('rikelimia@gmail.com')->send(new EmailTes($emailData));

    $hasil = Mail::to("rikelimia@gmail.com")->send(new EmailTes());

    // var_dump($hasil);
    return 'Email telah dikirim!';
  }
	
		
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Mail\MyTestMail;
use Illuminate\Support\Facades\Mail;
use DB;


class AuthController extends Controller
{

	public function index($error = null){
	  return view('auth/login')->with('error', $error);
	}
	public function register()
  {
	  $data['title'] = 'Register';
	  return view('user/register', $data);
  }
  public function register_action()
  {    

    $user = new User([
	    'name' => "Admin",
	    'email' => "admin@gmail.com",
	    'password' => Hash::make("admin"),
    ]);
    $user->save();

    return redirect()->route('login')->with('success', 'Registration success. Please login!');
  }
    

  public function login_action(Request $request){
      $request->validate([
          'username' => 'required',
          'password' => 'required',
          'tipe' => 'required',
          'captcha' => 'required|captcha'
      ],
      ['captcha.captcha'=>'Invalid captcha code!']);

      if($request->tipe=="member"){
        $tgl_lahir = strlen($request->password);
        if($tgl_lahir!=8){
          return back()->withErrors(['password' => 'Format Tgl Lahir Salah! Contoh: 19901231']);
          exit();
        }
        $year = substr($request->password, 0, 4);
        $month = substr($request->password, 4, 2);
        $day = substr($request->password, 6, 2);
        $tgl_lahir_baru = $year . '-' . $month . '-' . $day;
        $cekData = DB::table("member")->where("code",$request->username)->where("tgl_lahir", $tgl_lahir_baru)->get();
        if($cekData->count()>0){
          if($cekData->first()->status==1){
            session([
              'id' => $cekData->first()->id,
              'id_user_type' => 0,
              'tipe' => $request->tipe,
              'jenis' => 'member',
              'username' => $request->username            
            ]);
            return redirect()->intended('/dashboard-member');
          }else{
            return back()->withErrors(['password' => 'Member sudah tidak aktif!']);
            exit();
          }
        }else{
          return back()->withErrors(['password' => 'Data tidak ditemukan!']);
          exit();
        }
      }else{
        if (Auth::attempt(['email' => $request->username, 'password' => $request->password])) {          
          $request->session()->regenerate();
          $user = Auth::user()->getAttributes();                    
          
          session([
            'id' => $user['id'],                        
            'id_user_type' => $user['id_user_type'],                        
            'tipe' => $request->tipe,
            'jenis' => $user['jenis'],
            'partnership_id' => $user['partnership_id'],
            'username' => $request->username            
          ]);
          return redirect()->intended('/dashboard');
        }      
      }

      return back()->withErrors([
          'password' => 'Wrong username or password',
      ]);
  }    
	
	public function logout(){
		if(Auth::check()){
		  Auth::logout();
		};
		return redirect()->intended('b4ckd00R');
	}
  public function kirim_email()
  {
    $data_broadcast = [
        'content' => "<h1>Tessss</h1>",
        'subject' => "Test Kirim E-Mail"
    ];
    try {
        Mail::to('lailynfuad@gmail.com')->send(new MyTestMail((object)$data_broadcast));
    } catch (\Exception $e) {
        dd($e);
        // Log::error('Gagal mengirim E-Mail. email : ' . $user->email . '. error : ' . $e);
    }
  }
}

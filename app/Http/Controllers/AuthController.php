<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Mail\MyTestMail;
use Illuminate\Support\Facades\Mail;

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
    // $request->validate([
    //   'name' => 'required',
    //   'username' => 'required|unique:tb_user',
    //   'password' => 'required',
    //   'password_confirm' => 'required|same:password',
    // ]);

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
          'captcha' => 'required|captcha'
      ],
      ['captcha.captcha'=>'Invalid captcha code!']);


      if (Auth::attempt(['email' => $request->username, 'password' => $request->password])) {          
          $request->session()->regenerate();
          $user = Auth::user()->getAttributes();                    
          
          session([
            'id' => $user['id'],                        
            'id_user_type' => $user['id_user_type'],                        
            'username' => $request->username            
          ]);
          return redirect()->intended('/dashboard');
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

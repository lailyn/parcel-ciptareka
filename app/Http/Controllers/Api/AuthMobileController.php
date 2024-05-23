<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\UserTypeModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthMobileController extends Controller
{

  
  public function login(Request $request)
  {
    $credentials = $request->only('username', 'password');

    if (Auth::attempt(['email' => $request->username, 'password' => $request->password])) {              
      $user = Auth::user();
      $cekUser = (!is_null($user->id_user_type))?UserTypeModel::find($user->id_user_type)->name:"";
      $token = $user->createToken('API Token')->plainTextToken;

      $tokens = explode("|", $token);

      $data = array(
        'token'=>$tokens[1],
        'tipe_user'=>$cekUser
      );
      return response()->json(['status' => 1,'message' => 'success','data' => $data], 200);
    }       
    return response()->json(['message' => 'Invalid credentials'], 401);
  }  
	
	public function logout(Request $request)
  {        
    $request->user()->tokens()->delete();    
    return response()->json(['message' => 'Logged out successfully'], 200);
  }	
}

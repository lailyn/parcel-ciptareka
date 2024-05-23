<?php

namespace App\Http\Controllers;

use App\Models\UsersModel;
use App\Models\UserTypeModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;


class UsersController extends Controller
{
    var $set        = "users";
    var $title      = "Users";
    var $subtitle   = "Master Users";
    var $folder     = "master/users";

    public function index(){
        // echo Uuid::uuid4()->getHex(); die();
        $usersData = UsersModel::leftJoin('user_type', 'user_type.id', '=', 'users.id_user_type')                   
                    ->get(['user_type.name AS user_type', 'users.*']);
        $data['title']  = $this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "view";
        // dd($users = Auth::users()->getAttributes());                   
        return view($this->folder.'/index',$data)->with('users', $usersData);
    }
    public function insert(){
        $data['title']  = "Add ".$this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "insert";
        $data['userType'] = UserTypeModel::where('status',1)->get();
        return view($this->folder.'/insert',$data);
    }
    public function create(Request $request){        

        $data = UsersModel::firstOrNew(['email' => $request->email]);        
        $data->name = $request->name;        
        $data->email = $request->email;        
        $data->password = Hash::make($request->password);        
        $data->id_user_type = $request->id_user_type;        
        $data->status =  (!is_null($request->status))?$request->status:0;           
        $data->created_at = Carbon::now()->toDateTimeString();

        
        if($data->exists){
            session()->put('msg', setMsg("Email Duplicate!","danger"));        
            echo "<script>history.go(-1)</script>";
        }else{
            $data->save();
            session()->put('msg', setMsg("Successed!","success"));        
            return redirect()->route('users.index');
        }            
        
    }
    public function delete($id){
        $data = UsersModel::find($id);
        $data->delete();
        session()->put('msg', setMsg("Successed!","success"));        
        return redirect()->route('users.index');
    }
    public function edit($id){
        $data['title']  = "Edit ".$this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "edit";
        $userData = UsersModel::find($id);
        $data['userType'] = UserTypeModel::where('status',1)->get();
        return view($this->folder.'/insert',$data)->with('user', $userData);
    }
    public function update($id, Request $request){
        $data = UsersModel::find($id);
        $data->name = $request->name;        
        $data->email = $request->email;        
        $data->password = Hash::make($request->password);        
        $data->id_user_type = $request->id_user_type;        
        $data->status =  (!is_null($request->status))?$request->status:0;           
        $data->updated_at = Carbon::now()->toDateTimeString();
        $data->save();    
        session()->put('msg', setMsg("Successed!","success"));        
        return redirect()->route('users.index');
    }

}


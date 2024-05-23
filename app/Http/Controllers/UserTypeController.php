<?php

namespace App\Http\Controllers;

use App\Models\UserTypeModel;
use App\Models\UserAccessModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
class UserTypeController extends Controller
{
    var $set        = "user_type";
    var $title      = "User Type";
    var $subtitle   = "Master Users";
    var $folder     = "master/user_type";

    public function index(){
        $user_typeData     = UserTypeModel::orderBy('name', 'ASC')->get();
        $data['title']  = $this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "view";
        // dd($user = Auth::user()->getAttributes());                   
        return view($this->folder.'/index',$data)->with('user_type', $user_typeData);
    }
    public function insert(){
        $data['title']  = "Add ".$this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "insert";
        return view($this->folder.'/insert',$data);
    }
    public function access($id){
        $data['title']  = "Add ".$this->title;
        $data['subtitle']  = $this->subtitle;
        $data['set']    = $this->set;
        $data['isi']    = $this->set;
        $user_typeData = UserTypeModel::find($id);
        return view($this->folder.'/access',$data)->with('user_type', $user_typeData);
    }
    public function create(Request $request){
        $data = new UserTypeModel; 
        $data->name = $request->name;        
        $data->status =  (!is_null($request->status))?$request->status:0;                
        $data->created_at = Carbon::now()->toDateTimeString();
        $data->save();

        session()->put('msg', setMsg("Successed!","success"));        

        return redirect()->route('user_type.index');
    }
    public function delete($id){
        $data = UserTypeModel::find($id);
        $data->delete();
        session()->put('msg', setMsg("Successed!","success"));        
        return redirect()->route('user_type.index');
    }
    public function edit($id){
        $data['title']  = "Edit ".$this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "edit";
        $user_typeData = UserTypeModel::find($id);
        return view($this->folder.'/insert',$data)->with('user_type', $user_typeData);
    }
    public function update($id, Request $request){
        $data = UserTypeModel::find($id); 
        $data->name = $request->name;        
        $data->status =  (!is_null($request->status))?$request->status:0;              
        $data->updated_at = Carbon::now()->toDateTimeString();
        $data->save();    
        session()->put('msg', setMsg("Successed!","success"));        
        return redirect()->route('user_type.index');
    }
    public function updateAccess($id, Request $request){                        
        $jml            = $request->jml;                                    
        for ($i=1; $i <= $jml; $i++) {
            $id_menu = $_POST["id_menu_".$i];                                  
            $cek = DB::table("user_access")->where("menu_id",$id_menu)->where("user_type_id",$id);                                
            if($cek->count() > 0){            
                $id_a = $cek->first()->id;           
                $data = UserAccessModel::find($id_a);
            }else{
                $data = new UserAccessModel;                
            }            
            $data->menu_id = $id_menu;                                    
            $data->user_type_id = $id;
            if(isset($_POST["view_".$i])) $data->can_view = $_POST["view_".$i];                                   
                else $data["can_view"] = 0;
            if(isset($_POST["insert_".$i])) $data->can_insert = $_POST["insert_".$i];                                 
                else $data["can_insert"] = 0;
            if(isset($_POST["edit_".$i])) $data->can_edit = $_POST["edit_".$i];                                   
                else $data["can_edit"] = 0;
            if(isset($_POST["delete_".$i])) $data->can_delete = $_POST["delete_".$i];                                 
                else $data["can_delete"] = 0;
            $data->save();
                   
        }      
        session()->put('msg', setMsg("Successed!","success"));        
        return redirect()->route('user_type.index');                            
        
    }

}

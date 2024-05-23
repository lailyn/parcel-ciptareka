<?php

namespace App\Http\Controllers;

use App\Models\SettingModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use File;

class SettingController extends Controller
{
	var $set        = "setting";
	var $title      = "Setting";
	var $subtitle   = "Master Setting";
	var $folder     = "master/setting";

	public function index(){
		$settingData     = SettingModel::orderBy('id', 'ASC')->get();
		$data['title']  = $this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "view";		
		return view($this->folder.'/insert',$data)->with('setting', $settingData);
	}		
	public function update(Request $request){
		try {			
			$settingData     = SettingModel::orderBy('id', 'ASC')->get();
			$jum = $request->jum;
			$data = [];
			foreach ($settingData as $key => $row){				
				$id = $_POST["id_".$key];
				$data = SettingModel::find($id);
				if($row->tipe!='file'){										
					$data->value = $request->input('name_'.$row->name);					
				}else{
					$objectName 	= 'name_'.$row->name;					
	        $public_path 	= 'ima49es';
	        $request->validate([$objectName => 'mimes:jpeg,jpg,png|max:1000']);    
	        if($request->hasFile($objectName)) {                          
	          $image_path = public_path($public_path."/".$data->objectName);

            if (File::exists($image_path)) {
              File::delete(public_path($public_path."/".$data->objectName));
            }

            if(null !== $request->$objectName->getClientOriginalName()){
	            $fileName = $request->$objectName->getClientOriginalName();        
		          $upload = $request->$objectName->move(public_path($public_path), $fileName);        
		          $data->value = $fileName;
		        }
	        }
				}
				if(!empty($data)) $data->save();        
			}		
			// dd($data);			
			session()->put('msg', setMsg("Successed!","success"));        
			return redirect()->route('setting.index');	
		} catch (Exception $e) {
			session()->put('msg', setMsg($e,"danger"));        
			return back();						
		}		
	}

}

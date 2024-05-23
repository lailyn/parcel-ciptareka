<?php

namespace App\Http\Controllers;

use App\Models\KelurahanModel;
use App\Models\KecamatanModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DataTables;

class KelurahanController extends Controller
{
	var $set        = "kelurahan";
	var $title      = "Kelurahan";
	var $subtitle   = "Master Wilayah";
	var $folder     = "master/kelurahan";

	public function index(){        
		$kelurahanData = KelurahanModel::join('location_subdistrict', 'location_subdistrict.id_subdistrict', '=', 'location_village.subdistrict_id')                   
					->get(['location_subdistrict.subdistrict', 'location_village.*']);
		$data['title']  = $this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "view";
		// dd($user = Auth::user()->getAttributes());                   
		return view($this->folder.'/index',$data);//->with('kelurahan', $kelurahanData);        
	}
	public function list(Request $request)
	{
		if ($request->ajax()) {
			// $data = KelurahanModel::All();
			$data = KelurahanModel::join('location_subdistrict', 'location_subdistrict.id_subdistrict', '=', 'location_village.subdistrict_id')                                       
					->get(['location_subdistrict.subdistrict', 'location_village.*']);
			return Datatables::of($data)
				->addIndexColumn()
				->addColumn('action', function($row){
					$actionBtn = "
					<div class=\"dropdown\">
						<button class=\"btn btn-circle btn-sm btn-warning\" type=\"button\"
							id=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\"
							aria-expanded=\"false\"> Action <i class=\"fas fa-chevron-down\"></i>
						</button>
						<div class=\"dropdown-menu animated--fade-in\" aria-labelledby=\"dropdownMenuButton\">
							<a class=\"dropdown-item\" href=".route('kelurahan.edit', $row->id_village).">Edit</a> 
							<a class=\"dropdown-item\" href=".route('kelurahan.delete', $row->id_village)." onclick=\"return confirm('Yakin?')\">Delete</a> 
						</div>
					</div>
					";
					return $actionBtn;
				})
				->rawColumns(['action'])
				->make(true);
		}
	}
	public function insert(){
		$data['title']  = "Add ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "insert";
		$data['subdistrict'] = KecamatanModel::All();
		return view($this->folder.'/insert',$data);
	}
	public function create(Request $request){
		$data = new KelurahanModel;
		$data->id_village = $request->id_village;     
		$data->subdistrict_id = $request->subdistrict_id;     
		$data->village = $request->village;               
		$cek = KelurahanModel::find($request->id_village);        
		if($cek){ 
			session()->put('msg', setMsg("Duplicate ID!","danger"));                
			return redirect()->route('kelurahan.index');
		}      
		$data->save();  
		return redirect()->route('kelurahan.index');
	}
	public function delete($id){
		$data = KelurahanModel::find($id);
		$data->delete();
		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('kelurahan.index');
	}
	public function edit($id){
		$data['title']  = "Edit ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "edit";
		$data['subdistrict'] = KecamatanModel::All();
		$kelurahanData = KelurahanModel::find($id);
		return view($this->folder.'/insert',$data)->with('kelurahan', $kelurahanData);
	}
	public function update($id, Request $request){
		$data = KelurahanModel::find($id);
		$data->id_village = $request->id_village;     
		$data->subdistrict_id = $request->subdistrict_id;     
		$data->village = $request->village;         
		if($id!=$request->id_village){
			$cek = KelurahanModel::find($request->id_village);                    
			if($cek) session()->put('msg', setMsg("Duplicate ID!","danger"));                                            
			return redirect()->route('kelurahan.index');
		}        
		$data->save();    
		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('kelurahan.index');
	}
}

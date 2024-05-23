<?php

namespace App\Http\Controllers;

use App\Models\WarehousingModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class WarehousingController extends Controller
{
    var $set    = "warehousing";
    var $title      = "Warehousing";
    var $subtitle   = "Warehousing Baru";
    var $folder = "transaksi/warehousing";

    public function index(){
        $warehousingData = WarehousingModel::All();
        $data['title']  = $this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "view";
        // dd($user = Auth::user()->getAttributes());                   
        return view($this->folder.'/index',$data)->with('warehousing', $warehousingData);
    }
    public function insert(){
        $data['title']  = "Add ".$this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "insert";
        // $data['jenisWarehousing'] = JenisWarehousingModel::where("status",1);
        // $data['tujuan'] = $data['asal'] = KelurahanModel::join('location_subdistrict', 'location_subdistrict.id_subdistrict', '=', 'location_village.subdistrict_id')
        //             ->join('location_cities', 'location_cities.id_cities', '=', 'location_subdistrict.id_cities')
        //             ->join('location_states', 'location_states.id_states', '=', 'location_cities.id_states')                   
        //             ->get(['location_states.states', 'location_cities.cities','location_subdistrict.subdistrict','location_village.*']);
        return view($this->folder.'/insert',$data);
    }
    public function create(Request $request){
        $data = new WarehousingModel;
        $data->name = $request->name;        
        $data->jenis_warehousing_id = $request->jenis_warehousing_id;        
        $data->asal = $request->asal;        
        $data->tujuan = $request->tujuan;        
        $data->durasi = $request->durasi;        
        $data->cost = ubahRupiah($request->cost);        
        $data->cod = ubahRupiah($request->cod);        
        $data->status =  (!is_null($request->status))?$request->status:0;           
        $data->created_at = Carbon::now()->toDateTimeString();
        $data->save();

        session()->put('msg', setMsg("Successed!","success"));        

        return redirect()->route('warehousing.index');
    }
    public function delete($id){
        $data = WarehousingModel::find($id);
        $data->delete();
        session()->put('msg', setMsg("Successed!","success"));        
        return redirect()->route('warehousing.index');
    }
    public function edit($id){
        $data['title']  = "Edit ".$this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "edit";
        $warehousingData = WarehousingModel::find($id);
        $data['jenisWarehousing'] = JenisWarehousingModel::where("status",1);
        $data['tujuan'] = $data['asal'] = KelurahanModel::join('location_subdistrict', 'location_subdistrict.id_subdistrict', '=', 'location_village.subdistrict_id')
                    ->join('location_cities', 'location_cities.id_cities', '=', 'location_subdistrict.id_cities')
                    ->join('location_states', 'location_states.id_states', '=', 'location_cities.id_states')                   
                    ->get(['location_states.states', 'location_cities.cities','location_subdistrict.subdistrict','location_village.*']);
        return view($this->folder.'/insert',$data)->with('warehousing', $warehousingData);
    }
    public function update($id, Request $request){
        $data = WarehousingModel::find($id);
        $data->name = $request->name;        
        $data->jenis_warehousing_id = $request->jenis_warehousing_id;        
        $data->asal = $request->asal;        
        $data->tujuan = $request->tujuan;        
        $data->cost = ubahRupiah($request->cost);        
        $data->cod = ubahRupiah($request->cod);        
        $data->durasi = $request->durasi;        
        $data->status =  (!is_null($request->status))?$request->status:0;           
        $data->updated_at = Carbon::now()->toDateTimeString();
        $data->save();    
        session()->put('msg', setMsg("Successed!","success"));        
        return redirect()->route('warehousing.index');
    }

    public function simpan(){
		$data['title']  = $this->title;
		$data['subtitle']  = "Pilih Gudang";
		$data['isi']    = $this->set;
		$data['set']    = "simpan";     
		return view($this->folder.'/simpan',$data);
	}

}


<?php

namespace App\Http\Controllers;

use App\Models\PengirimanLanjutanModel;
use App\Models\KaryawanModel;
use App\Models\CabangModel;
use App\Models\KendaraanDanPlatModel;
use App\Models\PengirimanModel;
use App\Models\OutletModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PengirimanLanjutanController extends Controller
{
    var $set    = "pengirimanLanjutan";
    var $title      = "PengirimanLanjutan";
    var $subtitle   = "PengirimanLanjutan Baru";
    var $folder = "transaksi/pengirimanLanjutan";

    public function index(){
        $pengirimanLanjutanData = PengirimanLanjutanModel::All();
        $data['title']  = $this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "view";
        // dd($user = Auth::user()->getAttributes());                   
        return view($this->folder.'/index',$data)->with('pengirimanLanjutan', $pengirimanLanjutanData);
    }
    public function insert(){
        $data['title']  = "Tambah ".$this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "insert"; 
        $data['tgl_manifest'] = date("Y-m-d");
        $data['port_asal'] = "";    
        $data['port_tujuan'] = "";    
        $data['outlet_id'] = "";    
        $data['estimasi'] = "";    
        $data['keterangan'] = "";    
        $data['kendaraan_id'] = "";     
        $data['karyawan_id'] = "";      
        $data['kendaraan'] = KendaraanDanPlatModel::where("status",1)->get();       
        $data['outlet'] = OutletModel::where("status",1)->get();        
        $data['karyawan'] = KaryawanModel::where("status",1)->get();        
        $data['cabang'] = CabangModel::All();
        return view($this->folder.'/insert',$data);
    }
    public function create(Request $request){
        // $data = new PengirimanLanjutanModel;
        // $data->name = $request->name;        
        // $data->jenis_pengirimanLanjutan_id = $request->jenis_pengirimanLanjutan_id;        
        // $data->asal = $request->asal;        
        // $data->tujuan = $request->tujuan;        
        // $data->durasi = $request->durasi;        
        // $data->cost = ubahRupiah($request->cost);        
        // $data->cod = ubahRupiah($request->cod);        
        // $data->status =  (!is_null($request->status))?$request->status:0;           
        // $data->created_at = Carbon::now()->toDateTimeString();
        // $data->save();

        // session()->put('msg', setMsg("Successed!","success"));        

        $data['title']  = $this->title;
        $data['subtitle']  = "Create";
        $data['isi']    = $this->set;
        $data['set']    = "create";     
        return view($this->folder.'/insert',$data);
    }
    public function delete($id){
        $data = PengirimanLanjutanModel::find($id);
        $data->delete();
        session()->put('msg', setMsg("Successed!","success"));        
        return redirect()->route('pengirimanLanjutan.index');
    }
    public function edit($id){
        $data['title']  = "Edit ".$this->title;
        $data['subtitle']  = $this->subtitle;
        $data['isi']    = $this->set;
        $data['set']    = "edit";
        $pengirimanLanjutanData = PengirimanLanjutanModel::find($id);
        $data['jenisPengirimanLanjutan'] = JenisPengirimanLanjutanModel::where("status",1);
        $data['tujuan'] = $data['asal'] = KelurahanModel::join('location_subdistrict', 'location_subdistrict.id_subdistrict', '=', 'location_village.subdistrict_id')
                    ->join('location_cities', 'location_cities.id_cities', '=', 'location_subdistrict.id_cities')
                    ->join('location_states', 'location_states.id_states', '=', 'location_cities.id_states')                   
                    ->get(['location_states.states', 'location_cities.cities','location_subdistrict.subdistrict','location_village.*']);
        return view($this->folder.'/insert',$data)->with('pengirimanLanjutan', $pengirimanLanjutanData);
    }
    public function update($id, Request $request){
        $data = PengirimanLanjutanModel::find($id);
        $data->name = $request->name;        
        $data->jenis_pengirimanLanjutan_id = $request->jenis_pengirimanLanjutan_id;        
        $data->asal = $request->asal;        
        $data->tujuan = $request->tujuan;        
        $data->cost = ubahRupiah($request->cost);        
        $data->cod = ubahRupiah($request->cod);        
        $data->durasi = $request->durasi;        
        $data->status =  (!is_null($request->status))?$request->status:0;           
        $data->updated_at = Carbon::now()->toDateTimeString();
        $data->save();    
        session()->put('msg', setMsg("Successed!","success"));        
        return redirect()->route('pengirimanLanjutan.index');
    }

    public function detail(){
		$data['title']  = $this->title;
		$data['subtitle']  = "Detail";
		$data['isi']    = $this->set;
		$data['set']    = "detail";     
		return view($this->folder.'/detail',$data);
	}
    

}


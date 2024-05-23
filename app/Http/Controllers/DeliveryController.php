<?php

namespace App\Http\Controllers;

use App\Models\DeliveryModel;
use App\Models\KaryawanModel;
use App\Models\SuratJalanModel;
use App\Models\PengirimanModel;
use App\Models\SuratJalanDetailModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DeliveryController extends Controller
{
	var $set    = "delivery";
	var $title      = "Delivery";
	var $subtitle   = "Delivery Baru";
	var $folder = "transaksi/delivery";

	public function index(){
		$deliveryData = SuratJalanModel::leftJoin("surat_jalan_detail AS sjd","sjd.code","=","surat_jalan.code")
					->join("pengiriman","sjd.pengiriman_id","=","pengiriman.id")									
					->join("karyawan","surat_jalan.karyawan_id","=","karyawan.id")
					->whereRaw("sjd.diterima_at IS NOT NULL")
					->get(["surat_jalan.*","sjd.*","pengiriman.status AS sta","pengiriman.isi_paket","karyawan.name AS kurir","pengiriman.jumlah_paket","pengiriman.id_kelurahan_penerima","pengiriman.total_tagihan","pengiriman.awb_number","pengiriman.nama_pengirim","pengiriman.nama_penerima"]);				
		$data['title']  = $this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "view";
		$data['karyawan'] = KaryawanModel::where("status",1)->get();                
		return view($this->folder.'/index',$data)->with('delivery', $deliveryData);
	}
	public function insert(){
		$data['title']  = "Add ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "insert";
		$data['no_surat']    = "";
		$data['karyawan_id']    = "";
		$data['cakupan']    = "";
		$data['karyawan'] = KaryawanModel::where("status",1)->get();                
		return view($this->folder.'/insert',$data);
	}
	public function generate(Request $request){
		$data['title']  = "Generate ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "generate";        		
		$data['karyawan'] = KaryawanModel::where("status",1)->get();                
		$data['no_surat'] = $no_surat = $request->no_surat;
		$data['karyawan_id'] = $karyawan_id = $request->karyawan_id;		
		$where = "1=1 ";
		if(!empty($request->no_surat)){
			$where.= " AND surat_jalan.code = '$no_surat'";
		}
		if(!empty($request->karyawan_id)){
			$where.= " AND surat_jalan.karyawan_id = '$karyawan_id'";
		}
		
		$data['manifest'] = SuratJalanModel::leftJoin("surat_jalan_detail AS sjd","sjd.code","=","surat_jalan.code")
					->join("pengiriman","sjd.pengiriman_id","=","pengiriman.id")				
					->whereRaw($where)
					->where("sjd.diterima_at")
					->get(["surat_jalan.*","sjd.pengiriman_id","pengiriman.isi_paket","pengiriman.jumlah_paket","pengiriman.id_kelurahan_penerima","pengiriman.total_tagihan","pengiriman.awb_number","pengiriman.nama_pengirim","pengiriman.nama_penerima"]);		
		return view($this->folder.'/insert',$data);
	}
	public function create(Request $request){
		$data = new DeliveryModel;
		$data->name = $request->name;        
		$data->jenis_delivery_id = $request->jenis_delivery_id;        
		$data->asal = $request->asal;        
		$data->tujuan = $request->tujuan;        
		$data->durasi = $request->durasi;        
		$data->cost = ubahRupiah($request->cost);        
		$data->cod = ubahRupiah($request->cod);        
		$data->status =  (!is_null($request->status))?$request->status:0;           
		$data->created_at = Carbon::now()->toDateTimeString();
		$data->save();

		session()->put('msg', setMsg("Successed!","success"));        

		return redirect()->route('delivery.index');
	}
	public function delete($id){
		$data = DeliveryModel::find($id);
		$data->delete();
		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('delivery.index');
	}
	public function edit($id){
		$data['title']  = "Edit ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "edit";
		$deliveryData = DeliveryModel::find($id);
		$data['jenisDelivery'] = JenisDeliveryModel::where("status",1);
		$data['tujuan'] = $data['asal'] = KelurahanModel::join('location_subdistrict', 'location_subdistrict.id_subdistrict', '=', 'location_village.subdistrict_id')
					->join('location_cities', 'location_cities.id_cities', '=', 'location_subdistrict.id_cities')
					->join('location_states', 'location_states.id_states', '=', 'location_cities.id_states')                   
					->get(['location_states.states', 'location_cities.cities','location_subdistrict.subdistrict','location_village.*']);
		return view($this->folder.'/insert',$data)->with('delivery', $deliveryData);
	}
	public function update($id, Request $request){
		$data = DeliveryModel::find($id);
		$data->name = $request->name;        
		$data->jenis_delivery_id = $request->jenis_delivery_id;        
		$data->asal = $request->asal;        
		$data->tujuan = $request->tujuan;        
		$data->cost = ubahRupiah($request->cost);        
		$data->cod = ubahRupiah($request->cod);        
		$data->durasi = $request->durasi;        
		$data->status =  (!is_null($request->status))?$request->status:0;           
		$data->updated_at = Carbon::now()->toDateTimeString();
		$data->save();    
		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('delivery.index');
	}
	public function simpanDiterima($id,Request $request){        
		
    $data = SuratJalanDetailModel::where("pengiriman_id",$id)->first();
    $data->penerima = $penerima = $request->penerima;
    $data->diterima_at = $data->updated_at = Carbon::now()->toDateTimeString();
    $data->status_utama = "paket_sampai_dipenerima";

    $objectName = 'gambar';
    $request->validate([$objectName => 'required|mimes:jpeg,jpg,png|max:1000']);    
    if($request->file()) {
      $fileName = time().'_'.$request->$objectName->getClientOriginalName();        
      $upload = $request->$objectName->move(public_path('ima49es/delivery'), $fileName);        
      $data->gambar = $fileName;                
    }

    
    $data->save();

    $data2 = PengirimanModel::find($id);
    $data2->updated_at = $data2->tgl_delivered = $waktu = Carbon::now()->toDateTimeString();
    $data2->status = "paket_sampai_dipenerima";
    $data2->save();
    
		simpanStatus($id,$waktu,"paket_sampai_dipenerima","Paket sudah diterima ".$penerima);

    session()->put('msg', setMsg("Successed!","success"));        
    return redirect()->route('delivery.index');
           
  }
  public function simpanTidakDiterima($id,Request $request){        
		
    $data = SuratJalanDetailModel::where("pengiriman_id",$id)->first();
    $data->alasan = $alasan = $request->alasan;
    $data->ditunda_at = $waktu = $data->updated_at = Carbon::now()->toDateTimeString();
    $data->status_utama = "paket_ditunda_penyerahan";

    $data->save();
    
		simpanStatus($id,$waktu,"paket_ditunda_penyerahan","Paket belum diterima karena alasan: ".$alasan);

    session()->put('msg', setMsg("Successed!","success"));        
    return redirect()->route('delivery.index');
           
  }
	public function barangDiterima($id){
		$data['title']  = $this->title;
		$data['subtitle']  = "Barang Diterima";
		$data['isi']    = $this->set;
		$data['set']    = "barangDiterima";     
		$data['pengiriman_id']    = $id;
		return view($this->folder.'/barangDiterima',$data);
	}

	public function barangTidakDiterima($id){
		$data['title']  = $this->title;
		$data['subtitle']  = "Barang Tidak Diterima";
		$data['isi']    = $this->set;
		$data['set']    = "barangTidakDiterima";     
		$data['pengiriman_id']    = $id;
		return view($this->folder.'/barangTidakDiterima',$data);
	}
}


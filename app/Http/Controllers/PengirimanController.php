<?php

namespace App\Http\Controllers;

use App\Models\PengirimanModel;
use App\Models\ProvinsiModel;
use App\Models\KecamatanModel;
use App\Models\JenisTarifModel;
use App\Models\OutletModel;
use App\Models\CabangModel;
use App\Models\TarifModel;
use App\Models\JenisPaketModel;
use App\Models\JadwalPickupModel;
use App\Models\MetodeBayarModel;
use App\Models\ManifestAntarDetailModel;
use App\Models\StatusModel;
use DB;
use PDF;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PengirimanController extends Controller
{
	var $set    = "pengiriman";
	var $title      = "Pengiriman";
	var $subtitle   = "Pengiriman";
	var $folder = "transaksi/pengiriman";

	public function index(){
		$pengirimanData = PengirimanModel::leftJoin('jenis_tarif', 'jenis_tarif.id', '=', 'pengiriman.jenis_tarif_id')                                       
					->orderBy("pengiriman.created_at","DESC")
					->get(['jenis_tarif.name AS jenis_tarif', 'pengiriman.*']);
		$data['title']  = $this->title;
		$data['subtitle']  = $this->subtitle." baru";
		$data['isi']    = "stt-list";		
		$data['set']    = "view";		
		$data['total'] = PengirimanModel::All()->count();
		$data['jemput'] = PengirimanModel::where("status","manifest_jemput")->count();
		$data['antar'] = PengirimanModel::where("status","manifest_antar")->count();;
		$data['lintas'] = PengirimanModel::where("status","manifest_lintas")->count();	
		return view($this->folder.'/index',$data)->with('pengiriman', $pengirimanData);
	}
	public function history(){
		$pengirimanData = PengirimanModel::leftJoin('jenis_tarif', 'jenis_tarif.id', '=', 'pengiriman.jenis_tarif_id')                                       
					->get(['jenis_tarif.name AS jenis_tarif', 'pengiriman.*']);
		$data['title']  = "Riwayat ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "history";	
		$data['total'] = PengirimanModel::All()->count();
		$data['jemput'] = PengirimanModel::where("status","manifest_jemput")->count();;
		$data['antar'] = PengirimanModel::where("status","manifest_antar")->count();;
		$data['lintas'] = PengirimanModel::where("status","manifest_lintas")->count();	
		return view($this->folder.'/index',$data)->with('pengiriman', $pengirimanData);
	}	
	public function dataAjax(Request $request)
  {
		$data = [];

		if($request->has('term')){
			$term = $request->term;
			$query = KecamatanModel::join('location_cities', 'location_cities.id_cities', '=', 'location_subdistrict.id_cities')
					->join('location_states', 'location_states.id_states', '=', 'location_cities.id_states')                   
					->where("location_subdistrict.subdistrict","like","%$term%")
					->orWhere("location_cities.cities","like","%$term%")
					->orWhere("location_states.states","like","%$term%")
					->distinct()
					->get(['location_states.states', 'location_cities.cities','location_subdistrict.*']);
			foreach($query AS $isiData){
				$data[] = array(
					"id"=>$isiData->id_subdistrict,
					"text"=>$isiData->subdistrict.", ".$isiData->cities.", ".$isiData->states
				);
			}
		}
		return response()->json($data);
  }
	public function insert(){
		$data['title']  = "Tambah Pengiriman Paket Baru";
		$data['subtitle']  = "";
		$data['isi']    = "stt-create";
		$data['set']    = "insert";		
		$data['jenisTarif'] = JenisTarifModel::where("status",1)->get();        
		$data['outlet'] = OutletModel::where("status",1)->get();        		
		$data['cabang'] = CabangModel::All();
		$data['jenisPaket'] = JenisPaketModel::where("status",1)->get();        		
		return view($this->folder.'/insert',$data);
	}
	public function cekAsuransi(Request $request)
	{
		$asuransi = get_setting("asuransi");
		$total_tagihan = $request->total_tagihan;
		$hasil = ($asuransi/100) * $total_tagihan;
		return $asuransi."|".$hasil;
	}
	public function cekOngkir(Request $request)	
	{
		$tarifModel = new TarifModel;
		$berat	= $request->berat;		
		$lebar	= $request->lebar;		
		$tinggi	= $request->tinggi;		
		$panjang	= $request->panjang;		
		$jumlah_paket	= $request->jumlah_paket;		
		$asal	= $request->asal;		
		$biaya_asuransi	= $request->biaya_asuransi;
		$tujuan	= $request->tujuan;		
		$jenis_tarif_id	= $request->jenis_tarif_id;		
		$ongkos = 0;$durasi = 1;		
		$cekData = $tarifModel->cekDataOngkir($jenis_tarif_id,$asal,$tujuan);
		if(!empty($cekData)){
			$ongkos = $cekData['ongkos'];
			$durasi = $cekData['durasi'];
		}		

		$konstanta = get_setting("konstanta");
		$cekDimensi = floor(($tinggi * $panjang * $lebar) / $konstanta);
		$cekHargaDimensi = $cekDimensi * $ongkos;
		$cekBerat = ceil($berat/1000);
		$beratAsli = ($cekBerat<=0)?1:$cekBerat;
		$cekHargaBerat = $beratAsli * $ongkos;
		if($cekHargaBerat > $cekHargaDimensi) $ongkos = $cekHargaBerat;
			else $ongkos = $cekHargaDimensi;


		$tagihan = ($ongkos * $jumlah_paket) + $biaya_asuransi;		
		if($ongkos>0) return "exist|".$ongkos."|".$tagihan."|".$durasi;
			else return "Ongkos tidak ditemukan, silakan tambah di menu Tarif|".$ongkos."|".$tagihan."|".$durasi;
		
	}	
	public function cariKode($outlet_id){				
		$sttNumber = sttNumber($outlet_id);		
		return $sttNumber;
	}	
	public function create(Request $request){
		try {
			$cekLokasi = new KecamatanModel;
			$data = new PengirimanModel;
			$data->outlet_id = $outlet_id = $request->outlet_id;        
			$data->port_asal = $request->port_asal;        
			$data->port_tujuan = $request->port_tujuan;        
			$data->code = generateRandomString(3).cariKode_helper("pengiriman");
			$data->stt_number = $this->cariKode($request->port_asal);
			$data->tgl_input = $request->tgl_input;        
			$data->nama_pengirim = $request->nama_pengirim;        
			$data->no_hp_pengirim = $request->no_hp_pengirim;        
			$data->alamat_pengirim = $request->alamat_pengirim;        
			$data->id_kecamatan_pengirim = $id_kecamatan_pengirim = $request->id_kecamatan_pengirim;        
			$data->id_kabupaten_pengirim = $cekLokasi->get_location($id_kecamatan_pengirim)['id_kabupaten'];        
			$data->id_provinsi_pengirim = $cekLokasi->get_location($id_kecamatan_pengirim)['id_provinsi'];
			$data->nama_penerima = $request->nama_penerima;        
			$data->no_hp_penerima = $request->no_hp_penerima;        
			$data->alamat_penerima = $request->alamat_penerima;        
			$data->id_kecamatan_penerima = $id_kecamatan_penerima = $request->id_kecamatan_penerima;        
			$data->id_kabupaten_penerima = $cekLokasi->get_location($id_kecamatan_penerima)['id_kabupaten'];        
			$data->id_provinsi_penerima = $cekLokasi->get_location($id_kecamatan_penerima)['id_provinsi'];
			$data->jenis_pengiriman_id = $request->jenis_paket_id;        
			$data->isi_paket = $request->isi_paket;        
			$data->nilai_paket = $request->nilai_paket;        
			$data->jumlah_paket = $request->jumlah_paket;        
			$data->metode_bayar = $request->metode_bayar;        
			$data->jenis_tarif_id = $request->jenis_tarif_id;        
			$data->ongkir = 	$request->ongkir;        
			$data->biaya_asuransi = $request->biaya_asuransi;        
			$data->total_tagihan = ubahRupiah($request->total_tagihan);        
			$data->ongkir = $request->ongkir;        
			$data->berat = $request->berat;        
			$data->lebar = $request->lebar;        
			$data->panjang = $request->panjang;        
			$data->tinggi = $request->tinggi;        			
			$data->status_bayar =  1;
			$data->status =  "baru";
			$data->created_at = $waktu = Carbon::now()->toDateTimeString();
			$data->save();			

			simpanStatus($data->id,$waktu,"pengiriman_baru",$ket='');

			session()->put('msg', setMsg("Successed!","success"));        

			return redirect()->route('pengiriman.stt-list');
		} catch (Exception $e) {
			session()->put('msg', setMsg($e,"danger"));        
			return back();						
		}
		
	}
	public function delete($id){
		$data = PengirimanModel::find($id);
		$data->delete();
		session()->put('msg', setMsg("Successed!","success"));        
		return redirect()->route('pengiriman.stt-list');
	}
	public function edit($id){
		$data['title']  = "Edit ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "edit";
		$pengirimanData = PengirimanModel::find($id);
		$data['cabang'] = CabangModel::All();
		$data['outlet'] = OutletModel::where("status",1)->get();        		
		$data['jenisTarif'] = JenisTarifModel::where("status",1)->get();        
		$data['jenisPaket'] = JenisPaketModel::where("status",1)->get();        		
		return view($this->folder.'/insert',$data)->with('pengiriman', $pengirimanData);
	}
	public function bayar($id){
		$data['title']  = "Pembayaran ".$this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "bayar";
		$pengirimanData = PengirimanModel::find($id);
		$data['metodeBayar'] = MetodeBayarModel::where("status",1)->get();        
		$data['jadwalPickup'] = JadwalPickupModel::where("status",1)->get();        		
		return view($this->folder.'/bayar',$data)->with('pengiriman', $pengirimanData);
	}
	public function updateBayar($id, Request $request){
		try {
			$data = PengirimanModel::find($id);			
			$data->metode_bayar_id = $request->metode_bayar_id;        			
			$data->status_bayar = $request->status_bayar;        			     		
			$data->updated_at = Carbon::now()->toDateTimeString();
			$data->save();

			session()->put('msg', setMsg("Successed!","success"));        

			return redirect()->route('pengiriman.stt-list');
		} catch (Exception $e) {
			session()->put('msg', setMsg($e,"danger"));        
			return back();						
		}		
	}
	public function update($id, Request $request){
		try {
			$data = PengirimanModel::find($id);			
			$data->nama_pengirim = $request->nama_pengirim;        
			$data->no_hp_pengirim = $request->no_hp_pengirim;        
			$data->alamat_pengirim = $request->alamat_pengirim;        
			$data->id_kelurahan_pengirim = $request->id_kelurahan_pengirim;        
			$data->nama_penerima = $request->nama_penerima;        
			$data->no_hp_penerima = $request->no_hp_penerima;        
			$data->alamat_penerima = $request->alamat_penerima;        
			$data->id_kelurahan_penerima = $request->id_kelurahan_penerima;        
			$data->jenis_pengiriman_id = $request->jenis_paket_id;        
			$data->isi_paket = $request->isi_paket;        
			$data->nilai_paket = $request->nilai_paket;        
			$data->jumlah_paket = $request->jumlah_paket;        
			$data->metode_bayar = $request->metode_bayar;        
			$data->jenis_tarif_id = $request->jenis_tarif_id;        
			$data->ongkir = $request->ongkir;        
			$data->biaya_asuransi = $request->biaya_asuransi;        
			$data->total_tagihan = ubahRupiah($request->total_tagihan);        
			$data->ongkir = $request->ongkir;        
			$data->berat = $request->berat;        
			$data->lebar = $request->lebar;        
			$data->panjang = $request->panjang;        
			$data->tinggi = $request->tinggi;  
			$data->outlet_id = $request->outlet_id;      			
			$data->updated_at = Carbon::now()->toDateTimeString();
			$data->save();

			session()->put('msg', setMsg("Successed!","success"));        

			return redirect()->route('pengiriman.stt-list');
		} catch (Exception $e) {
			session()->put('msg', setMsg($e,"danger"));        
			return back();						
		}		
	}
	public function cetakSTT($id){
		$pengirimanData = PengirimanModel::find($id);		
		// return view($this->folder.'/cetakSTT')->with('pengiriman', $pengirimanData);
		$pdf = PDF::loadview($this->folder.'/cetakSTT',['pengiriman'=>$pengirimanData]);		
		return $pdf->download('cetak-STT.pdf');
	}
	public function lacak(){
		$data['stt_number'] = "";
		$data['status'] = "";
		$data['title']  = "Pelacakan ".$this->title;
		$data['subtitle']  = "";
		$data['timelineStatus'] = "";
		$data['isi']    = "pelacakan";
		$data['set']    = "pelacakan";		
		return view($this->folder.'/pelacakan',$data);
	}
	public function lacakPengiriman(Request $request){				
			$data['title']  = "Pelacakan ".$this->title;
			$data['subtitle']  = "";
			$data['isi']    = "pelacakan";
			$data['set']    = "pelacakan";		
			$data['status'] = "";
			$data['timelineStatus'] = "";
			$data['stt_number'] = $request->stt_number;        
			$cek = PengirimanModel::where("stt_number",$request->stt_number);
			if($cek->count()){
				$dt = $cek->first();				
				$data['timelineStatus'] = DB::table("table_status")->where("pengiriman_id",$dt->id)->orderby('updated_at','DESC')->get();				
				$data['status'] = "Status Pengiriman Saat ini: <strong> ".$dt->status."</strong>";        				
				$data['dts'] = PengirimanModel::find($dt->id);
    		$data['dt_delivery'] = ManifestAntarDetailModel::where("pengiriman_id",$dt->id)->first();

				session()->put('msg', setMsg("Successed!","success"));        
			}else{
				$data['status'] = "AWB Number tidak ditemukan!";        
				session()->put('msg', setMsg("Successed!","danger"));        
			}			
			return view($this->folder.'/pelacakan',$data);			
	}
}


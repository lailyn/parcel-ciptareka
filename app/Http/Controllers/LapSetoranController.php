<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SetoranPaketModel;
use App\Models\SetoranManajemenModel;
use App\Models\PartnershipModel;
use App\Models\PengembalianSetoranModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use DB;
use PDF;

class LapSetoranController extends Controller
{
	
	var $set        = "lapSetoran";
	var $title      = "Laporan Setoran";
	var $subtitle   = "Laporan Setoran";
	var $folder     = "laporan/lapSetoran";

	public function index(){
		$lapSetoran = "";
		$data['title']  = $this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "view";
		$data['filter_1']="";$data['filter_2']=date("Y-m")."-01";$data['filter_3']=date("Y-m-d");
		return view($this->folder.'/index',$data)->with('lapSetoran', $lapSetoran);
	}
	public function filter(Request $request){		
		$data['filter_1']="";$data['filter_2']=date("Y-m")."-01";$data['filter_3']=date("Y-m-d");
		$where="1=1";$file = "index";
		if(isset($request->jenis)){ 					
			$data['filter_1'] = $request->jenis;			
			if($request->jenis=="setoran_member"){								
				if(isset($request->tgl_awal)){ 
					$where.= " AND setoranPaket.tgl_setor BETWEEN '$request->tgl_awal' AND '$request->tgl_akhir'";
					$data['filter_2'] = $request->tgl_awal;
					$data['filter_3'] = $request->tgl_akhir;
				}
				$data['dt_setoran'] = SetoranPaketModel::whereRaw($where)
					->join("member_paket","setoranPaket.member_paket_id","=","member_paket.id")
					->join("member","member_paket.member_id","=","member.id")
					->join("paket","member_paket.paket_id","=","paket.id")
					->orderBy('setoranPaket.id', 'DESC')
					->where("setoranPaket.submit",1)
					->get(['setoranPaket.*','member.name AS namaMember',"paket.name AS namaPaket"]);							
			}elseif($request->jenis=="setoran_manajemen"){								
				if(isset($request->tgl_awal)){ 
					$where.= " AND setoranManajemen.tgl_setor BETWEEN '$request->tgl_awal' AND '$request->tgl_akhir'";
					$data['filter_2'] = $request->tgl_awal;
					$data['filter_3'] = $request->tgl_akhir;
				}
				$data['dt_setoran'] = SetoranManajemenModel::join("partnership","setoranManajemen.partnership_id","=","partnership.id")
					->orderBy('setoranManajemen.id', 'DESC')
					->whereRaw($where)
					->where("setoranManajemen.approval_status",1)
					->get(['setoranManajemen.*','partnership.name AS namaPartner']);							
			}else{
				if(isset($request->tgl_awal)){ 
					$where.= " AND pengembalianSetoran.tgl_pengembalian BETWEEN '$request->tgl_awal' AND '$request->tgl_akhir'";
					$data['filter_2'] = $request->tgl_awal;
					$data['filter_3'] = $request->tgl_akhir;
				}
				$data['dt_setoran'] = PengembalianSetoranModel::join("member_paket","pengembalianSetoran.member_paket_id","=","member_paket.id")
					->join("member","member_paket.member_id","=","member.id")
					->join("paket","member_paket.paket_id","=","paket.id")
					->whereRaw($where)
					->orderBy('pengembalianSetoran.id','DESC')
					->get(['pengembalianSetoran.*','member.name AS namaMember','paket.name AS namaPaket']);               
			}		
		}
						
		$data['title']  = $this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "filter";
		$data['filter']    = "";		
		$submit  = $request->submit;
		if($submit=="filter"){
			$link = view($this->folder."/".$file,$data);
		}elseif($submit=="xlsxFormat"){
			$link = view($this->folder.'/'.$fileXls,$data);
		}elseif($submit=="pdfFormat"){			
			$link = view($this->folder.'/'.$filePdf,$data);
		}

		return $link;
		
		
		
	}
}


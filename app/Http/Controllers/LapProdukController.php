<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ProdukModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use DB;
use PDF;

class LapProdukController extends Controller
{
	
	var $set        = "lapProduk";
	var $title      = "Laporan Produk";
	var $subtitle   = "Laporan Produk";
	var $folder     = "laporan/lapProduk";

	public function index(){
		$lapProduk = "";
		$data['title']  = $this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "view";
		$data['filter_1']="";
		return view($this->folder.'/index',$data)->with('lapProduk', $lapProduk);
	}
	public function filter(Request $request){		
		$data['filter_1'] = "";
		$where="1=1";
		if(isset($request->nama_produk)){ 
			$where.= " AND produk.name LIKE '%$request->nama_produk%'";
			$data['filter_1'] = $request->nama_produk;			
		}
		
		$file = "index";
		$fileXls = "xlsx";
		$filePdf = "pdf";
		$data['dt_produk'] = ProdukModel::whereRaw($where)
			->orderBy('produk.name', 'ASC')
			->get();							
						
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


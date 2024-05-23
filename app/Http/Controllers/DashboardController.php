<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
		var $set    = "dashboard";
    var $folder = "";
    var $title      = "Dashboard";
    var $subtitle   = "Selamat Datang";

	public function index()
	{
	  $data['title'] = 'Dashboard';	  
	  $data['isi'] = $this->set;
	  $data['subtitle'] = get_setting('app');	  	 
	  return view('dashboard', $data);
	}
}

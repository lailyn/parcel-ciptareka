
	<!-- Side Navigation -->
	<div class="content-side content-side-full">
		<ul class="nav-main">
			<?php       
      $act="";$show="";
      if(setMenu('dashboard')!=''){           
        $show = 'd-none';                        
      }else{            
        if($isi=='dashboard'){
          $act = "active"; 
          $show = "menu-open"; 
        }
      }                   
      ?>
			<li <?= setMenu('dashboard') ?> class="nav-main-item">
				<a class="nav-main-link {{ $act }}" href="{{ route('dashboard.index') }}">
					<i class="nav-main-link-icon si si-speedometer"></i>
					<span class="nav-main-link-name">Dashboard</span>
				</a>
			</li>
			<?php       
      $act="";$show="";
      if(setMenu('pelacakan')!=''){           
        $show = 'd-none';                        
      }else{            
        if($isi=='pelacakan'){
          $act = "active"; 
          $show = "menu-open"; 
        }
      }              
      ?>
			<li class="nav-main-item">
				<a class="nav-main-link {{ $act }}" href="{{ route('pengiriman.lacak') }}">
					<i class="nav-main-link-icon si si-plane"></i>
					<span class="nav-main-link-name">Pelacakan</span>
				</a>
			</li>
			<li class="nav-main-heading">Transaksi</li>
			<?php       
      $act="";$show="";
      if(setMenu('stt-create')!=''){           
        $show = 'd-none';                        
      }else{            
        if($isi=='stt-create'){
          $act = "active"; 
          $show = "menu-open"; 
        }
      }              
      ?>
			<li class="nav-main-item">
				<a class="nav-main-link {{ $act }}" href="{{ route('pengiriman.stt-create') }}">
					<i class="nav-main-link-icon si si-energy"></i>
					<span class="nav-main-link-name">STT Create</span>
				</a>
			</li>
			<?php       
      $act="";$show="";
      if(setMenu('stt-list')!=''){           
        $show = 'd-none';                        
      }else{            
        if($isi=='stt-list'){
          $act = "active"; 
          $show = "menu-open"; 
        }
      }              
      ?>
			<li class="nav-main-item">
				<a class="nav-main-link {{ $act }}" href="{{ route('pengiriman.stt-list') }}">
					<i class="nav-main-link-icon si si-energy"></i>
					<span class="nav-main-link-name">STT Lists</span>
				</a>
			</li>
			<?php       
      $act="";$show="";
      if(setMenu('manifestJemput-insert')!='' && setMenu('manifestJemput-list')!=''){
        $show = 'd-none';                        
      }else{            
        if($isi=='manifestJemput-insert' || $isi=='manifestJemput-list'){
          $act = "active"; 
          $show = "open"; 
        }
      }              
      ?>			
			<li class="nav-main-item {{ $show }}">
				<a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
					<i class="nav-main-link-icon si si-energy"></i>
					<span class="nav-main-link-name">Manifest Jemput</span>
				</a>
				<ul class="nav-main-submenu">								
					<li <?= setMenu('manifestJemput-insert') ?> class="nav-main-item">
						<a class="nav-main-link <?=($isi=='manifestJemput-insert')?'active':'';?>" href="{{ route('manifestJemput.insert') }}">						
							<span class="nav-main-link-name">Create</span>
						</a>
					</li>
					<li <?= setMenu('manifestJemput-list') ?> class="nav-main-item">
						<a class="nav-main-link <?=($isi=='manifestJemput-list')?'active':'';?>" href="{{ route('manifestJemput.list') }}">						
							<span class="nav-main-link-name">List</span>
						</a>
					</li>					
				</ul>
			</li>

			<?php       
      $act="";$show="";
      if(setMenu('manifestLintas-insert')!='' && setMenu('manifestLintas-list')!=''){
        $show = 'd-none';                        
      }else{            
        if($isi=='manifestLintas-insert' || $isi=='manifestLintas-list'){
          $act = "active"; 
          $show = "open"; 
        }
      }              
      ?>			
			<li class="nav-main-item {{ $show }}">
				<a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
					<i class="nav-main-link-icon si si-energy"></i>
					<span class="nav-main-link-name">Manifest Lintas</span>
				</a>
				<ul class="nav-main-submenu">								
					<li <?= setMenu('manifestLintas-insert') ?> class="nav-main-item">
						<a class="nav-main-link <?=($isi=='manifestLintas-insert')?'active':'';?>" href="{{ route('manifestLintas.insert') }}">						
							<span class="nav-main-link-name">Create</span>
						</a>
					</li>
					<li <?= setMenu('manifestLintas-list') ?> class="nav-main-item">
						<a class="nav-main-link <?=($isi=='manifestLintas-list')?'active':'';?>" href="{{ route('manifestLintas.list') }}">						
							<span class="nav-main-link-name">List</span>
						</a>
					</li>					
				</ul>
			</li>

			<?php       
      $act="";$show="";
      if(setMenu('manifestUtama-insert')!='' && setMenu('manifestUtama-list')!=''){
        $show = 'd-none';                        
      }else{            
        if($isi=='manifestUtama-insert' || $isi=='manifestUtama-list'){
          $act = "active"; 
          $show = "open"; 
        }
      }              
      ?>			
			<li class="nav-main-item {{ $show }}">
				<a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
					<i class="nav-main-link-icon si si-energy"></i>
					<span class="nav-main-link-name">Manifest Utama</span>
				</a>
				<ul class="nav-main-submenu">								
					<li <?= setMenu('manifestUtama-insert') ?> class="nav-main-item">
						<a class="nav-main-link <?=($isi=='manifestUtama-insert')?'active':'';?>" href="{{ route('manifestUtama.insert') }}">						
							<span class="nav-main-link-name">Create</span>
						</a>
					</li>
					<li <?= setMenu('manifestUtama-list') ?> class="nav-main-item">
						<a class="nav-main-link <?=($isi=='manifestUtama-list')?'active':'';?>" href="{{ route('manifestUtama.list') }}">						
							<span class="nav-main-link-name">List</span>
						</a>
					</li>					
				</ul>
			</li>

			<?php       
      $act="";$show="";
      if(setMenu('manifestAntar-insert')!='' && setMenu('manifestAntar-list')!=''){
        $show = 'd-none';                        
      }else{            
        if($isi=='manifestAntar-insert' || $isi=='manifestAntar-list'){
          $act = "active"; 
          $show = "open"; 
        }
      }              
      ?>			
			<li class="nav-main-item {{ $show }}">
				<a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
					<i class="nav-main-link-icon si si-energy"></i>
					<span class="nav-main-link-name">Manifest Antar</span>
				</a>
				<ul class="nav-main-submenu">								
					<li <?= setMenu('manifestAntar-insert') ?> class="nav-main-item">
						<a class="nav-main-link <?=($isi=='manifestAntar-insert')?'active':'';?>" href="{{ route('manifestAntar.insert') }}">						
							<span class="nav-main-link-name">Create</span>
						</a>
					</li>
					<li <?= setMenu('manifestAntar-list') ?> class="nav-main-item">
						<a class="nav-main-link <?=($isi=='manifestAntar-list')?'active':'';?>" href="{{ route('manifestAntar.list') }}">						
							<span class="nav-main-link-name">List</span>
						</a>
					</li>					
				</ul>
			</li>





			
			
																					
			<li class="nav-main-heading">Master Data</li>
			<?php       
      $act="";$show="";
      if(setMenu('level')!=''){           
        $show = 'd-none';                        
      }else{            
        if($isi=='level'){
          $act = "active"; 
          $show = "menu-open"; 
        }
      }              
      ?>
			<li <?= setMenu('level') ?> class="nav-main-item">				
				<a class="nav-main-link {{ $act }}" href="{{ route('level.index') }}">
					<i class="nav-main-link-icon si si-direction"></i>
					<span class="nav-main-link-name">Level</span>
				</a>
			</li>
			<?php       
      $act="";$show="";
      if(setMenu('periode')!=''){           
        $show = 'd-none';                        
      }else{            
        if($isi=='periode'){
          $act = "active"; 
          $show = "menu-open"; 
        }
      }              
      ?>
			<li <?= setMenu('periode') ?> class="nav-main-item">				
				<a class="nav-main-link {{ $act }}" href="{{ route('periode.index') }}">
					<i class="nav-main-link-icon si si-calendar"></i>
					<span class="nav-main-link-name">Periode</span>
				</a>
			</li>
			
			<?php       
      $act="";$show="";
      if(setMenu('produk')!='' && setMenu('paket')!=''){
        $show = 'd-none';                        
      }else{            
        if($isi=='produk' || $isi=='paket'){
          $act = "active"; 
          $show = "open"; 
        }
      }              
      ?>				
			<li class="nav-main-item {{ $show }}">
				<a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
					<i class="nav-main-link-icon si si-present"></i>
					<span class="nav-main-link-name">Paket</span>
				</a>
				<ul class="nav-main-submenu">
					<li <?= setMenu('produk') ?> class="nav-main-item">
						<a class="nav-main-link <?=($isi=='produk')?'active':'';?>" href="{{ route('produk.index') }}">
							<span class="nav-main-link-name">Produk</span>
						</a>
					</li>						
				</ul>
				<ul class="nav-main-submenu">
					<li <?= setMenu('paket') ?> class="nav-main-item">
						<a class="nav-main-link <?=($isi=='paket')?'active':'';?>" href="{{ route('paket.index') }}">
							<span class="nav-main-link-name">Paket</span>
						</a>
					</li>						
				</ul>
			</li>	

			<?php       
      $act="";$show="";
      if(setMenu('outlet')!=''){           
        $show = 'd-none';                        
      }else{            
        if($isi=='outlet'){
          $act = "active"; 
          $show = "menu-open"; 
        }
      }              
      ?>
			<li <?= setMenu('outlet') ?> class="nav-main-item">				
				<a class="nav-main-link {{ $act }}" href="{{ route('outlet.index') }}">
					<i class="nav-main-link-icon fa fa-archway"></i>
					<span class="nav-main-link-name">Outlet</span>
				</a>
			</li>
			
			<?php       
      $act="";$show="";
      if(setMenu('negara')!='' && setMenu('provinsi')!='' && setMenu('kabupaten')!='' && setMenu('kecamatan')!='' && setMenu('kelurahan')!=''){
        $show = 'd-none';                        
      }else{            
        if($isi=='negara' || $isi=='provinsi' || $isi=='kabupaten' || $isi=='kecamatan' || $isi=='kelurahan'){
          $act = "active"; 
          $show = "open"; 
        }
      }              
      ?>
			<li class="nav-main-item {{ $show }}">
				<a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
					<i class="nav-main-link-icon si si-map"></i>
					<span class="nav-main-link-name">Wilayah</span>
				</a>
				<ul class="nav-main-submenu">
					<li <?= setMenu('negara') ?> class="nav-main-item">
						<a class="nav-main-link <?=($isi=='negara')?'active':'';?>" href="{{ route('negara.index') }}">
							<span class="nav-main-link-name">Negara</span>
						</a>
					</li>
					<li <?= setMenu('provinsi') ?> class="nav-main-item">
						<a class="nav-main-link <?=($isi=='provinsi')?'active':'';?>" href="{{ route('provinsi.index') }}">
							<span class="nav-main-link-name">Provinsi</span>
						</a>
					</li>
					<li <?= setMenu('kabupaten') ?> class="nav-main-item">
						<a class="nav-main-link <?=($isi=='kabupaten')?'active':'';?>" href="{{ route('kabupaten.index') }}">
							<span class="nav-main-link-name">Kab/Kota</span>
						</a>
					</li>
					<li <?= setMenu('kecamatan') ?> class="nav-main-item">
						<a class="nav-main-link <?=($isi=='kecamatan')?'active':'';?>" href="{{ route('kecamatan.index') }}">
							<span class="nav-main-link-name">Kecamatan</span>
						</a>
					</li>
					<li <?= setMenu('kelurahan') ?> class="nav-main-item">
						<a class="nav-main-link <?=($isi=='kelurahan')?'active':'';?>" href="{{ route('kelurahan.index') }}">
							<span class="nav-main-link-name">Kelurahan</span>
						</a>
					</li>								
				</ul>
			</li>	
			<?php       
      $act="";$show="";
      if(setMenu('satuan_paket')!='' && setMenu('satuan_berat')!=''){
        $show = 'd-none';                        
      }else{            
        if($isi=='satuan_paket' || $isi=='satuan_berat'){
          $act = "active"; 
          $show = "open"; 
        }
      }              
      ?>					
			<li class="nav-main-item {{ $show }}">
				<a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
					<i class="nav-main-link-icon si si-layers"></i>
					<span class="nav-main-link-name">Satuan</span>
				</a>
				<ul class="nav-main-submenu">
					<li <?= setMenu('satuan_paket') ?> class="nav-main-item">
						<a class="nav-main-link <?=($isi=='satuan_paket')?'active':'';?>" href="{{ route('satuan_paket.index') }}">
							<span class="nav-main-link-name">Satuan Paket</span>
						</a>
					</li>
					<li <?= setMenu('satuan_berat') ?> class="nav-main-item">
						<a  class="nav-main-link <?=($isi=='satuan_berat')?'active':'';?>" href="{{ route('satuan_berat.index') }}">
							<span class="nav-main-link-name">Satuan Berat</span>
						</a>
					</li>							
				</ul>
			</li>		
				
			<?php       
      $act="";$show="";
      if(setMenu('jenis_tarif')!='' && setMenu('metode_bayar')!='' && setMenu('jenis_metode')!='' && setMenu('tarif')!=''){
        $show = 'd-none';                        
      }else{            
        if($isi=='jenis_tarif' || $isi=='metode_bayar' || $isi=='jenis_metode' || $isi=='tarif'){
          $act = "active"; 
          $show = "open"; 
        }
      }              
      ?>				
			<li class="nav-main-item {{ $show }}">
				<a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
					<i class="nav-main-link-icon si si-tag"></i>
					<span class="nav-main-link-name">Tarif</span>
				</a>
				<ul class="nav-main-submenu">
					<li <?= setMenu('jenis_tarif') ?> class="nav-main-item">
						<a class="nav-main-link <?=($isi=='jenis_tarif')?'active':'';?>" href="{{ route('jenis_tarif.index') }}">
							<span class="nav-main-link-name">Jenis Tarif</span>
						</a>
					</li>						
				</ul>
				<ul class="nav-main-submenu">
					<li <?= setMenu('tarif') ?> class="nav-main-item">
						<a class="nav-main-link <?=($isi=='tarif')?'active':'';?>" href="{{ route('tarif.index') }}">
							<span class="nav-main-link-name">Tarif</span>
						</a>
					</li>						
				</ul>
				<ul class="nav-main-submenu">
					<li <?= setMenu('jenis_metode') ?> class="nav-main-item">
						<a class="nav-main-link <?=($isi=='jenis_metode')?'active':'';?>" href="{{ route('jenis_metode.index') }}">
							<span class="nav-main-link-name">Jenis Metode Bayar</span>
						</a>
					</li>						
				</ul>
				<ul class="nav-main-submenu">
					<li <?= setMenu('metode_bayar') ?> class="nav-main-item">
						<a class="nav-main-link <?=($isi=='metode_bayar')?'active':'';?>" href="{{ route('metode_bayar.index') }}">
							<span class="nav-main-link-name">Metode Bayar</span>
						</a>
					</li>						
				</ul>
			</li>			
			<?php       
      $act="";$show="";
      if(setMenu('jabatan_karyawan')!='' && setMenu('karyawan')!='' && setMenu('departemen')!=''){
        $show = 'd-none';                        
      }else{            
        if($isi=='jabatan_karyawan' || $isi=='karyawan' || $isi=='departemen'){
          $act = "active"; 
          $show = "open"; 
        }
      }              
      ?>			
			<li class="nav-main-item {{ $show }}">
				<a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
					<i class="nav-main-link-icon si si-user"></i>
					<span class="nav-main-link-name">Karyawan</span>
				</a>
				<ul class="nav-main-submenu">
					<li <?= setMenu('jabatan_karyawan') ?> class="nav-main-item">
						<a class="nav-main-link <?=($isi=='jabatan_karyawan')?'active':'';?>" href="{{ route('jabatan_karyawan.index') }}">
							<span class="nav-main-link-name">Jabatan Karyawan</span>
						</a>
					</li>						
				</ul>
				<ul class="nav-main-submenu">
					<li <?= setMenu('departemen') ?> class="nav-main-item">
						<a class="nav-main-link <?=($isi=='departemen')?'active':'';?>" href="{{ route('departemen.index') }}">
							<span class="nav-main-link-name">Departemen</span>
						</a>
					</li>						
				</ul>
				<ul class="nav-main-submenu">
					<li <?= setMenu('karyawan') ?> class="nav-main-item">
						<a class="nav-main-link <?=($isi=='karyawan')?'active':'';?>" href="{{ route('karyawan.index') }}">
							<span class="nav-main-link-name">Karyawan</span>
						</a>
					</li>						
				</ul>
			</li>						
			<?php       
      $act="";$show="";
      if(setMenu('jenis_kendaraan')!='' && setMenu('kendaraan_dan_plat')!=''){
        $show = 'd-none';                        
      }else{            
        if($isi=='jenis_kendaraan' || $isi=='kendaraan_dan_plat'){
          $act = "active"; 
          $show = "open"; 
        }
      }              
      ?>
			<li class="nav-main-item {{ $show }}">
				<a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
					<i class="nav-main-link-icon fa fa-truck"></i>
					<span class="nav-main-link-name">Kendaraan</span>
				</a>
				<ul class="nav-main-submenu">
					<li class="nav-main-item">
						<a class="nav-main-link <?=($isi=='jenis_kendaraan')?'active':'';?>" href="{{ route('jenis_kendaraan.index') }}">
							<span class="nav-main-link-name">Jenis Kendaraan</span>
						</a>
					</li>						
				</ul>
				<ul class="nav-main-submenu">
					<li class="nav-main-item">
						<a class="nav-main-link <?=($isi=='kendaraan_dan_plat')?'active':'';?>" href="{{ route('kendaraan_dan_plat.index') }}">
							<span class="nav-main-link-name">Kendaraan dan Plat</span>
						</a>
					</li>						
				</ul>
			</li>	
			<?php       
      $act="";$show="";
      if(setMenu('supplier')!=''){           
        $show = 'd-none';                        
      }else{            
        if($isi=='supplier'){
          $act = "active"; 
          $show = "menu-open"; 
        }
      }              
      ?>
			<li <?= setMenu('supplier') ?> class="nav-main-item">				
				<a class="nav-main-link {{ $act }}" href="{{ route('supplier.index') }}">
					<i class="nav-main-link-icon fa fa-truck-loading"></i>
					<span class="nav-main-link-name">Supplier</span>
				</a>
			</li>					
			<?php       
      $act="";$show="";
      if(setMenu('jadwal_pickup')!=''){           
        $show = 'd-none';                        
      }else{            
        if($isi=='jadwal_pickup'){
          $act = "active"; 
          $show = "menu-open"; 
        }
      }              
      ?>			
			<li <?= setMenu('jadwal_pickup') ?> class="nav-main-item">				
				<a class="nav-main-link {{ $act }}" href="{{ route('jadwal_pickup.index') }}">
					<i class="nav-main-link-icon si si-calendar"></i>
					<span class="nav-main-link-name">Jadwal Pickup</span>
				</a>
			</li>	
			<?php       
      $act="";$show="";
      if(setMenu('jenis_paket')!=''){           
        $show = 'd-none';                        
      }else{            
        if($isi=='jenis_paket'){
          $act = "active"; 
          $show = "menu-open"; 
        }
      }              
      ?>			
			<li <?= setMenu('jenis_paket') ?> class="nav-main-item">				
				<a class="nav-main-link {{ $act }}" href="{{ route('jenis_paket.index') }}">
					<i class="nav-main-link-icon si si-social-dropbox"></i>
					<span class="nav-main-link-name">Jenis Paket</span>
				</a>
			</li>	
			<?php       
      $act="";$show="";
      if(setMenu('users')!='' && setMenu('user_type')!=''){
        $show = 'd-none';                        
      }else{            
        if($isi=='users' || $isi=='user_type'){
          $act = "active"; 
          $show = "open"; 
        }
      }              
      ?>
			<li class="nav-main-item {{ $show }}">
				<a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
					<i class="nav-main-link-icon si si-users"></i>
					<span class="nav-main-link-name">User</span>
				</a>
				<ul class="nav-main-submenu">
					<li class="nav-main-item">
						<a class="nav-main-link <?=($isi=='user_type')?'active':'';?>" href="{{ route('user_type.index') }}">
							<span class="nav-main-link-name">User Type</span>
						</a>
					</li>						
				</ul>
				<ul class="nav-main-submenu">
					<li class="nav-main-item">
						<a class="nav-main-link <?=($isi=='users')?'active':'';?>" href="{{ route('users.index') }}">
							<span class="nav-main-link-name">Users</span>
						</a>
					</li>						
				</ul>
			</li>
			<?php       
      $act="";$show="";
      if(setMenu('setting')!=''){           
        $show = 'd-none';                        
      }else{            
        if($isi=='setting'){
          $act = "active"; 
          $show = "menu-open"; 
        }
      }              
      ?>
			<li <?= setMenu('setting') ?> class="nav-main-item">
				<a class="nav-main-link {{ $act }}" href="{{ route('setting.index') }}">
					<i class="nav-main-link-icon si si-settings"></i>
					<span class="nav-main-link-name">Setting</span>
				</a>
			</li>					
		</ul>		
	</div>
	<!-- END Side Navigation -->
</nav>
<!-- END Sidebar -->

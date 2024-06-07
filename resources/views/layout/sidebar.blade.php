
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
			<li class="nav-main-heading">Transaksi</li>
			<?php       
      $act="";$show="";
      if(setMenu('member')!=''){           
        $show = 'd-none';                        
      }else{            
        if($isi=='member'){
          $act = "active"; 
          $show = "menu-open"; 
        }
      }              
      ?>
			<li class="nav-main-item">
				<a class="nav-main-link {{ $act }}" href="{{ route('member.index') }}">
					<i class="nav-main-link-icon si si-energy"></i>
					<span class="nav-main-link-name">Membership</span>
				</a>
			</li>
			<?php       
      $act="";$show="";
      if(setMenu('partnership')!=''){           
        $show = 'd-none';                        
      }else{            
        if($isi=='partnership'){
          $act = "active"; 
          $show = "menu-open"; 
        }
      }              
      ?>
			<li class="nav-main-item">
				<a class="nav-main-link {{ $act }}" href="{{ route('partnership.index') }}">
					<i class="nav-main-link-icon si si-energy"></i>
					<span class="nav-main-link-name">Partnership</span>
				</a>
			</li>
			<?php       
      $act="";$show="";
      if(setMenu('setoranPaket')!=''){           
        $show = 'd-none';                        
      }else{            
        if($isi=='setoranPaket'){
          $act = "active"; 
          $show = "menu-open"; 
        }
      }              
      ?>
			<li class="nav-main-item">
				<a class="nav-main-link {{ $act }}" href="{{ route('setoranPaket.index') }}">
					<i class="nav-main-link-icon si si-energy"></i>
					<span class="nav-main-link-name">Setoran Paket</span>
				</a>
			</li>
			<?php       
      $act="";$show="";
      if(setMenu('pengembalianSetoran')!=''){           
        $show = 'd-none';                        
      }else{            
        if($isi=='pengembalianSetoran'){
          $act = "active"; 
          $show = "menu-open"; 
        }
      }              
      ?>
			<li class="nav-main-item">
				<a class="nav-main-link {{ $act }}" href="{{ route('pengembalianSetoran.index') }}">
					<i class="nav-main-link-icon si si-energy"></i>
					<span class="nav-main-link-name">Pengembalian Setoran</span>
				</a>
			</li>
			<?php       
      $act="";$show="";
      if(setMenu('setoranManajemen')!=''){           
        $show = 'd-none';                        
      }else{            
        if($isi=='setoranManajemen'){
          $act = "active"; 
          $show = "menu-open"; 
        }
      }              
      ?>
			<li class="nav-main-item">
				<a class="nav-main-link {{ $act }}" href="{{ route('setoranManajemen.index') }}">
					<i class="nav-main-link-icon si si-energy"></i>
					<span class="nav-main-link-name">Setoran Manajemen</span>
				</a>
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
      if(setMenu('karyawan')!=''){           
        $show = 'd-none';                        
      }else{            
        if($isi=='karyawan'){
          $act = "active"; 
          $show = "menu-open"; 
        }
      }              
      ?>
			<li <?= setMenu('karyawan') ?> class="nav-main-item">				
				<a class="nav-main-link {{ $act }}" href="{{ route('karyawan.index') }}">
					<i class="nav-main-link-icon si si-user"></i>
					<span class="nav-main-link-name">Karyawan</span>
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

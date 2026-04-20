@role('manager')
<div class="main-sidebar">
    <aside id="sidebar-wrapper">
      <div class="sidebar-brand">
        <a href="{{ route('dashboard') }}">{{ $setting->nama_perusahaan}}</a>
      </div>
      <div class="sidebar-brand sidebar-brand-sm">
        <a href="{{ route('dashboard') }}">SW</a>
      </div>
      <ul class="sidebar-menu" >
          <li class="menu-header">Dashboard</li>
          <li class="{{ request()->is('dashboard') ? 'active' : '' }}"><a class="nav-link " href="{{ route('dashboard') }}"><i class="fas fa-fire"></i> <span>Dashboard</span></a></li>
          <li class="menu-header">Transaksi</li>
          <li class="nav-item dropdown  {{ request()->is('penjualan/jenis_pembayaran','penjualan/item','penjualan/kategori','penjualan/ringkasan') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="far fa-money-bill-alt"></i> <span>Penjualan</span></a>
            <ul class="dropdown-menu ">
              <li class=" {{ request()->is('penjualan/ringkasan') ? 'active' : '' }}"><a class="nav-link" href="{{ route('penjualan.ringkasan') }}">Ringkasan Penjualan</a></li>
              <li class=" {{ request()->is('penjualan/jenis_pembayaran') ? 'active' : '' }}"><a class="nav-link" href="{{ route('penjualan.jenis_pembayaran') }}">Jenis Pembayaran </a></li>
              <li class=" {{ request()->is('penjualan/kategori') ? 'active' : '' }}"><a class="nav-link" href="{{ route('penjualan.kategori') }}"> Kategori Penjualan </a></li>  
              <li class=" {{ request()->is('penjualan/item') ? 'active' : '' }}"><a class="nav-link" href="{{ route('penjualan.item') }}"> Item Penjualan </a></li> 
            </ul>
          </li>
          <li class="{{ request()->is('transaksiadmin') ? 'active' : '' }}"><a class="nav-link" href="{{ route('transaksiadmin.index') }}"><i class="far fa-credit-card"></i> <span>Transaksi</span></a></li>
          <li class="{{ request()->is('shift.index') ? 'active' : '' }}"><a class="nav-link" href="{{ route('shift.index') }}"><i class="far fa-address-card"></i> <span>Shift</span></a></li>
        </ul>
    </aside>
  </div>
  @endrole
@role('keuangan')
<div class="main-sidebar">
    <aside id="sidebar-wrapper">
      <div class="sidebar-brand">
        <a href="{{ route('dashboard') }}">{{ $setting->nama_perusahaan}}</a>
      </div>
      <div class="sidebar-brand sidebar-brand-sm">
        <a href="{{ route('dashboard') }}">SW</a>
      </div>
      <ul class="sidebar-menu" >
          <li class="menu-header">Dashboard</li>
          <li class="{{ request()->is('dashboard') ? 'active' : '' }}"><a class="nav-link " href="{{ route('dashboard') }}"><i class="fas fa-fire"></i> <span>Dashboard</span></a></li>
          <li class="menu-header">Transaksi</li>
          <li class="nav-item dropdown  {{ request()->is('penjualan/jenis_pembayaran','penjualan/item','penjualan/kategori','penjualan/ringkasan') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="far fa-money-bill-alt"></i> <span>Penjualan</span></a>
            <ul class="dropdown-menu ">
              <li class=" {{ request()->is('penjualan/ringkasan') ? 'active' : '' }}"><a class="nav-link" href="{{ route('penjualan.ringkasan') }}">Ringkasan Penjualan</a></li>
              <li class=" {{ request()->is('penjualan/jenis_pembayaran') ? 'active' : '' }}"><a class="nav-link" href="{{ route('penjualan.jenis_pembayaran') }}">Jenis Pembayaran </a></li>
              <li class=" {{ request()->is('penjualan/kategori') ? 'active' : '' }}"><a class="nav-link" href="{{ route('penjualan.kategori') }}"> Kategori Penjualan </a></li>  
              <li class=" {{ request()->is('penjualan/item') ? 'active' : '' }}"><a class="nav-link" href="{{ route('penjualan.item') }}"> Item Penjualan </a></li> 
            </ul>
          </li>
          <li class="{{ request()->is('transaksiadmin') ? 'active' : '' }}"><a class="nav-link" href="{{ route('transaksiadmin.index') }}"><i class="far fa-credit-card"></i> <span>Transaksi</span></a></li>
          <li class="{{ request()->is('shift.index') ? 'active' : '' }}"><a class="nav-link" href="{{ route('shift.index') }}"><i class="far fa-address-card"></i> <span>Shift</span></a></li>
        </ul>
    </aside>
  </div>
  @endrole
  
@role('operator')
<div class="main-sidebar">
    <aside id="sidebar-wrapper">
      <div class="sidebar-brand">
        <a href="{{ route('dashboard') }}">{{ $setting->nama_perusahaan}}</a>
      </div>
      <div class="sidebar-brand sidebar-brand-sm">
        <a href="{{ route('dashboard') }}">SW</a>
      </div>
      <ul class="sidebar-menu" >
          <li class="{{ request()->is('dashboard') ? 'active' : '' }}"><a class="nav-link " href="{{ route('dashboard') }}"><i class="fas fa-fire"></i> <span>Dashboard</span></a></li>
          <li class="{{ request()->is('wisata.index') ? 'active' : '' }}"><a class="nav-link" href="{{ route('wisata.index') }}"><i class="fas fa-columns"></i> <span>Objek Wisata</span></a></li>
          <li class="{{ request()->is('kategori.index') ? 'active' : '' }}"><a class="nav-link" href="{{ route('kategori.index') }}"><i class="fas fa-columns"></i> <span>Kategori</span></a></li>
          <li class="{{ request()->is('item') ? 'active' : '' }}"><a class="nav-link" href="{{ route('item.index') }}"><i class="fas fa-columns"></i> <span>Item</span></a></li>
          <li class="{{ request()->is('promo') ? 'active' : '' }}"><a class="nav-link" href="{{ route('promo.index') }}"><i class="fas fa-columns"></i> <span>Promo</span></a></li>
          <li class="{{ request()->is('bank.index') ? 'active' : '' }}"><a class="nav-link" href="{{ route('bank.index') }}"><i class="fas fa-columns"></i> <span>Bank</span></a></li>
          <li class="{{ request()->is('ewalet.index') ? 'active' : '' }}"><a class="nav-link" href="{{ route('ewalet.index') }}"><i class="fas fa-columns"></i> <span>E-Wallet</span></a></li>
          </ul>
    </aside>
  </div>
  @endrole
  @role('admin')
<div class="main-sidebar">
    <aside id="sidebar-wrapper">
      <div class="sidebar-brand">
        <a href="{{ route('dashboard') }}">{{ $setting->nama_perusahaan}}</a>
      </div>
      <div class="sidebar-brand sidebar-brand-sm">
        <a href="{{ route('dashboard') }}">SW</a>
      </div>
      <ul class="sidebar-menu" >
          <li class="menu-header">Dashboard</li>
          <li class="{{ request()->is('dashboard') ? 'active' : '' }}"><a class="nav-link " href="{{ route('dashboard') }}"><i class="fas fa-fire"></i> <span>Dashboard</span></a></li>
          <li class="menu-header">Data Master</li>
          <li class="nav-item dropdown {{ request()->is('wisata.index','kategori.index','item','bank.index','ewalet.index','promo') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown  "  data-toggle="dropdown"><i class="fas fa-columns"></i> <span>Data Master</span></a>
            <ul class="dropdown-menu" >
              <li class="{{ request()->is('wisata.index') ? 'active' : '' }}"><a class="nav-link "  href="{{ route('wisata.index') }}" >Objek Wisata</a></li>
              <li class="{{ request()->is('kategori.index') ? 'active' : '' }}"><a class="nav-link " href="{{ route('kategori.index') }}">Kategori</a></li>
              <li class="{{ request()->is('item') ? 'active' : '' }}"><a class="nav-link " href="{{ route('item.index') }}">Item </a></li>
              <li class="{{ request()->is('promo') ? 'active' : '' }}"><a class="nav-link" href="{{ route('promo.index') }}">Promo </a></li>
              <li class="{{ request()->is('bank.index') ? 'active' : '' }}"><a class="nav-link " href="{{ route('bank.index') }}">Bank </a></li>
              <li class="{{ request()->is('ewalet.index') ? 'active' : '' }}"><a class="nav-link " href="{{ route('ewalet.index') }}">E-Wallet </a></li>
            </ul>
          </li>
          <li class="menu-header">Transaksi</li>
          <li class="nav-item dropdown  {{ request()->is('penjualan/jenis_pembayaran','penjualan/item','penjualan/kategori','penjualan/ringkasan') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="far fa-money-bill-alt"></i> <span>Penjualan</span></a>
            <ul class="dropdown-menu ">
              <li class=" {{ request()->is('penjualan/ringkasan') ? 'active' : '' }}"><a class="nav-link" href="{{ route('penjualan.ringkasan') }}">Ringkasan Penjualan</a></li>
              <li class=" {{ request()->is('penjualan/jenis_pembayaran') ? 'active' : '' }}"><a class="nav-link" href="{{ route('penjualan.jenis_pembayaran') }}">Jenis Pembayaran </a></li>
              <li class=" {{ request()->is('penjualan/kategori') ? 'active' : '' }}"><a class="nav-link" href="{{ route('penjualan.kategori') }}"> Kategori Penjualan </a></li>  
              <li class=" {{ request()->is('penjualan/item') ? 'active' : '' }}"><a class="nav-link" href="{{ route('penjualan.item') }}"> Item Penjualan </a></li> 
            </ul>
          </li>
          <li class="{{ request()->is('transaksiadmin') ? 'active' : '' }}"><a class="nav-link" href="{{ route('transaksiadmin.index') }}"><i class="far fa-credit-card"></i> <span>Transaksi</span></a></li>
          <li class="{{ request()->is('shift.index') ? 'active' : '' }}"><a class="nav-link" href="{{ route('shift.index') }}"><i class="far fa-address-card"></i> <span>Shift</span></a></li>
         
           <li class="menu-header">Asset</li>
          <li class="nav-item dropdown {{ request()->is('asset.index','kategori_asset.index','merk.index','departemen.index','userasset.index') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown  "  data-toggle="dropdown"><i class="fas fa-columns"></i> <span>Data Master Asset</span></a>
            <ul class="dropdown-menu" >
              <li class="{{ request()->is('kategori_asset.index') ? 'active' : '' }}"><a class="nav-link " href="{{ route('kategori_asset.index') }}">Kategori</a></li>
              <!--<li class="{{ request()->is('asset.index') ? 'active' : '' }}"><a class="nav-link " href="{{ route('asset.index') }}">Nama Asset</a></li>  -->
              <li class="{{ request()->is('merk.index') ? 'active' : '' }}"><a class="nav-link " href="{{ route('merk.index') }}">Merk/Type </a></li>
              <li class="{{ request()->is('departemen.index') ? 'active' : '' }}"><a class="nav-link " href="{{ route('departemen.index') }}">Departemen</a></li>
              <li class="{{ request()->is('userasset') ? 'active' : '' }}"><a class="nav-link " href="{{ route('userasset.index') }}">User</a></li>
            </ul>
          </li>
          <li class=" {{ request()->is('dataasset.index') ? 'active' : '' }}"><a class="nav-link" href="{{ route('dataasset.index') }}"><i class="far fa-file-alt"></i><span>Data Asset</span></a></li>
         
          <li class="menu-header">Setting</li>
          <li class="{{ request()->is('setting.index') ? 'active' : '' }}"><a class="nav-link" href="{{ route('setting.index') }}"><i class="fas fa-cog"></i></i> <span>Kelola Web</span></a></li>
          <li class=" {{ request()->is('user') ? 'active' : '' }}"><a class="nav-link" href="{{ route('user') }}"><i class="far fa-user"></i><span>User Pengguna</span></a></li>
        </ul>
    </aside>
  </div>
  @endrole

  @role('kasir')
<div class="main-sidebar">
    <aside id="sidebar-wrapper">
      <div class="sidebar-brand">
        <a href="{{ route('dashboard') }}">SIWINDU</a>
      </div>
      <div class="sidebar-brand sidebar-brand-sm">
        <a href="{{ route('dashboard') }}">SW</a>
      </div>
      <ul class="sidebar-menu" >
          <li class="menu-header">Kasir</li>
          <li class="{{ request()->is('dashboard') ? 'active' : '' }}"><a class="nav-link " href="{{ route('dashboard') }}"><i class="fas fa-fire"></i> <span>Dashboard</span></a></li>
          @if(Auth::user()->status=='1')
          <li class="{{ request()->is('transaksi') ? 'active' : '' }}"><a class="nav-link " href="{{ route('transaksi.baru') }}"><i class="far fa-money-bill-alt"></i> <span>Transaksi Baru</span></a></li>
          <li class="{{ request()->is('belumselesai.index') ? 'active' : '' }}"><a class="nav-link " href="{{ route('belumselesai.index') }}"><i class="far fa-money-bill-alt"></i> <span>Transaksi Belum Selesai</span></a></li>
          @endif
          <li class="{{ request()->is('aktivitas.index') ? 'active' : '' }}"><a class="nav-link " href="{{ route('aktivitas.index') }}"><i class="far fa-file-alt"></i> <span>Aktivitas</span></a></li>
          <li class="{{ request()->is('shiftkasir.index') ? 'active' : '' }}"><a class="nav-link " href="{{ route('shiftkasir.index') }}"><i class="fas fa-ellipsis-h"></i> <span>Shift</span></a></li>
      </ul>
      
    </aside>
  </div>
  @endrole



<?php
use App\Models\Transaksi;
$JumlahNotifikasi = Transaksi::where('status','Belum')->where('wisata',Auth::user()->wisata)->count();
$StatusTransaksi = Transaksi::where('status','Belum')->get();
$role = Auth::user();
foreach ($role->roles as $roley ) {
 $roles = $roley->display_name;
}
?>

<div class="navbar-bg"></div>
      <nav class="navbar navbar-expand-lg main-navbar">
        <form class="form-inline mr-auto">
          <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
          </ul>
        </form>
        <ul class="navbar-nav navbar-right">
          @if(Auth::user()->status=='1')
          <li class="dropdown dropdown-list-toggle">
            <a href="#" data-toggle="dropdown" class="nav-link notification-toggle nav-link-lg">
              <i class="far fa-bell "></i>
              @role('admin')
              <?php
              if(!empty($JumlahNotifikasis)){
                echo '<span class="badge badge-danger">'.$JumlahNotifikasis.'</span>';
              }
              ?>
            </a>
            <div class="dropdown-menu dropdown-list dropdown-menu-right">
             <?php
              if(!empty($JumlahNotifikasis)){
                echo '<div class="dropdown-header">Notifikasi Transaksi';
                echo '<div class="float-right">';
                echo '<span class="badge badge-danger">'.$JumlahNotifikasis.'</span></div></div>';
              }else{
                echo '<div class="dropdown-header">Tidak Ada Notifikasi</div>';
              }
              ?>
            </div>
          </li>
          @endrole
          <!--   @role('kasir')-->
          <!--    ?php-->
          <!--    if(!empty($JumlahNotifikasi)){-->
          <!--      echo '<span class="badge badge-danger">'.$JumlahNotifikasi.'</span>';-->
          <!--    }-->
          <!--    ?>-->
          <!--  </a>-->
          <!--  <div class="dropdown-menu dropdown-list dropdown-menu-right">-->
          <!--  ?php-->
          <!--    if(!empty($JumlahNotifikasi)){-->
          <!--      echo '<div class="dropdown-header">Notifikasi Transaksi';-->
          <!--      echo '<div class="float-right">';-->
          <!--      echo '<span class="badge badge-danger">'.$JumlahNotifikasi.'</span></div></div>';-->
          <!--    }-->
          <!--    else{-->
          <!--      echo '<div class="dropdown-header">Tidak Ada Notifikasi</div>';-->
          <!--    }-->
          <!--    ?>-->

          <!--   ?php-->
          <!--  if(!empty($JumlahNotifikasi)){?>-->
          <!--      <div class="dropdown-list-icons">-->
          <!--     @foreach($StatusTransaksi as $status)-->
          <!--      @if ($status->wisata == Auth::user()->wisata)-->
          <!--      <a href="{{route('page.transaksibelum', $status->id_transaksi)}}" class="dropdown-item dropdown-item-unread">-->
          <!--      <div class="dropdown-item-icon bg-danger text-white">-->
          <!--      <i class="fas fa-money-bill-wave"></i></div>-->
          <!--      <div class="dropdown-item-desc">Transaksi Belum Selesai-->
          <!--      <div class="time text-primary">Kode Transaksi :{{$status->id_transaksi}}</div></div>-->
          <!--      @endif-->
          <!--      @endforeach-->
          <!--      </a>-->
          <!--      </div>-->
          <!--      ?php } ?>-->
          <!--  </div>-->
          <!--</li>-->
          <!--@endif-->
          <!--@endrole-->
          <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
            <img alt="image" src="{{ asset('template') }}/assets/img/avatar/avatar-1.png" class="rounded-circle mr-1">
            <div class="d-sm-none d-lg-inline-block">{{ Auth::user()->name }}</div></a>
            <div class="dropdown-menu dropdown-menu-right">
              <div class="dropdown-title">{{ $roles }}</div>
              <a href="{{ route('profile.index') }}" class="dropdown-item has-icon">
                <i class="far fa-user"></i> Profile
              </a>
              @role('admin')
              <a href="{{ route('setting.index') }}" class="dropdown-item has-icon">
                <i class="fas fa-cog"></i> Settings
              </a>
              @endrole
              @if(Auth::user()->status=='0')
              <div class="dropdown-divider"></div>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
              <a href="route('logout')" class="dropdown-item has-icon text-danger" onclick="event.preventDefault();
              this.closest('form').submit();">
                <i class="fas fa-sign-out-alt"></i> Logout
              </a>
             </form>
            </div>
            @else
              <a href="{{ route('shiftsaatini') }}" class="dropdown-item has-icon">
                <i class="fas fa-ellipsis-h"></i> Akhiri Shift
              </a>
            @endif
          </li>
        </ul>
      </nav>
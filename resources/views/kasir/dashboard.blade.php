 <!-- Main Content -->
 @extends('layouts.app')
 @section('tittle', 'Dashboard')
 @section('conten')
      <!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Dashboard</h1>
            <div class="section-header-breadcrumb">
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12 ">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="box">
                                        <div class="box-body text-center">
                                            <!--<h1>Selamat Datang</h1>-->
                                            <h5>Silahkan Masukan Transaksi</h5>
                                            <br><br>
                                            @if(Auth::user()->status=='1')
                                            <a href="{{ route('transaksi.baru') }}" class="btn btn-success btn-lg">Transaksi Baru</a>
                                            @else
                                            <a href="{{ route('shiftbaru') }}" class="btn btn-success btn-lg">Silahkan Mulai Shift </a>
                                            @endif
                                            <br><br><br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
  @endsection

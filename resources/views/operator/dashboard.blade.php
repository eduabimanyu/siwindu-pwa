@extends('layouts.app')
@section('tittle', 'Dashboard')
@section('dashboard', 'active')
@section('main', 'show')
@section('conten')

  <!-- Main Content -->
  <div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Dashboard</h1>
       </div>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-12" >
              <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                    <i class="far fa-file-alt"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header ">
                    <h4>Total Wisata</h4>
                  </div>
                  <div class="card-body " >
                    <span>{{ $wisata }}</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-danger">
                    <i class="far fa-file-alt"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Total Item</h4>
                  </div>
                  <div class="card-body">
                    <span id="pendapatan">{{ $item }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
    </section>
    <div class="row">
      <div class="col-lg-6 col-md-6 col-sm-12 col-12">
        <div class="card card-statistic-1">
        <div id="container"></div>
        </div>
      </div>
       <div class="col-lg-6 col-md-6 col-sm-12 col-12">
        <div class="card card-statistic-1">
        <div id="containersum"></div>
        </div>
      </div>
    </div>
</div>



@push('js')
<script src="{{ asset('template') }}/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('template') }}/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script src="https://code.highcharts.com/highcharts.js"></script>
@endpush
  @endsection

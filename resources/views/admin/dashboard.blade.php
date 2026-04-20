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
            <div class="form-group col-md-4">
             <select class="form-control filter" name="filer_wisata" id="filter_wisata" required >
                  @foreach($wisata as $wisatab)
                 <option value="{{ $wisatab->id }}"> {{ $wisatab->nama_wisata }}</option>
                 @endforeach
                 <option value="{{ $wisata=0 }}"> Semua</option>
             </select>
           </div>
           <div class="form-group col-md-3">
            <input type="text" id="filter_tanggal" class="form-control filter" readonly >
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-12" >
              <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                  <i class="far fa-user"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header ">
                    <h4>Transaksi</h4>
                  </div>
                  <div class="card-body " >
                    <span id="transaksi"></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-danger">
                  <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Total Pendapatan</h4>
                  </div>
                  <div class="card-body">Rp.
                    <span id="pendapatan"></span>,-
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
<script>

// transaksi();
let filter_wisata = $('#filter_wisata').val()
var d = new Date();

      $(document).ready(function () {
            tampiltransaksi()

              })
              function tampiltransaksi(start_date= d.getFullYear() + "-" + (d.getMonth()+1) + "-" + d.getDate(), end_date= d.getFullYear() + "-" + (d.getMonth()+1) + "-" + d.getDate() ) {
                $("#transaksi").html('');
                      $.ajax({
                          url : "{{route('transaksi.count')}}",
                          data: {filter_wisata, start_date, end_date},
                          success: function(data){
                          $("#transaksi").html(data);
                           }
                          });
                 $(".filter").on('change',function(ranges){
                  $("#transaksi").html('');
                      $.ajax({
                          url : "{{route('transaksi.count')}}",
                          data: {filter_wisata, start_date, end_date},
                          success: function(data){
                            $("#transaksi").html(data);
                          }
                          });
                        })

                }


           $(document).ready(function () {
            tampilpendapatan()

              })
            function tampilpendapatan(start_date= d.getFullYear() + "-" + (d.getMonth()+1) + "-" + d.getDate(), end_date= d.getFullYear() + "-" + (d.getMonth()+1) + "-" + d.getDate() ) {
                $("#pendapatan").html('');
                      $.ajax({
                          url : "{{route('transaksi.pendapatan')}}",
                          data: {filter_wisata, start_date, end_date},
                          success: function(data){
                            $("#pendapatan").html(data);
                          }
                          });
            $(".filter").on('change',function(e){
            $("#pendapatan").html('');
            $.ajax({
                    url : "{{route('transaksi.pendapatan')}}",
                    data: {filter_wisata, start_date, end_date},
                    success: function(data){
                      $("#pendapatan").html(data);
                    }
                    });
                  })
               }

           $(document).ready(function () {
            tampilfilter()
              })
              function tampilfilter() {
                $('#filter_tanggal').daterangepicker({
                   locale: {
                        format: 'DD/MM/YYYY'
                        },
                     ranges:{
                          'Hari Ini' : [moment().subtract(0, 'days'), moment().subtract(0, 'days')],
                          'Kemarin' : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                          '7 Hari Sebelumnya' : [moment().subtract(6, 'days'), moment()],
                          '30 Hari Sebelumnya' : [moment().subtract(29, 'days'), moment()],
                          'Bulan ini' : [moment().startOf('month'), moment().endOf('month')],
                          'Bulan Lalu' : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                      },
                      format : 'YYYY-MM-DD'
                  }, function(start, end){
                      $("#transaksi").html();
                      tampiltransaksi(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
                      $("#pendapatan").html();
                      tampilpendapatan(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
                    },

                 );
              }

      $(".filter").on('change',function(e){
          filter_wisata = $('#filter_wisata').val()

      })

   var transaksi =  <?php echo json_encode($transaksi) ?>;
   
   Highcharts.chart('container', {
       title: {
           text: 'Jumlah Transaksi'
       },
       subtitle: {
           text: 'Tahun 2025'
       },
        xAxis: {
           categories: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli','Agustus','Sepetember','Oktober','November','Desember']
       },
       yAxis: {
           title: {
               text: 'Jumlah Transaksi'
           }
       },
       legend: {
           layout: 'vertical',
           align: 'right',
           verticalAlign: 'middle'
       },
       plotOptions: {
           series: {
               allowPointSelect: true
           }
       },
       series: [{
           name: 'Transaksi',
           data: transaksi
       }],
       responsive: {
           rules: [{
               condition: {
                   maxWidth: 500
               },
               chartOptions: {
                   legend: {
                       layout: 'horizontal',
                       align: 'center',
                       verticalAlign: 'bottom'
                   }
               }
           }]
       }
});

var transaksisum =  <?php echo json_encode($transaksisum,JSON_NUMERIC_CHECK) ?>;
   
   Highcharts.chart('containersum', {
       title: {
           text: 'Pendapatan'
       },
       subtitle: {
           text: 'Tahun 2025'
       },
        xAxis: {
           categories: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli','Agustus','Sepetember','Oktober','November','Desember']
       },
       yAxis: {
           title: {
               text: 'Jumlah Pendapatan'
           }
       },
       legend: {
           layout: 'vertical',
           align: 'right',
           verticalAlign: 'middle'
       },
       plotOptions: {
           series: {
               allowPointSelect: true
           }
       },
       series: [{
           name: 'Pendapatan',
           data: transaksisum
       }],
       responsive: {
           rules: [{
               condition: {
                   maxWidth: 500
               },
               chartOptions: {
                   legend: {
                       layout: 'horizontal',
                       align: 'center',
                       verticalAlign: 'bottom'
                   }
               }
           }]
       }
});
</script>
@endpush
  @endsection

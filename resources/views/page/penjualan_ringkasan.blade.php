 <!-- Main Content -->
 @extends('layouts.app')
 @section('tittle', 'Ringkasan Penjualan')

 @section('conten')
      <!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Ringkasan Penjualan</h1>
          <div class="section-header-breadcrumb">
          <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
          <div class="breadcrumb-item ">Ringkasan Penjualan</div>
        </div>
        </div>
        <div class="row">
        <div class="form-group col-md-4">
          <select class="form-control filter" name="filer_wisata" id="filter_wisata" required >
            @foreach($wisata as $wisatab)
              <option value="{{ $wisatab->id }}"> {{ $wisatab->nama_wisata }}</option>
              @endforeach
              <option value="{{ $wisatab=0 }}"> Semua</option>
          </select>
        </div>
          <div class="form-group col-md-3">
          <input type="text" id="filter_tanggal" class="form-control filter" readonly >
        </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-12">
                  <div class="card card-primary">
                    <div class="card-header">
                      <h4>Pendapatan</h4>
                      <div class="card-header-action">
                        <h6 id="pendapatan"></h6>
                      </div>
                    </div>
                </div>
            </div>
    </section>
</div>

@push('js')
<script src="{{ asset('template') }}/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('template') }}/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script src="{{ asset('template') }}/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="{{ asset('template') }}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('template') }}/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{ asset('template') }}/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.5/dist/sweetalert2.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script >
let filter_wisata = $('#filter_wisata').val()
var d = new Date();
              $(document).ready(function () {
               tampilpendapatan()
              })
                function tampilpendapatan(start_date= d.getFullYear() + "-" + (d.getMonth()+1) + "-" + d.getDate(), end_date= d.getFullYear() + "-" + (d.getMonth()+1) + "-" + d.getDate() ) {
                    $("#pendapatan").html('');
                          $.ajax({
                              url : "{{route('penjualan.pendapatan')}}",
                              data: {filter_wisata, start_date, end_date},
                              success: function(data){
                                $("#pendapatan").html(data);
                              }
                              });

                    $(".filter").on('change',function(e){
                      $("#pendapatan").html('');
                      $.ajax({
                              url : "{{route('penjualan.pendapatan')}}",
                              data: {filter_wisata,start_date, end_date},
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
                      $("#pendapatan").html();
                      tampilpendapatan(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
                    },

                 );
              }

              $(".filter").on('change',function(e){
                filter_wisata = $('#filter_wisata').val()

            })

</script>
@endpush
  @endsection

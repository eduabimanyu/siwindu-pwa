 <!-- Main Content -->
 @extends('layouts.app')
 @section('tittle', 'Item Penjualan')

 @section('conten')
      <!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Penjualan Berdasarkan Item</h1>
          <div class="section-header-breadcrumb">
          <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
          <div class="breadcrumb-item ">Penjualan Item</div>
        </div>
        </div>
        <div class="row">
            <div class="form-group col-md-4">
              <select class="form-control filter" name="filer_wisata" id="filter_wisata" required >
                <option value="{{ $wisatab=0 }}"> Semua</option>
                @foreach($wisata as $wisatab)
                  <option value="{{ $wisatab->id }}"> {{ $wisatab->nama_wisata }}</option>
                  @endforeach
              </select>
            </div>
            <div class="form-group col-md-3">
              <input type="text" id="filter_tanggal" class="form-control filter" readonly >
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12 ">
                    <div class="card" id="data">
                        <div class="card-body">
                                <table class="table table-striped" id="tabel">
                                    <thead>
                                        <tr>
                                            <th scope="col">Item</th>
                                            <th scope="col">Terjual</th>
                                            <th scope="col">Pndapatan</th>
                                            <th scope="col">Kategori</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Total</th>
                                            <th id="item"></th>
                                            <th id="pendapatan"></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                        </div>
                        <div id="item"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@push('js')
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.5/dist/sweetalert2.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script >

let filter_wisata = $('#filter_wisata').val()
var d = new Date();
        $(document).ready(function () {
            item()
        })
        function item(start_date= d.getFullYear() + "-" + (d.getMonth()+1) + "-" + d.getDate(), end_date= d.getFullYear() + "-" + (d.getMonth()+1) + "-" + d.getDate()  ) {

            $('#tabel').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax : {
                    url : "{{route('penjualan.dataitem')}}",
                    data:  function(d){
                        d.filter_wisata = filter_wisata;
                        d.start_date = start_date;
                        d.end_date = end_date;
                        return d
                    }
                },
                columns:[
                        {data: 'nama_item', name: 'nama_item'},
                        {data: 'count', name: 'count'},
                        {data: 'sum', name: 'sum'},
                        {data: 'kategoris', name: 'kategoris'},

                    ]
            })
                $("#item").html('');
                      $.ajax({
                          url : "{{route('items.count')}}",
                          data: {filter_wisata, start_date, end_date},
                          success: function(data){
                          $("#item").html(data);
                          }
                          });

                $(".filter").on('change',function(){
                  filter_wisata = $('#filter_wisata').val()
                  $("#item").html('');
                      $.ajax({
                          url : "{{route('items.count')}}",
                          data: {filter_wisata, start_date, end_date},
                          success: function(data){
                            $("#item").html(data);
                          }
                          });
                    })

                    $("#pendapatan").html('');
                          $.ajax({
                              url : "{{route('penjualan.pendapatan')}}",
                              data: {filter_wisata, start_date, end_date},
                              success: function(data){
                                $("#pendapatan").html(data);
                              }
                              });

                    $(".filter").on('change',function(){
                      $("#pendapatan").html('');
                      $.ajax({
                              url : "{{route('penjualan.pendapatan')}}",
                              data: {filter_wisata, start_date, end_date},
                              success: function(data){
                                $("#pendapatan").html(data);
                              }
                              });
                      })
            }
            $(".filter").on('change',function(e){
                filter_wisata = $('#filter_wisata').val()
                $('#tabel').DataTable().ajax.reload()
            })

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
                      $('#tabel').DataTable().destroy();
                      item(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
                    },

                 );
              }

</script>
@endpush
  @endsection

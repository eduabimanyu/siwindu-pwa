 <!-- Main Content -->
 @extends('layouts.app')
 @section('tittle', 'Jenis Pembayaran')

 @section('conten')
      <!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Jenis Pembayaran</h1>
          <div class="section-header-breadcrumb">
          <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
          <div class="breadcrumb-item ">Jenis Pembayaran</div>
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
                            <div class="form-group col-md-3">
                              </div>
                                <table class="table table-sm">
                                    <thead>
                                      <tr>
                                        <th scope="col">Metode Pembayaran</th>
                                        <th class="text-center" scope="col">Jumlah Transaksi</th>
                                        <th class="text-right" scope="col">Total Pendapatan</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                          <td><a href="{{ route('penjualan.tunai') }}" id="detailtunai"><b>Tunai</b></a></td>
                                          <td class="text-center"><b><span id="transaksi_tunai"></b></td>
                                          <td class="text-right"><b><span id="pendapatan_tunai"></span></td>
                                        </tr>
                                        <tr>
                                          <td><a href="{{ route('penjualan.qris') }}"><b>QRIS</b></a></td>
                                          <td class="text-center"><b><span id="transaksi_qris"></span></td>
                                          <td class="text-right"><b><span id="pendapatan_qris"></span></td>
                                        </tr>
                                        <tr>
                                          <td><a href="{{ route('penjualan.bank') }}"><b>Transfer Bank</b></a></td>
                                          <td class="text-center"><b><span id="transaksi_bank"></span></td>
                                          <td class="text-right"><b><span id="pendapatan_bank"></span></td>
                                        </tr>
                                        <tr>
                                          <td><a href="{{ route('penjualan.ewalet') }}" ><b>Transfer Ewallet</b></a></td>
                                          <td class="text-center"><b><span id="transaksi_ewalet"></span></td>
                                          <td class="text-right"><b><span id="pendapatan_ewalet"></span></td>
                                        </tr>
                                      </tbody>
                                      <tr>
                                        <td><b>Total</b></td>
                                        <td class="text-center"><b><span id="transaksi"></span></b></td>
                                        <td class="text-right"><b><span id="pendapatan"></span></b></td>
                                      </tr>
                                  </table>
                        </div>
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
                          tampiltransaksi()
                            })

              function tampiltransaksi(start_date= d.getFullYear() + "-" + (d.getMonth()+1) + "-" + d.getDate(), end_date= d.getFullYear() + "-" + (d.getMonth()+1) + "-" + d.getDate() ) {
                $("#transaksi").html('');
                      $.ajax({
                          url : "{{route('penjualan.count')}}",
                          data: {filter_wisata, start_date, end_date},
                          success: function(data){
                          $("#transaksi").html(data);
                          }
                          });

                $(".filter").on('change',function(e){
                  filter_wisata = $('#filter_wisata').val()
                  $("#transaksi").html('');
                      $.ajax({
                          url : "{{route('penjualan.count')}}",
                          data: {filter_wisata, start_date, end_date},
                          success: function(data){
                            $("#transaksi").html(data);
                          }
                          });
                    })
                }

              $(document).ready(function () {
                          tampiltransaksitunai()
                            })

              function tampiltransaksitunai(start_date= d.getFullYear() + "-" + (d.getMonth()+1) + "-" + d.getDate(), end_date= d.getFullYear() + "-" + (d.getMonth()+1) + "-" + d.getDate() ) {
                $("#transaksi_tunai").html('');
                      $.ajax({
                          url : "{{route('penjualan.transaksi_tunai')}}",
                          data: {filter_wisata, start_date, end_date},
                          success: function(data){
                          $("#transaksi_tunai").html(data);
                          }
                          });

                $(".filter").on('change',function(e){
                  filter_wisata = $('#filter_wisata').val()
                  $("#transaksi_tunai").html('');
                      $.ajax({
                          url : "{{route('penjualan.transaksi_tunai')}}",
                          data: {filter_wisata, start_date, end_date},
                          success: function(data){
                            $("#transaksi_tunai").html(data);
                          }
                          });
                    })
                }
             $(document).ready(function () {
                          tampiltransaksiqris()
                            })

              function tampiltransaksiqris(start_date= d.getFullYear() + "-" + (d.getMonth()+1) + "-" + d.getDate(), end_date= d.getFullYear() + "-" + (d.getMonth()+1) + "-" + d.getDate() ) {
                $("#transaksi_qris").html('');
                      $.ajax({
                          url : "{{route('penjualan.transaksi_qris')}}",
                          data: {filter_wisata, start_date, end_date},
                          success: function(data){
                          $("#transaksi_qris").html(data);
                          }
                          });

                $(".filter").on('change',function(e){

                  $("#transaksi_qris").html('');
                      $.ajax({
                          url : "{{route('penjualan.transaksi_qris')}}",
                          data: {filter_wisata, start_date, end_date},
                          success: function(data){
                            $("#transaksi_qris").html(data);
                          }
                          });
                    })
                }
                
              $(document).ready(function () {
                          tampiltransaksibank()
                            })

              function tampiltransaksibank(start_date= d.getFullYear() + "-" + (d.getMonth()+1) + "-" + d.getDate(), end_date= d.getFullYear() + "-" + (d.getMonth()+1) + "-" + d.getDate() ) {
                $("#transaksi_bank").html('');
                      $.ajax({
                          url : "{{route('penjualan.transaksi_bank')}}",
                          data: {filter_wisata, start_date, end_date},
                          success: function(data){
                          $("#transaksi_bank").html(data);
                          }
                          });

                $(".filter").on('change',function(e){

                  $("#transaksi_bank").html('');
                      $.ajax({
                          url : "{{route('penjualan.transaksi_bank')}}",
                          data: {filter_wisata, start_date, end_date},
                          success: function(data){
                            $("#transaksi_bank").html(data);
                          }
                          });
                    })
                }

              $(document).ready(function () {
                          tampiltransaksiewalet()
                            })

              function tampiltransaksiewalet(start_date= d.getFullYear() + "-" + (d.getMonth()+1) + "-" + d.getDate(), end_date= d.getFullYear() + "-" + (d.getMonth()+1) + "-" + d.getDate() ) {
                $("#transaksi_ewalet").html('');
                      $.ajax({
                          url : "{{route('penjualan.transaksi_ewalet')}}",
                          data: {filter_wisata, start_date, end_date},
                          success: function(data){
                          $("#transaksi_ewalet").html(data);
                          }
                          });

                $(".filter").on('change',function(e){
                  $("#transaksi_ewalet").html('');
                      $.ajax({
                          url : "{{route('penjualan.transaksi_ewalet')}}",
                          data: {filter_wisata, start_date, end_date},
                          success: function(data){
                            $("#transaksi_ewalet").html(data);
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
                              data: {filter_wisata, start_date, end_date},
                              success: function(data){
                                $("#pendapatan").html(data);
                              }
                              });
                      })
                    }

              $(document).ready(function () {
                tampilpendapatan_tunai()

                  })

                  function tampilpendapatan_tunai(start_date= d.getFullYear() + "-" + (d.getMonth()+1) + "-" + d.getDate(), end_date= d.getFullYear() + "-" + (d.getMonth()+1) + "-" + d.getDate()  ) {
                    $("#pendapatan_tunai").html('');
                          $.ajax({
                              url : "{{route('penjualan.pendapatan_tunai')}}",
                              data: {filter_wisata, start_date, end_date},
                              success: function(data){
                                $("#pendapatan_tunai").html(data);
                              }
                              });

                    $(".filter").on('change',function(e){
                      $("#pendapatan_tunai").html('');
                      $.ajax({
                              url : "{{route('penjualan.pendapatan_tunai')}}",
                              data: {filter_wisata, start_date, end_date},
                              success: function(data){
                                $("#pendapatan_tunai").html(data);
                              }
                              });
                      })
                    }

                $(document).ready(function () {
                tampilpendapatan_qris()

                  })

                  function tampilpendapatan_qris(start_date= d.getFullYear() + "-" + (d.getMonth()+1) + "-" + d.getDate(), end_date= d.getFullYear() + "-" + (d.getMonth()+1) + "-" + d.getDate() ) {
                    $("#pendapatan_qris").html('');
                          $.ajax({
                              url : "{{route('penjualan.pendapatan_qris')}}",
                              data: {filter_wisata, start_date, end_date},
                              success: function(data){
                                $("#pendapatan_qris").html(data);
                              }
                              });

                    $(".filter").on('change',function(e){
                      $("#pendapatan_qris").html('');
                      $.ajax({
                              url : "{{route('penjualan.pendapatan_qris')}}",
                              data: {filter_wisata, start_date, end_date},
                              success: function(data){
                                $("#pendapatan_qris").html(data);
                              }
                              });
                      })
                    }

              $(document).ready(function () {
                tampilpendapatan_bank()

                  })

                  function tampilpendapatan_bank(start_date= d.getFullYear() + "-" + (d.getMonth()+1) + "-" + d.getDate(), end_date= d.getFullYear() + "-" + (d.getMonth()+1) + "-" + d.getDate() ) {
                    $("#pendapatan_bank").html('');
                          $.ajax({
                              url : "{{route('penjualan.pendapatan_bank')}}",
                              data: {filter_wisata, start_date, end_date},
                              success: function(data){
                                $("#pendapatan_bank").html(data);
                              }
                              });

                    $(".filter").on('change',function(e){
                      $("#pendapatan_bank").html('');
                      $.ajax({
                              url : "{{route('penjualan.pendapatan_bank')}}",
                              data: {filter_wisata, start_date, end_date},
                              success: function(data){
                                $("#pendapatan_bank").html(data);
                              }
                              });
                      })
                    }

              $(document).ready(function () {
               tampilpendapatan_ewalet()

              })
              function tampilpendapatan_ewalet(start_date= d.getFullYear() + "-" + (d.getMonth()+1) + "-" + d.getDate(), end_date= d.getFullYear() + "-" + (d.getMonth()+1) + "-" + d.getDate() ) {
                $("#pendapatan_ewalet").html('');
                      $.ajax({
                          url : "{{route('penjualan.pendapatan_ewalet')}}",
                          data: {filter_wisata, start_date, end_date},
                          success: function(data){
                            $("#pendapatan_ewalet").html(data);
                          }
                          });

                $(".filter").on('change',function(e){
                  $("#pendapatan_ewalet").html('');
                  $.ajax({
                          url : "{{route('penjualan.pendapatan_ewalet')}}",
                          data: {filter_wisata, start_date, end_date},
                          success: function(data){
                            $("#pendapatan_ewalet").html(data);
                          }
                          });
                   })
                }
              $(".filter").on('change',function(e){
                filter_wisata = $('#filter_wisata').val()
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
                      $("#transaksi").html();
                      tampiltransaksi(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
                      $("#transaksi_tunai").html();
                      tampiltransaksitunai(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
                      $("#transaksi_bank").html();
                      tampiltransaksiqris(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
                      $("#transaksi_qrs").html();
                      tampiltransaksibank(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
                      $("#transaksi_ewalet").html();
                      tampiltransaksiewalet(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
                      $("#pendapatan").html();
                      tampilpendapatan(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
                      $("#pendapatan_tunai").html();
                      tampilpendapatan_tunai(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
                      $("#pendapatan_qris").html();
                      tampilpendapatan_qris(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
                      $("#pendapatan_bank").html();
                      tampilpendapatan_bank(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
                      $("#pendapatan_ewalet").html();
                      tampilpendapatan_ewalet(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
                    },

                 );
              }

</script>
@endpush
  @endsection

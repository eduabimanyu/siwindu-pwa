 <!-- Main Content -->
 @extends('layouts.app')
 @section('tittle', 'Transaksi')

 @section('conten')
      <!-- Main Content -->
<div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>Transaksi</h1>
        <div class="section-header-breadcrumb">
          <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
          <div class="breadcrumb-item ">transaksi</div>
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
        {{-- <div class="form-group col-md-4">
        <div class="buttons">
          <a href="-" class="btn btn-icon icon-left btn-warning"><i class="fas fa-file-excel"></i></i> Import Excel</a>
          <a href="{{ route('export.excel') }}" class="btn btn-icon icon-left btn-success"><i class="fas fa-file-excel"></i></i> Export Excel</a>
        </div> --}}
        {{-- </div> --}}
            <div class="col-lg-6 col-md-6 col-sm-12 col-12" >
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
       <div class="section-body">
            <div class="row">
                <div class="col-12 ">
                    <div class="card">
                        <div class="card-body">
                                <table class="table table-striped" id="tabel">
                                    <thead>
                                        <tr>
                                            <th scope="col">Wisata</th>
                                            <th scope="col">Tanggal | Jam</th>
                                            <th scope="col">Total Item</th>
                                            <th scope="col">Total Pendapatan</th>
                                            <th scope="col">Jenis_pembayaran</th>
                                            <th scope="col">Kasir</th>
                                            <th scope="col"></th>
                                        </tr>
                                    </thead>
                                </table>
                        </div>
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

      <script>
    // transaksi();
    let filter_wisata = $('#filter_wisata').val()

     $(document).ready(function () {
            isi()

      })
      var d = new Date();
        function isi(start_date= d.getFullYear() + "-" + (d.getMonth()+1) + "-" + d.getDate(), end_date= d.getFullYear() + "-" + (d.getMonth()+1) + "-" + d.getDate()  ) {
          var exportExtension = '';
          $('#tabel').DataTable({
               responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax : {
                    url : "{{route('transaksiadmin.data')}}",
                    data:  function(d){
                        d.filter_wisata = filter_wisata;
                        d.start_date = start_date;
                        d.end_date = end_date;
                        return d
                    }
                },
                columns:[
                        {data: 'wisatas', name: 'wisatas'},
                        {data: 'tanggal'},
                        {data: 'total_item'},
                        {
                        data: 'total_harga',
                            targets: 0,
                            render: $.fn.dataTable.render.number( ',', '.', 0, 'Rp. ' )
                        },
                         {data: 'jenis_pembayaran', name: 'jenis_pembayaran'},
                         {data: 'users', name: 'users'},
                         {data: 'aksi'}
                    ],
                 dom: "<'row '<'col-sm-12 col-md-4'l ><'col-sm-12 col-md-4'B><'col-sm-12 col-md-4'f>>" +
                            "<'row'<'col-sm-12'tr>>" +
                            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                 buttons: ['copy',
                            {
                            extend: 'excelHtml5',
                            title: 'Data Transaksi',
                            },
                            {
                                extend: 'pdfHtml5',
                                title: 'Data Transaksi'
                            },
                           'print'
                        ],
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
                      $('#tabel').DataTable().destroy();
                      isi(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
                      $("#transaksi").html();
                      tampiltransaksi(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
                      $("#pendapatan").html();
                      tampilpendapatan(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
                    },

                 );
              }
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
                 $(".filter").on('change',function(){
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

           function deleteData(url) {
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Ingin Menghapus Data ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    if($status = '200'){
                    setTimeout(() => {
                        Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Data Berhasil Dihapus',
                        showConfirmButton: false,
                        timer: 1000
                      }).then((res)=>{
                          $('#tabel').DataTable().ajax.reload();
                          $("#transaksi").html(tampiltransaksi);
                          $("#pendapatan").html(tampilpendapatan)
                      })
                  });
                }
                })
                .fail((errors) => {
                    alert('Tidak dapat menghapus data');
                    return;
                });
                  }
              })
              }


            $(".filter").on('change',function(start,end){
              filter_wisata = $('#filter_wisata').val();
              $('#tabel').DataTable().ajax.reload();
            })

        $('#export').click(function () {
        var  filter_wisata= $('#filter_wisata').val();
        $.ajax({
                url : "{{ route('export.excel') }}",
                data: {filter_wisata},
                success: function(data){
                  $("#export").sh(data);
                }
                });
    });

</script>
  @endpush
  @endsection

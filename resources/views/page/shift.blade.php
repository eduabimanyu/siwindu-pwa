 <!-- Main Content -->
 @extends('layouts.app')
 @section('tittle', 'shift')


 @section('conten')
      <!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Shift Kerja</h1>
            <div class="section-header-breadcrumb">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-5">
                <select class="form-control filter" name="filer_wisata" id="filter_wisata" required >
                    @foreach($wisata as $wisatad)
                    <option value="{{ $wisatad->id }}"> {{ $wisatad->nama_wisata }}</option>
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
                    <div class="card">
                        <div class="card-body">
                              <table class="table table-striped" id="tabel">
                                    <thead>
                                        <tr>
                                            <th scope="col">Kasir</th>
                                            <th scope="col">Mulai Shift </th>
                                            <th scope="col">Akhir Shift </th>
                                            <th scope="col">Pendapatan </th>
                                            <th scope="col">Aksi</th>
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
      <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
      <script>
      let filter_wisata = $('#filter_wisata').val()
      var d = new Date();
       $(document).ready(function () {
            isi()

        })

        function isi(start_date= d.getFullYear() + "-" + (d.getMonth()+1) + "-" + d.getDate(), end_date= d.getFullYear() + "-" + (d.getMonth()+1) + "-" + d.getDate()  ) {
            $('#tabel').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax : {
                    url : "{{route('shiftadmin.data')}}",
                    data:  function(d){
                        d.filter_wisata = filter_wisata;
                        d.start_date = start_date;
                        d.end_date = end_date;
                        return d
                    }

                },
                columns:[
                        {data: 'nama_kasir', name: 'nama_kasir'},
                        {data: 'tanggalawal', name: 'tanggalawal'},
                        {data: 'tanggalakhir', name: 'tanggalakhir'},
                        {
                        data: 'total_pendapatan',
                            targets: 0,
                            render: $.fn.dataTable.render.number( ',', '.', 0, 'Rp. ' )
                        },
                           {data: 'aksi', name: 'aksi'}
                    ],
                    searching: false,
                      bInfo : false,
                      bSort: false,
                      paginate: false
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

                    },

                 );
              }

              $(".filter").on('change',function(start,end){
              filter_wisata = $('#filter_wisata').val();
              $('#tabel').DataTable().ajax.reload();
               })

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

        </script>
  @endpush
  @endsection

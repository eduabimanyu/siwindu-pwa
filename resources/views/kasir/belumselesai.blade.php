 <!-- Main Content -->
 @extends('layouts.app')
 @section('tittle', 'Transaksi Belum Selesai')

 @section('conten')
      <!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Transaksi Belum Selesai</h1>
        <div class="section-header-breadcrumb">
          <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
          <div class="breadcrumb-item ">Belum Selesai</div>
        </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                  <div class="card card-primary">
                    <div class="card-body">
                        <table class="table table-striped" id="tabel">
                        <thead>
                            <tr>
                                <th scope="col">Kode Transaksi</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Jam</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        </table>
                     </div>
                 </div>
              </div>
            </div>
        </div>
        <input type="hidden" name="wisata" id="wisata" value="{{ Auth::user()->wisata }}">
    </section>
</div>

@push('js')
<script src="{{ asset('template') }}/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('template') }}/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->

<script>
// transaksi();
let wisata = $('#wisata').val()
$(document).ready(function () {
      isi()

})
  function isi() {
    var exportExtension = '';
    $('#tabel').DataTable({
         responsive: true,
          processing: true,
          serverSide: true,
          autoWidth: false,
          bSort: false,
          bInfo: false,
          paginate: false,
          ajax : {
              url : "{{route('belumselesai.index')}}",
              data:  function(d){
                        d.wisata = wisata;
                        return d }
          },
          columns:[
                  {data: 'id_transaksi'},
                  {data: 'tanggal'},
                  {data: 'jam'},
                  {data: 'aksi'}
              ],
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
                        location.reload();
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

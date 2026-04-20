 <!-- Main Content -->
 @extends('layouts.app')
 @section('tittle', 'Aktivitas')

 @section('conten')
      <!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Aktivitas</h1>
        <div class="section-header-breadcrumb">
          <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
          <div class="breadcrumb-item ">Aktivitas</div>
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
                                <th scope="col">Tanggal</th>
                                <th scope="col">Pendapatan</th>
                                <th scope="col">Jam</th>
                                <th scope="col">Detail</th>
                            </tr>
                        </thead>
                        </table>
                     </div>
                 </div>
              </div>
            </div>
        </div>
    </section>
    <input type="hidden" name="wisata" id="wisata" value="{{ Auth::user()->wisata }}">
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
              url : "{{route('aktivitas.data')}}",
              data:  function(d){
                        d.wisata = wisata;
                        return d }
          },
          columns:[
                  {data: 'tanggal'},
                  {
                  data: 'total_harga',
                      targets: 0,
                      render: $.fn.dataTable.render.number( ',', '.', 0, 'Rp. ' )
                  },
                  {data: 'jam'},
                   {data: 'aksi'}
              ],
      })
    }

</script>
@endpush
@endsection

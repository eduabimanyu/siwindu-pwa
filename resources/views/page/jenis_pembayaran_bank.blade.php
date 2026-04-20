 <!-- Main Content -->
 @extends('layouts.app')
 @section('tittle', 'Jenis Pembayaran Transfer Bank')

 @section('conten')
      <!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Jenis Pembayaran Transfer Bank</h1>
            <div class="section-header-breadcrumb">
            </div>
        </div>
        <div class="form-group col-md-4">
          <select class="form-control filter" name="filer_wisata" id="filter_wisata" required >
            <option value="{{ $wisatab=0 }}"> Semua</option>
            @foreach($wisata as $wisatab)
              <option value="{{ $wisatab->id }}"> {{ $wisatab->nama_wisata }}</option>
              @endforeach
          </select>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12 ">
                  <div class="card" >
                    <div class="card-body">
                            <table class="table table-striped" id="tabelbank">
                                <thead>
                                    <tr>
                                        <th scope="col">Wisata</th>
                                        <th scope="col">Tanggal | Jam</th>
                                        <th scope="col">Total Item</th>
                                        <th scope="col">Total Pendapatan</th>
                                        <th scope="col">Bank</th>
                                        <th scope="col">Kasir</th>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.5/dist/sweetalert2.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script >

let filter_wisata = $('#filter_wisata').val()


        $(document).ready(function () {
            bank()
        })
        function bank() {

            $('#tabelbank').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax : {
                    url : "{{route('transaksibank.data')}}",
                    data:  function(d){
                        d.filter_wisata = filter_wisata;
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
                         {data: 'banks', name: 'banks'},
                         {data: 'users', name: 'users'},

                        {data: 'aksi'}
                    ]
            })
        }
              $(".filter").on('change',function(e){
                filter_wisata = $('#filter_wisata').val()
                filter_kategori = $('#filter_kategori').val()
                $('#tabeltunai').DataTable().ajax.reload()
                $('#tabelbank').DataTable().ajax.reload()
                $('#tabelewalet').DataTable().ajax.reload()
            })

</script>
@endpush
  @endsection

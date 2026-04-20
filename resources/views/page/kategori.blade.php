 <!-- Main Content -->
 @extends('layouts.app')
 @section('tittle', 'Kategori')

 @section('conten')
      <!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Kategori </h1>
            <div class="section-header-breadcrumb">
                <button
                    class="btn btn-primary"
                    data-toggle="modal"
                    id="tambah"
                    data-target="#btn-tambah">Tambah Data</button>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 ">
                    <div class="card">
                        <div class="card-body">
                            {{-- <div class="form-group col-md-5">
                                <select class="form-control filter" name="filer_wisata" id="filter_wisata" required >
                                    <option value="{{ $wisatas=0 }}">Semua</option>
                                    @foreach($wisata as $wisatas)
                                    <option value="{{ $wisatas->id }}"> {{ $wisatas->nama_wisata }}</option>
                                    @endforeach
                                </select>
                              </div> --}}
                               <table class="table table-striped" id="tabel">
                                    <thead>
                                        <tr>
                                            <th scope="col">Kategori</th>
                                            {{-- <th scope="col">Nama Wisata</th> --}}
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

      <div class="modal fade" tabindex="-1" role="dialog" id="btn-tambah">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Tambah</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="{{ route('kategori.store') }}" method="post" id="forms">
                  @csrf
                  {{-- <div class="form-group">
                    <label>Pilih Wisata</label>
                    <select class="form-control form-control-lg" name="wisata" id="wisata" required >
                        <option value="" selected disabled >--Pilih Wisata--</option>
                        @foreach($wisata as $wisatac)
                      <option value="{{$wisatac->id }}"> {{ $wisatac->nama_wisata }}</option>
                      @endforeach
                    </select>
                </div> --}}
                  <div class="form-group">
                    <label>Nama Kategori</label>
                    <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" required>
                  </div>
                </div>
                    <div class="card-footer text-right">
                        <input type="text" hidden class="form-control" id="id" name="id">
                        <button class="btn btn-warning" id="tutup" data-dismiss="modal">Close</button>
                        <button class="btn btn-success" id="simpan" type="submit">Simpan</button>
                    </div>
                </div>
              </form >
           </div>
      </div>

      @push('js')
      <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
      <script >

    let filter_wisata = $('#filter_wisata').val()
        $(document).ready(function () {
            isi()

        })

        function isi() {
            $('#tabel').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax : {
                    url : "{{route('kategori.index')}}",
                    data:  function(d){
                        d.filter_wisata = filter_wisata;
                        return d
                    }
               },
                columns:[
                        {data: 'nama_kategori', name: 'nama_kategori'},
                        // {data: 'wisatas', name: 'wisatas'},
                        {data: 'aksi', name: 'aksi'}
                    ]
            })
        }

        $(document).on('submit','form', function (event){
            event.preventDefault();
            $.ajax({
                url: $(this).attr('action') ,
                type: $(this).attr('method'),
                typeData : "JSON",
                data : new FormData(this),
                processData :false,
                contentType: false,
                success : function(res){
                    console.log(res);
                    $('#tutup').click()
                    $('#forms')[0].reset();
                    $('#tabel').DataTable().ajax.reload()
                    Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Data Berhasil Disimpan',
                    showConfirmButton: false,
                    timer: 1000
                    })
                },
                error : function (xhr){
                    toastr.error('gagal')
                }
            })
        })
        $(document).on('click','.edit', function (){
            $('#forms').attr('action',"{{route('kategori.updates')}}")
            let id = $(this).attr('id')
            $.ajax({
                url :"{{route('kategori.edits')}}",
                type : 'post',
                data : {
                    id : id,
                    _token :"{{csrf_token()}}"
                },
                success: function(res){
                    console.log(res);
                    $('#id').val(res.id)
                    $('#wisata').val(res.wisata)
                    $('#nama_kategori').val(res.nama_kategori)
                    $('#tambah').click()
                },
                error : function (xhr){
                console.log(xhr);

            }

            })
        })

        $(document).on('click','.hapus', function (){
            let id = $(this).attr('id')
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
                    $.ajax({
                url :"{{route('kategori.hapus')}}",
                type : 'post',
                data : {
                    id : id,
                    _token :"{{csrf_token()}}"
                },
                success: function(res,status){
                  if($status = '200'){
                    setTimeout(() => {
                        Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Data Berhasil Dihapus',
                        showConfirmButton: false,
                        timer: 1000 
                      }).then((res)=>{
                          $('#tabel').DataTable().ajax.reload()
                      })
                  });
                }
            }, error : function (xhr){
                console.log(xhr);
              Swal.fire({
              position: 'center',
              icon: 'warning',
              title: 'Data Gagal Dihapus',
              text: "Kategori Terpakai Di Item!",
              showConfirmButton: false,
              timer: 2000
              })
            }

            })
                }
                })
            })

            $(".filter").on('change',function(){
                filter_wisata = $('#filter_wisata').val()
                $('#tabel').DataTable().ajax.reload();

            })

    </script>
  @endpush
  @endsection

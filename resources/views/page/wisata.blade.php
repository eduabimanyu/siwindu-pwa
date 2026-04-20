 <!-- Main Content -->
 @extends('layouts.app')
 @section('tittle', 'Wisata')
 @section('conten')
      <!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Objek Wisata</h1>
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
                                <table class="table table-striped " id="tabel">
                                    <thead>
                                        <tr>
                                            <th scope="col">Nama Wisata</th>
                                            <th scope="col">Alamat</th>
                                             <th scope="col">No.Hp</th>
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
              <form action="{{ route('wisata.store') }}" method="post" id="forms">
                  @csrf
                  <input type="text" hidden class="form-control" id="id" name="id">
                  <div class="form-group">
                    <label>Nama Wisata</label>
                    <input type="text" class="form-control" id="nama_wisata" name="nama_wisata" required>
                  </div>
                  <div class="form-group">
                    <label>No Hp</label>
                    <input type="text" class="form-control" id="no_hp" name="no_hp" required>
                  </div>
                  <div class="form-group">
                    <label>Alamat</label>
                    <input type="text" class="form-control" id="alamat" name="alamat" required>
                  </div>
                </div>
                    <div class="card-footer text-right">
                        <button class="btn btn-warning" id="tutup" data-dismiss="modal">Close</button>
                        <button class="btn btn-success" id="simpan" type="submit">Simpan</button>
                    </div>
                </div>
              </form >
           </div>
      </div>

      @push('js')
      <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
      <script>
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
                    url : "{{route('wisata.index')}}"
                },
                columns:[
                        {data: 'nama_wisata', name: 'nama_wisata'},
                        {data: 'alamat', name: 'alamat'},
                        {data: 'no_hp', name:'no_hp'},
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
            $('#forms').attr('action',"{{route('wisata.updates')}}")
            let id = $(this).attr('id')
            $.ajax({
                url :"{{route('wisata.edits')}}",
                type : 'post',
                data : {
                    id : id,
                    _token :"{{csrf_token()}}"
                },
                success: function(res){
                    console.log(res);
                    $('#id').val(res.id)
                    $('#nama_wisata').val(res.nama_wisata)
                    $('#alamat').val(res.alamat)
                    $('#no_hp').val(res.no_hp)
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
                url :"{{route('wisata.hapus')}}",
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
            },error : function (xhr){
              Swal.fire({
              position: 'center',
              icon: 'warning',
              title: 'Data Gagal Dihapus',
              text: "Wisata Ini Terpakai Di Item/Transaksi!",
              showConfirmButton: false,
              timer: 2000
              })
            }
            })
                }
                })
            })

    </script>
  @endpush
  @endsection

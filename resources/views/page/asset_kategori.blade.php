 <!-- Main Content -->
 @extends('layouts.app')
 @section('tittle', 'Kategori Asset')
 @section('conten')
      <!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Kategori Asset</h1>
            <div class="section-header-breadcrumb">
                <button onclick="addForm('{{ route('kategori_asset.store') }}')" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Tambah</button>
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
                                            <th scope="col">Nama Kategori</th>
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

<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form">
    <div class="modal-dialog " role="document">
        <form action="" method="post" class="form-horizontal" >
            @csrf
            @method('post')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                </div>
                <br>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="nama_kategori_asset" class="col-lg-4">Nama Kategori Asset</label>
                        <div class="col-lg-8">
                            <input type="text" name="nama_kategori_asset" id="nama_kategori_asset" class="form-control" required autofocus>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                   
                </div>
                <div class="card-footer text-right ">
                    <input type="text" hidden class="form-control" id="id" name="id">
                    <button class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
                    <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('js')
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.5/dist/sweetalert2.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
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
              url : "{{route('kategori_asset.index')}}"
          },
          columns:[
                  {data: 'nama_kategori_asset', name: 'nama_kategori_asset'},
                  {data: 'aksi', name: 'aksi'}
              ]
      })
  }


  function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Kategori Asset');
        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=nama_asset]').focus();
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
              $('#modal-form').modal('hide');
              $('#modal-form form')[0].reset();
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
      $('#modal-form form').attr('action',"{{route('kategori_asset.updates')}}")
      let id = $(this).attr('id')
      $.ajax({
          url :"{{route('kategori_asset.edits')}}",
          type : 'post',
          data : {
              id : id,
              _token :"{{csrf_token()}}"
          },
          success: function(res){
              console.log(res);
              $('#id').val(res.id)
              $('#nama_kategori_asset').val(res.nama_kategori_asset)
              $('#nama_asset').val(res.nama_asset)
              $('#modal-form').modal('show');
              $('#modal-form .modal-title').text('Edit Kategori Asset');

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
          url :"{{route('kategori_asset.hapus')}}",
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
      },
      })
          }
          })
      })

</script>
@endpush
  @endsection

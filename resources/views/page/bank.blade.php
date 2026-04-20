 <!-- Main Content -->
 @extends('layouts.app')
 @section('tittle', 'Bank')
 @section('conten')
      <!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Bank</h1>
            <div class="section-header-breadcrumb">
                <button onclick="addForm('{{ route('bank.store') }}')" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Tambah</button>
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
                                            <th scope="col">No</th>
                                            <th scope="col">Nama Bank</th>
                                            <th scope="col">No. Rekening</th>
                                            <th scope="col">Pemilik</th>
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
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="nama_bank" class="col-lg-4">Nama Bank</label>
                        <div class="col-lg-8">
                            <input type="text" name="nama_bank" id="nama_bank" class="form-control" required autofocus>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="nomor_rekening" class="col-lg-4">Nomor Rekening</label>
                        <div class="col-lg-8">
                            <input type="text" name="nomor_rekening" id="nomor_rekening" class="form-control" required autofocus>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="atas_nama" class="col-lg-4">Pemilik</label>
                        <div class="col-lg-8">
                            <input type="text" name="atas_nama" id="atas_nama" class="form-control" required autofocus>
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
              url : "{{route('bank.index')}}"
          },
          columns:[
                  {data: 'DT_RowIndex', searchable: false, sortable: false},
                  {data: 'nama_bank', name: 'nama_bank'},
                  {data: 'nomor_rekening', name: 'nomor_rekening'},
                  {data: 'atas_nama', name: 'atas_nama'},
                  {data: 'aksi', name: 'aksi'}
              ]
      })
  }


  function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Bank');
        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=nama_bank]').focus();
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
      $('#modal-form form').attr('action',"{{route('bank.updates')}}")
      let id = $(this).attr('id')
      $.ajax({
          url :"{{route('bank.edits')}}",
          type : 'post',
          data : {
              id : id,
              _token :"{{csrf_token()}}"
          },
          success: function(res){
              console.log(res);
              $('#id').val(res.id)
              $('#nama_bank').val(res.nama_bank)
              $('#nomor_rekening').val(res.nomor_rekening)
              $('#atas_nama').val(res.atas_nama)
              $('#modal-form').modal('show');
              $('#modal-form .modal-title').text('Edit Bank');

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
          url :"{{route('bank.hapus')}}",
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

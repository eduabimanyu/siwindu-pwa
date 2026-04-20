 <!-- Main Content -->
 @extends('layouts.app')
 @section('tittle', 'Item')
 @section('conten')
      <!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Item</h1>
            <div class="section-header-breadcrumb">
                <button onclick="addForm('{{ route('item.store') }}')" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Tambah</button>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12 ">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-row">
                            <div class="form-group col-md-4">
                                <select class="form-control filter" name="filer_wisata" id="filter_wisata" required >
                                    <option value="{{ $wisatas=0 }}">Semua</option>
                                    @foreach($wisata as $wisatas)
                                    <option value="{{ $wisatas->id }}"> {{ $wisatas->nama_wisata }}</option>
                                    @endforeach
                                </select>
                              </div>
                              <div class="form-group col-md-4">
                                <select class="form-control filter" name="filer_kategori" id="filter_kategori" required >
                                    <option value="">--Pilih Kategori--</option>
                                    @foreach($kategori as $kategori)
                                    <option value="{{ $kategori->id }}"> {{ $kategori->nama_kategori }}</option>
                                    @endforeach
                                </select>
                              </div>
                            </div>
                                <table class="table table-striped" id="tabel">
                                    <thead>
                                        <tr>
                                            <th scope="col">Kode Item</th>
                                            <th scope="col">Nama Item</th>
                                            <th scope="col">Kategori</th>
                                            <th scope="col">Harga</th>
                                            <th scope="col">diskon</th>
                                            <th scope="col">Wisata</th>
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
      
      <div class="modal fade" tabindex="-1" role="dialog" id="modal-form">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Aksi</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="" method="post" >
                  @csrf
                  @method('post')
                  <div class="form-group">
                    <label>Pilih Wisata</label>
                    <select class="form-control form-control-lg" name="wisata" id="wisata" required >
                        <option value="" selected disabled >--Pilih Wisata--</option>
                        @foreach($wisata as $wisatac)
                      <option value="{{$wisatac->id }}"> {{ $wisatac->nama_wisata }}</option>
                      @endforeach  
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Pilih Kategori</label>
                    <select class="form-control form-control-lg" name="kategori" id="kategori" required >
                        <option value="" selected disabled >--Pilih Kategori--</option>
                        @foreach($kategorid as $kategorid)
                      <option value="{{ $kategorid->id }}"> {{ $kategorid->nama_kategori }}</option>
                      @endforeach  
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Nama Item</label>
                    <input type="text" class="form-control" id="nama_item" name="nama_item" required>
                  </div>
                  <div class="form-group">
                    <label>Harga</label>
                    <input type="text" class="form-control" id="harga" name="harga"  onkeypress='return event.charCode >= 48 && event.charCode <= 57' required>
                  </div>
                  <div class="form-group">
                    <label>Diskon</label>
                    <input type="text" class="form-control" id="diskon" name="diskon"  onkeypress='return event.charCode >= 48 && event.charCode <= 57' value="0">
                  </div>
                  <div class="form-group">
                </div>
                    <div class="card-footer text-right">
                        <button class="btn btn-warning" id="tutup" data-dismiss="modal">Close</button>
                        <button class="btn btn-success" id="simpan" type="submit">Simpan</button>
                    </div>
                </div>
              </form >
           </div>
      </div></div>

      @push('js')
      <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.5/dist/sweetalert2.all.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
      <script>

    let filter_wisata = $('#filter_wisata').val(),
    filter_kategori =$('#filter_kategori').val()
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
            url : "{{route('item.data')}}",
            data:  function(d){
                d.filter_wisata = filter_wisata;
                d.filter_kategori = filter_kategori;
                return d
            }
        },
        columns:[
                {data: 'kode_item'},
                {data: 'nama_item'},
                {data: 'kategoris'},
                {
                data: 'harga',
                    targets: 0,
                    render: $.fn.dataTable.render.number( ',', '.', 0, 'Rp. ' )
                },
                {
                data: 'diskon',
                    targets: 0,
                    render: $.fn.dataTable.render.number( ',', '.', 0, '', ' %' )
                },
                {data: 'wisatas'},
                {data: 'aksi'}
            ]
    })
            
        }
        $('#modal-form').on('submit', function (e) {
            if (! e.preventDefault()) {
                $.post($('#modal-form form').attr('action'), $('#modal-form form').serialize())
                    .done((response) => {
                        $('#modal-form').modal('hide');
                        $('#tabel').DataTable().ajax.reload()
                        Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Data Berhasil Disimpan',
                        showConfirmButton: false,
                        timer: 1000
                        })
                    })
                    .fail((errors) => {
                        alert('Tidak dapat menyimpan data');
                        return;
                    });
            }
        });

        function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Item');
        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=nama_item]').focus();
        }
        function editForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit item');
        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');
        $('#modal-form [name=nama_item]').focus();
        $.get(url)
            .done((response) => {
                $('#modal-form [name=wisata]').val(response.wisata);
                $('#modal-form [name=nama_item]').val(response.nama_item);
                $('#modal-form [name=kategori]').val(response.kategori);
                $('#modal-form [name=harga]').val(response.harga);
                $('#modal-form [name=diskon]').val(response.diskon);
            })
            .fail((errors) => {
                alert('Tidak dapat menampilkan data');
                return;
            });
       
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
                          $('#tabel').DataTable().ajax.reload()
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

        $(".filter").on('change',function(){
                filter_wisata = $('#filter_wisata').val()
                filter_kategori = $('#filter_kategori').val()
                 $('#tabel').DataTable().ajax.reload()
                
            })

    </script>
  @endpush
  @endsection

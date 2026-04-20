 <!-- Main Content -->
 @extends('layouts.app')
 @section('tittle', 'Promo')
 @section('conten')
      <!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>E-Wallet</h1>
            <div class="section-header-breadcrumb">
                <button onclick="addForm('{{ route('promo.store') }}')" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Tambah</button>
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
                                            <th scope="col">Nama Promo</th>
                                            <th scope="col">Diskon</th>
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
                        <label for="nama_promo" class="col-lg-4">Nama Promo</label>
                        <div class="col-lg-8">
                            <input type="text" name="nama_promo" id="nama_promo" class="form-control" required autofocus>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="atas_nama" class="col-lg-4">Diskon</label>
                        <div class="col-lg-8">
                            <input type="number" name="diskon" id="diskon" class="form-control" required autofocus>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right ">
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
                url : "{{route('promo.data')}}"
            },
            columns:[
                    {data: 'nama_promo'},
                    {
                    data: 'diskon',
                        targets: 0,
                        render: $.fn.dataTable.render.number( ',', '.', 0, '', ' %' )
                    },
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
            $('#modal-form .modal-title').text('Tambah promo');
            $('#modal-form form')[0].reset();
            $('#modal-form form').attr('action', url);
            $('#modal-form [name=_method]').val('post');
            $('#modal-form [name=nama_promo]').focus();
            }
            function editForm(url) {
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Edit promo');
            $('#modal-form form')[0].reset();
            $('#modal-form form').attr('action', url);
            $('#modal-form [name=_method]').val('put');
            $('#modal-form [name=nama_promo]').focus();
            $.get(url)
                .done((response) => {
                    $('#modal-form [name=nama_promo]').val(response.nama_promo);
                    $('#modal-form [name=diskon]').val(response.diskon);
                })
                .fail((errors) => {
                    alert('Tidak dapat menampilkan data');
                    return;
                });
            
            }


                $('#wisata').change(function(){
                var wisataID = $(this).val();
                if(wisataID){
                    $.ajax({
                    type:"GET",
                    url:"getpromo?wisataID="+wisataID,
                    dataType: 'JSON',
                    success:function(res){
                        if(res){
                            $("#kategori").empty();
                            $("#kategori").append('<option>---Pilih Kategori---</option>');
                            $.each(res,function(nama,kode){
                                $("#kategori").append('<option value="'+kode+'">'+nama+'</option>');
                            });
                        }else{
                        $("#kategori").empty();
                        }
                    }
                    });
                }else{
                    $("#kategori").empty();
                }

            });

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

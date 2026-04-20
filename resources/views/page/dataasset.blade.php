 <!-- Main Content -->
 @extends('layouts.app')
 @section('tittle', 'Data Aset')
 @section('conten')
      <!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Data Aset</h1>
            <div class="section-header-breadcrumb">
                <button onclick="addForm('{{ route('dataasset.store') }}')" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Tambah</button>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12 ">
                    <div class="card">
                        <div class="card-body">
                           <p>Filter Data Aset</p>
                            <div class="form-row">
                              <div class="form-group col-md-3">
                                <select class="form-control filter" name="filer_kategori" id="filter_kategori" >
                                    <option value="">--Pilih Kategori--</option>
                                    @foreach($kasset as $kassetd)
                                    <option value="{{ $kassetd->id }}"> {{ $kassetd->nama_kategori_asset }}</option>
                                    @endforeach
                                </select>
                              </div>
                      
                                <div class="form-group col-md-3">
                                <select class="form-control filter" name="filer_departemen" id="filter_departemen" >
                                    <option value="">--Pilih Departemen--</option>
                                    @foreach($departemen as $departemenb)
                                    <option value="{{ $departemenb->id }}"> {{ $departemenb->nama_departemen }}</option>
                                    @endforeach
                                </select>
                              </div>
                              
                                <div class="form-group col-md-3">
                                <select class="form-control filter" name="filer_kondisi" id="filter_kondisi" >
                                    <option value="">--Pilih Kondisi--</option>
                                    <option value="Baik"> Baik </option>
                                    <option value="Rusak"> Rusak </option>
                                    <option value="Rusak Berat"> Rusak Berat </option>
                                </select>
                              </div>
                            </div>
                                <table class="table table-striped" id="tabel">
                                    <thead>
                                        <tr>
                                            <th scope="col">Kode Aset</th>
                                            <th scope="col">Foto</th>
                                            <th scope="col">Nama Aset</th>
                                            <th scope="col">Merk</th>
                                            <th scope="col">Departemen</th>
                                            <th scope="col">Kondisi</th>
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
                    <div class="text-center">
                    <div class="tampil-foto"></div>
                    </div>
                    <br>
                    <div class="form-group row">
                        <label for="kode_aset" class="col-lg-4">Kode Aset</label>
                        <div class="col-lg-8">
                            <input type="text" name="kode_asset" id="kode_asset" class="form-control"  >
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                <div class="form-group row">
                    <label for="nama_dataasset" class="col-lg-4">Kategori</label>
                    <div class="col-lg-8">
                    <select class="form-control" id="kategori" name="kategori">
                        <option value="" selected disabled >--Pilih Kategori--</option>
                        @foreach($kasset as $kassets)
                      <option value="{{$kassets->id }}"> {{ $kassets->nama_kategori_asset }}</option>
                      @endforeach
                    </select>
                </div>
                </div>
                <!-- <div class="form-group row">-->
                <!--        <label for="nama_dataasset" class="col-lg-4">Nama Asset</label>-->
                <!--    <div class="col-lg-8">-->
                <!--    <select class="form-control " name="nama_asset" id="nama_asset" required >-->
                <!--        <option value="" selected disabled >--Pilih Nama Asset--</option>-->
                <!--        @foreach($asset as $assets)-->
                <!--      <option value="{{$assets->id }}"> {{ $assets->nama_asset }}</option>-->
                <!--      @endforeach-->
                <!--    </select>-->
                <!--    </div>-->
                <!--</div>-->
                 <div class="form-group row">
                    <label for="nama_dataasset" class="col-lg-4">Merk</label>
                    <div class="col-lg-8">
                    <select class="form-control" id="merk" name="merk"  required>
                        <option value="" selected disabled >--Pilih Merk--</option>
                        @foreach($merk as $merks)
                      <option value="{{$merks->id }}"> {{ $merks->nama_merk }}</option>
                      @endforeach
                    </select>
                </div>
                </div>
                     <div class="form-group row">
                        <label for="kode_aset" class="col-lg-4">Nama Asset</label>
                        <div class="col-lg-8">
                            <input type="text" name="nama_asset" id="nama_asset" class="form-control"  required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                <div class="form-group row">
                    <label for="tgl_pembelian" class="col-lg-4">Tgl. Pembelian</label>
                    <div class="col-lg-8">
                        <input type="date" name="tgl_pembelian" id="tgl_pembelian" class="form-control" >
                        <span class="help-block with-errors"></span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="harga" class="col-lg-4">Harga Pembelian</label>
                    <div class="col-lg-8">
                        <input type="number" name="harga" id="harga" class="form-control" required >
                        <span class="help-block with-errors"></span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="departemen" class="col-lg-4">Lokasi</label>
                <div class="col-lg-8">
                <select class="form-control " name="wisata" id="wisata" required >
                    <option value="" selected disabled>--Pilih Lokasi--</option>
                    <option >Kantor Perumda </option>
                    @foreach($wisata as $wisatas)
                  <option value="{{$wisatas->id }}"> {{ $wisatas->nama_wisata }}</option>
                  @endforeach
                </select>
                </div>
                </div>
                <div class="form-group row">
                    <label for="departemen" class="col-lg-4">Departemen</label>
                <div class="col-lg-8">
                <select class="form-control " name="departemen" id="departemen" required>
                    <option value="" selected disabled  >--Pilih Departemen--</option>
                    <option >Non Departemen </option>
                    @foreach($departemen as $departemens)
                  <option value="{{$departemens->id }}"> {{ $departemens->nama_departemen }}</option>
                  @endforeach
                </select>
                </div>
                </div>
                <div class="form-group row">
                    <label for="user" class="col-lg-4">User</label>
                <div class="col-lg-8">
                <select class="form-control " name="user" id="user" required >
                    <option value="" selected disabled >--Pilih User--</option>
                    @foreach($user as $users)
                  <option value="{{$users->id }}"> {{ $users->nama_user }}</option>
                  @endforeach
                </select>
                </div>
                </div>
                <div class="form-group row">
                    <label for="kondisi" class="col-lg-4">Kondisi</label>
                    <div class="col-lg-8">
                        <select class="form-control " name="kondisi" id="kondisi" required >
                            <option value="" selected disabled >--Pilih Kondisi--</option>
                            <option > Baik </option>
                            <option > Rusak </option>
                            <option > Rusak Berat </option>
                            <option > - </option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="keterangan" class="col-lg-4">Keterangan</label>
                    <div class="col-lg-8">
                        <input type="text" name="keterangan" id="keterangan" class="form-control">
                        <span class="help-block with-errors"></span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="foto" class="col-lg-4">Foto</label>
                    <div class="col-lg-8">
                        <input accept="image/png, image/gif, image/jpeg, image/jpg" type="file" name="foto" value="" class="form-control" id="foto" />
                        <span class="help-block with-errors"></span>
                    </div>
                    <label for="foto" class="control-label" id="label_fotoa"></label>
                </div>
                <div class="text-center">
                <label for="foto" >
                    <img src="{{ asset('template')}}/images.png "  name="foto1" id="previewHolder" for="foto" onchange="preview('.tampil-foto', this.files[0])" class="rounded" style="width:50%;height:20%">
                  </label>
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


<div class="modal fade" id="modal-form1" tabindex="-1" role="dialog" aria-labelledby="modal-form">
    <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                </div>
                <br>
                <div class="modal-body">
                    <div class="tampil-foto1"></div>
                    <br>
                    <div class="form-group">
                        <label for="kode_aset" class="col-lg-4">KODE ASET</label>
                        <div class="col-lg-8">
                           <p name="kode_asseta" id="kode_asseta" ></p>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>

                <div class="form-group">
                    <label for="nama_dataasset" class="col-lg-4">KATEGORI</label>
                    <div class="col-lg-8">
                    <p name="kategoria" id="kategoria" ></p>
                </div>
                 <div class="form-group ">
                        <label for="nama_dataasset" class="col-lg-4">NAMA ASET</label>
                    <div class="col-lg-8">
                     <p name="nama_asseta" id="nama_asseta" ></p>
                    </div>
                </div>
                <div class="form-group ">
                    <label for="nama_dataasset" class="col-lg-4">MERK</label>
                    <div class="col-lg-8">
                    <p name="merka" id="merka" ></p>
                </div>
                </div>

                <div class="form-group ">
                    <label for="tgl_pembelian" class="col-lg-4">Tgl. Pembelian</label>
                    <div class="col-lg-8">
                      <p name="tgl_pembeliana" id="tgl_pembeliana" ></p>
                    </div>
                </div>
                <div class="form-group ">
                    <label for="harga" class="col-lg-4">Harga Pembelian</label>
                    <div class="col-lg-8">
                        <p name="hargaa" id="hargaa" ></p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="wisataa" class="col-lg-4">Wisata</label>
                <div class="col-lg-8">
                <p name="wisataa" id="wisataa" ></p> <p name="wisatab" id="wisatab" ></p>
                </div>
                </div>
                <div class="form-group ">
                    <label for="departemen" class="col-lg-4">Departemen</label>
                <div class="col-lg-8">
                <p name="departemena" id="departemena" ></p>
                </div>
                </div>
                <div class="form-group ">
                    <label for="user" class="col-lg-4">User</label>
                <div class="col-lg-8">
               <p name="usera" id="usera" ></p>
                </div>
                </div>
                <div class="form-group ">
                    <label for="kondisi" class="col-lg-4">Kondisi</label>
                    <div class="col-lg-8">
                         <p name="kondisia" id="kondisia" ></p>
                    </div>
                </div>
                <div class="form-group ">
                    <label for="keterangan" class="col-lg-4">Keterangan</label>
                    <div class="col-lg-8">
                       <p name="keterangana" id="keterangana" ></p>
                    </div>
                </div>
                </div>
               </div>
            </div>
    </div>
</div>



@push('js')
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.5/dist/sweetalert2.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>

    let filter_departemen = $('#filter_departemen').val(),
    filter_kategori =$('#filter_kategori').val(),
    filter_kondisi =$('#filter_kondisi').val()
    
$(document).ready(function() {
		function readURL(input) {
			if (input.files && input.files[0]) {
				var reader = new FileReader();
				reader.onload = function(e) {
					$('#previewHolder').attr('src', e.target.result);
				}
				reader.readAsDataURL(input.files[0]);
			} else {
				alert('select a file to see preview');
				$('#previewHolder').attr('src', '');
			}
		}
		$("#foto").change(function() {
			readURL(this);
		});

	});

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
              url : "{{route('dataasset.index')}}",
                data:  function(d){
                d.filter_kategori = filter_kategori;
                d.filter_departemen = filter_departemen;
                d.filter_kondisi = filter_kondisi;
                return d
            }
          },
          columns:[
                  {data: 'kode_asset', name: 'kode_asset'},
                  {data: 'foto', name: 'foto' , 
                  render: function( data, type, full, meta ) {
                       return "<img src=\"" + data + "\" height=\"50\"/>";
                    }
                      
                  },
                  {data: 'nama_asset', name: 'nama_asset'},
                  {data: 'merks', name: 'merks'},
                  {data: 'departement', name: 'departement'},
                  {data: 'kondisi', name: 'kondisi'},
                  {data: 'aksi', name: 'aksi'}
              ]
      })
  }




  function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Data Aset');
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
      $('#modal-form form').attr('action',"{{route('dataasset.updates')}}")
      let id = $(this).attr('id')
      $.ajax({
          url :"{{route('dataasset.edits')}}",
          type : 'post',
          data : {
              id : id,
              _token :"{{csrf_token()}}"
          },
          success: function(res){
              console.log(res);
              $('#id').val(res.id)
              $('#kode_asset').val(res.kode_asset)
              $('#nama_dataasset').val(res.nama_dataasset)
              $('#nama_asset').val(res.nama_asset)
              $('#kategori').val(res.kategori)
              $('#merk').val(res.merk)
              $('#tgl_pembelian').val(res.tgl_pembelian)
              $('#harga').val(res.harga)
              $('#wisata').val(res.wisata)
              $('#departemen').val(res.departemen)
              $('#user').val(res.user)
              $('#keterangan').val(res.keterangan)
              $('#kondisi').val(res.kondisi)
              $('#modal-form').modal('show');
              $('.tampil-foto').html(`<img src="{{ url('/') }}${res.foto}" width="200">`);
              $('#modal-form .modal-title').text('Edit Data Aset');

          },
          error : function (xhr){
          console.log(xhr);

      }

      })
  })



  $(document).on('click','.view', function (){
      let id = $(this).attr('id')
      $.ajax({
          url :"{{route('dataasset.view')}}",
          type : 'post',
          data : {
              id : id,
              _token :"{{csrf_token()}}"
          },
          success: function(res){
              $('#id').text(res.id)
              $('#kode_asseta').text(res.kode_asset)
              $('#nama_asseta').text(res.nama_asset)
              $('#kategoria').text(res.kasset)
              $('#merka').text(res.merks)
              $('#tgl_pembeliana').text(res.tgl_pembelian)
              $('#hargaa').html(`Rp. ${res.harga}  `)
              $('#wisataa').text(res.wisata)
              $('#wisatab').text(res.wisatas)
              $('#departemena').text(res.departemens);
              $('#usera').text(res.users)
              $('#keterangana').text(res.keterangan)
              $('#kondisia').text(res.kondisi)
              $('#modal-form1').modal('show');
              $('.tampil-foto1').html(`<img src="{{ url('/') }}${res.foto}" width="200">`);
              $('#modal-form1 .modal-title').text('Detail Asset');
              

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
          url :"{{route('dataasset.hapus')}}",
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


    //     $('#kategori').change(function(){
    //         var merkID = $(this).val();
    //         if(merkID){
    //             $.ajax({
    //             type:"GET",
    //             url:"getmerk?merkID="+merkID,
    //             dataType: 'JSON',
    //             success:function(res){
    //                 if(res){
    //                     $("#nama_asset").empty();
    //                     $("#nama_asset").append('<option>--Pilih Nama Asset--</option>');
    //                     $.each(res,function(nama,kode){
    //                         $("#nama_asset").append('<option value="'+kode+'">'+nama+'</option>');
    //                     });
    //                 }else{
    //                 $("#nama_asset").empty();
    //                 }
    //             }
    //             });
    //         }else{
    //             $("#nama_asset").empty();
    //         }

    //     });

     $('#kategori').change(function(){
            var kategorisID = $(this).val();
            if(kategorisID){
                $.ajax({
                type:"GET",
                url:"getkategoris?kategorisID="+kategorisID,
                dataType: 'JSON',
                success:function(res){
                    if(res){
                        $("#merk").empty();
                        $("#merk").append('<option>--Pilih Merk--</option>');
                        $.each(res,function(nama,kode){
                            $("#merk").append('<option value="'+kode+'">'+nama+'</option>');
                        });
                    }else{
                    $("#merk").empty();
                    }
                }
                });
            }else{
                $("#merk").empty();
            }

        });

        $(".filter").on('change',function(){
                filter_kategori = $('#filter_kategori').val()
                filter_departemen = $('#filter_departemen').val()
                filter_kondisi = $('#filter_kondisi').val()
                 $('#tabel').DataTable().ajax.reload()
                
            })



</script>
@endpush
  @endsection

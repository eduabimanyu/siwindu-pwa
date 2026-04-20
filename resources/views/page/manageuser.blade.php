 <!-- Main Content -->

 @extends('page.user')
 @section('tittle', 'User')
 @section('user')

<div class="section-body">           
  <div class="row">
    <div class="col-12 ">
      <div class="card">
          <div class="card-body">
             <table class="table table-striped" id="tabel" >
              <thead>
              <tr>
                <th scope="col">No</th> 
                <th scope="col">Nama</th>
                <th scope="col">Email</th>
                <th scope="col">Role</th>
                <th scope="col">Wisata</th>
                <th scope="col">Aksi</th>
              </tr> 
            </thead>
           </table>
        </div>
       </div>
       <div class="section">
        <button class="btn btn-primary" data-toggle="modal" id="tambah" data-target="#btn-tambah">Tambah Data</button>
       </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="btn-tambah">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="forms">
          @csrf
            <div class="form-group">
              <label>Username</label>
              <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
              <label>Email</label>
              <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
              <label>Password</label>
              <input type="text" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
              <label>Pilih Role</label>
              <select class="form-control form-control-lg" name="role" id="role" required >
                  <option value="" selected disabled >--Pilih Role--</option>
                  @foreach($role as $roles)
                <option value="{{$roles->id }}"> {{ $roles->display_name }}</option>
                @endforeach  
              </select>
             </div>
             <div class="form-group">
              <label>Pilih Wisata</label>
              <select class="form-control form-control-lg" name="wisata" id="wisata" required >
                  <option value="" selected disabled >--Wisata--</option>
                  <option value="Semua"> Semua </option>
                  @foreach($wisata as $wisatab)
                <option value="{{$wisatab->id }}"> {{ $wisatab->nama_wisata }}</option>
                @endforeach
              </select>
             </div>
          </div>
              <div class="card-footer text-right">
                 <button class="btn btn-warning" id="tutup" data-dismiss="modal">Close</button>
                  <button class="btn btn-success" id="simpan" >Simpan</button>
              </div>
          </div>
        </form >
     </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="btn-info">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Role : <label  for="" id="labelRole"></label></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        {{-- <form id="forms"> --}}
          @csrf
            <div class="form-group">
              <label>Username</label>
              <input type="text" readonly class="form-control" id="usernames" name="usernames">
            </div>
            <div class="form-group">
              <label>Email</label>
              <input type="email"readonly class="form-control" id="emails" name="emails">
            </div>
            <div class="form-group" hidden id="formBaru">
              <label>Password Baru</label>
              <input type="password" class="form-control" id="passwordbaru" name="passwordbaru" tabindex="2"required  autocomplete="current-password">
             <span class="help-block with-errors"></span>
            </div>
            <div class="form-group" hidden id="formKonfirmasi">
              <label>Konfirmasi Password</label>
              <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" tabindex="2"required  autocomplete="current-password">
             <span class="help-block with-errors"></span>
            </div>
            <div class="form-group" hidden id="formRole" >
              <label>Pilih Role</label>
              <select class="form-control" name="roles" id="roles" required >
                  <option value="" selected disabled >--Pilih Role--</option>
                  @foreach($role as $roles)
                <option value="{{$roles->id }}"> {{ $roles->display_name }}</option>
                @endforeach
              </select>
             </div>
             <div class="form-group" hidden id="formWisata">
             <label>Pilih Wisata</label>
             <select class="form-control" name="wisatas" id="wisatas" required  >
                  <option value="Semua"> Semua </option>
                  @foreach($wisata as $wisatas)
                 <option value="{{$wisatas->id }}"> {{ $wisatas->nama_wisata }}</option>
                  @endforeach
              </select>
          </div>
          </div>
              <div class="card-footer text-right">
                  <input type="text" hidden class="form-control" id="id" name="id">
                  <button class="btn btn-warning" id="tutup" data-dismiss="modal">Close</button>
                  <button class="btn btn-success" id="editUser" >Edit</button>
                  <button class="btn btn-success" hidden id="simpaneditUser" >Simpan Edit</button>
              </div>
          </div>
        {{-- </form > --}}
     </div>
</div>

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

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
        searching: false,
        paginate: false,
          ajax : {
              url : "{{route('user')}}"
          },
          columns:[
                  {
                      "data" :null, "sortable": false,
                      render : function (data, type, row, meta) {
                      return meta.row + meta.settings._iDisplayStart + 1
                      }
                  },
                  {data: 'name', name: 'name'},
                  {data: 'email', name: 'email'},
                  {data: 'roles', name: 'roles'},
                  {data: 'wisatas',  name: 'wisatas'},
                  {data: 'aksi', name: 'aksi'}
              ]
      })
      $.fn.dataTable.ext.errMode = 'none';
  }

  $(document).on('click','#simpan', function (){
                $.ajax({
                url: "{{ route('user.store') }}" ,
                type: 'post',
                data : {
                  username : $('#username').val(),
                  email : $('#email').val(),
                  password : $('#password').val(),
                  ket : $('#ket').val(),
                  role : $('#role').val(),
                  wisata : $('#wisata').val(),
                  "_token" : "{{ csrf_token() }}"
                },
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

        $(document).on('click','.info', function (){
                $.ajax({
                url: "{{route('user.info')}}",
                type: 'get',
                data : { 
                  id : $(this).attr('id'),
                  "_token" :"{{ csrf_token() }}"
                },success : function(res){
                    $('#usernames').val(res.user.name)
                    $('#id').val(res.user.id)
                    $('#emails').val(res.user.email)
                    $('#labelRole').text(res.roles)
                    $('#roles').val(res.roled)
                    $('#wisatas').val(res.user.wisata)
                    console.log(res);
                }, error : function (xhr){
                    console.error(xhr);
                }
            })
        })


        $(document).on('click','#editUser', function (){
          $('#simpaneditUser').attr('hidden',false) 
          $('#editUser').attr('hidden',true)   
          $('#usernames').attr('readonly',false)  
          $('#emails').attr('readonly',false)  
          $('#formRole').attr('hidden',false)  
          $('#formWisata').attr('hidden',false) 
          $('#formKonfirmasi').attr('hidden',false)  
          $('#formBaru').attr('hidden',false)   

        })

        $(document).on('click','#simpaneditUser', function (){
          $.ajax({
                url: "{{route('user.edit')}}",
                type: 'post',
                data : { 
                  id : $('#id').val(),
                  usernames : $('#usernames').val(),
                  emails : $('#emails').val(),
                  roles : $('#roles').val(),
                  wisatas : $('#wisatas').val(),
                  passwordbaru : $('#passwordbaru').val(),
                  "_token" : "{{ csrf_token() }}"
                },success : function(res){
                  console.log(res);
                    $('#tabel').DataTable().ajax.reload()
                    $('#btn-info').modal('hide');
                    Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Data Berhasil Disimpan',
                    showConfirmButton: false,
                    timer: 1000
                    })
                    
                }, error : function (xhr){
                    console.error(xhr);
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
          url :"{{route('user.hapus')}}",
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

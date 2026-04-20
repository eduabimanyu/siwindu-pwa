 <!-- Main Content -->

 @extends('page.user')
 @section('tittle', 'permission')
 @section('user')

<div class="section-body">           
  <div class="row">
   
    <div class="col-7">
      <div class="card">
          <div class="card-body">
            <div class="table-responsive">
             <table class="table table-striped" id="tabel" >
              <thead>
              <tr>
                <th scope="col">No</th> 
                <th scope="col">Nama</th>
                <th scope="col">Display Name</th>
                <th scope="col">Deskripsi</th>
                <th scope="col">Aksi</th>
              </tr> 
            </thead>
           </table>
        </div>
        </div>
       </div>
       <div class="section">
        <button class="btn btn-primary" data-toggle="modal" id="tambah" data-target="#btn-tambah">Tambah Data</button>
       </div>
      </div>
      <div class="col-3">
        <div class="card">
            <div class="card-body">
              <div class="form-group">
                <label>Nama Permission</label>
                <input type="text" class="form-control" id="permission" name="permission" required>
              </div>
              <div class="form-group">
                <label>Nama Tampilan</label>
                <input type="text" class="form-control" id="display" name="display" required>
              </div>
              <div class="form-group">
                <label>Deskripsi</label>
                <input type="text" class="form-control" id="deskripsi" name="deskripsi" required>
              </div>
              <div class="section">
                <button class="btn btn-primary" data-toggle="modal" id="tambah" data-target="#btn-tambah">Tambah Data</button>
               </div>
            </div>
        </div>
      </div>
      <div class="col-2">
        <div class="card">
            <div class="card-body">
              <label class="d-block">Permission</label>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="defaultCheck1">
                <label class="form-check-label" for="defaultCheck1">
                  Read
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="defaultCheck2">
                <label class="form-check-label" for="defaultCheck2">
                  Create
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="defaultCheck3">
                <label class="form-check-label" for="defaultCheck3">
                  Update
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="defaultCheck4">
                <label class="form-check-label" for="defaultCheck4">
                  Delete
                </label>
              </div>
            </div>
        </div>
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
            {{-- <div class="form-group">
              <label>Pilih Role</label>
              <select class="form-control form-control-lg" name="role" id="role" required >
                  <option value="" selected disabled >--Pilih Role--</option>
                  @foreach($role as $roles)
                <option value="{{$roles->id }}"> {{ $roles->display_name }}</option>
                @endforeach  
              </select>
             </div>
          </div> --}}
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
        <h5 class="modal-title">Role : <p><label  for="" id="labelRole"></label></p></h5>
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
            {{-- <div class="form-group" hidden id="formRole" >
              <label>Pilih Role</label>
              <select class="form-control form-control-lg" name="roles" id="roles" required >
                  <option value="" selected disabled >--Pilih Role--</option>
                  @foreach($role as $roles)
                <option value="{{$roles->id }}"> {{ $roles->display_name }}</option>
                @endforeach  
              </select>
             </div>
          </div> --}}
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
<script src="{{ asset('template') }}/plugins/jquery/jquery.min.js"></script>
  <script>
  $(document).ready(function () {
      isi()
  })

  function isi() {
      $('#tabel').DataTable({
          serverside : true,
          responsive : true,
          ajax : {
              url : "{{route('permission')}}"
          },
          columns:[
                  {
                      "data" :null, "sortable": false,
                      render : function (data, type, row, meta) {
                          return meta.row + meta.settings._iDisplayStart + 1
                      }
                  },
                  {data: 'name', name: 'name'},
                  {data: 'display_name', name: 'display_name'},
                  {data: 'description', name: 'description'},
                  {data: 'aksi', name: 'aksi'}
              ]
      })
  }

  $(document).on('click','#simpan', function (){
                $.ajax({
                url: "{{ route('user.store') }}" ,
                type: 'post',
                data : {
                  username : $('#username').val(),
                  email : $('#email').val(),
                  password : $('#password').val(),
                  role : $('#role').val(),
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
                type: 'post',
                data : { 
                  id : $(this).attr('id'),
                  "_token" :"{{ csrf_token() }}"
                },success : function(res){
                    $('#usernames').val(res.user.name)
                    $('#id').val(res.user.id)
                    $('#emails').val(res.user.email)
                    $('#labelRole').text(res.roles)
                   
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
                  "_token" : "{{ csrf_token() }}"
                },success : function(res){
                  console.log(res);
                    $('#tabel').DataTable().ajax.reload()
                    $('#btn-info').modal('hide');
                    // Swal.fire({
                    // position: 'center',
                    // icon: 'success',
                    // title: 'Data Berhasil Disimpan',
                    // showConfirmButton: false,
                    // timer: 1000
                    // })
                    
                }, error : function (xhr){
                    console.error(xhr);
                }
            })
        })



</script>
@endpush
  @endsection

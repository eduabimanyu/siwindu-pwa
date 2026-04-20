 <!-- Main Content -->
 @extends('layouts.app')
 @section('tittle', 'profile')
 @section('conten')
      <!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Profile</h1>
        </div>
        <div class="section-body">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
              <div class="box">
                  <form action="{{ route('profile.update') }}" method="post" class="form-profil" data-toggle="validator" enctype="multipart/form-data">
                      @csrf
                      <div class="box-body">
                          <div class="alert alert-info alert-dismissible" style="display: none;">
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                              <i class="icon fa fa-check"></i> Perubahan berhasil disimpan
                          </div>
                          <div class="form-group row">
                              <label for="nama" class="col-lg-2 control-label">Nama Lengkap</label>
                              <div class="col-lg-6">
                                  <input type="text" name="nama" class="form-control" id="nama" value="{{ $profil->name }}" required autofocus>
                                  <span class="help-block with-errors"></span>
                              </div>
                          </div>
                          <div class="form-group row">
                              <label for="email" class="col-lg-2 control-label">Email</label>
                              <div class="col-lg-6">
                                  <input type="email" name="email" class="form-control" id="email" value="{{ $profil->email }}" required>
                                  <span class="help-block with-errors"></span>
                              </div>
                          </div>
                        <div class="form-group row">
                            <label for="password" class="col-lg-2 control-label">Password Baru</label>
                            <div class="col-lg-6">
                            <input type="password" class="form-control" id="password" name="password" tabindex="2"required  autocomplete="current-password">
                           <span class="help-block with-errors"></span>
                         </div>
                        </div>
                        <div class="form-group row">
                            <label for="password_confirmation" class="col-lg-2 control-label">Konfirmasi Password</label>
                            <div class="col-lg-6">
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" tabindex="2"required  data-match="#password">
                           <span class="help-block with-errors"></span>
                         </div>
                        </div>
                        @role ('admin')
                        <div class="form-group row">
                            <label for="role" class="col-lg-2 control-label">Role</label>
                            <div class="col-lg-4">
                            <select class="form-control" name="role" id="role" required >
                                <option value="" selected disabled >--Pilih Role--</option>
                                @foreach($role as $roled)
                              <option value="{{$roled->id }}"{{$roles==$roled->id ? 'selected' :'' }}> {{ $roled->display_name }}</option>
                              @endforeach
                            </select>
                        </div>
                        </div>
                        <div class="form-group row">
                            <label for="wisata" class="col-lg-2 control-label">Wisata</label>
                            <div class="col-lg-4">
                            <select class="form-control" name="wisata" id="wisata" required  >
                                <option value="Semua"> Semua </option>
                                @foreach($wisata as $wisata)
                            <option value="{{$wisata->id }}"{{  $profil->wisata==$wisata->id ? 'selected' :'' }}> {{ $wisata->nama_wisata }}</option>
                            @endforeach
                            </select>
                        </div>
                        </div>
                      </div>
                      @endrole
                      <div class="box-footer text-right">
                          <button class="btn btn-sm btn-flat btn-primary"><i class="fa fa-save"></i> Simpan Perubahan</button>
                      </div>
                  </form>
              </div>
          </div>
      </div>
    </section>
</div>
</div>


 @push('js')
 <script src="{{ asset('template') }}/plugins/jquery/jquery.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.5/dist/sweetalert2.all.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>

    $(function () {
        $('.form-profil').on('submit', function (e) {
            if (! e.preventDefault()) {
                $.ajax({
                    url: $('.form-profil').attr('action'),
                    type: $('.form-profil').attr('method'),
                    data: new FormData($('.form-profil')[0]),
                    async: false,
                    processData: false,
                    contentType: false
                })
                .done(response => {
                    $('.alert').fadeIn();
                    setTimeout(function(){ location.reload(); }, 800);
                })
                .fail(errors => {
                    if (errors.status == 422) {
                        alert(errors.responseJSON);
                    } else {
                        alert('Tidak dapat menyimpan data');
                    }
                    return;
                });
            }
        });
    });
</script>
@endpush
 @endsection
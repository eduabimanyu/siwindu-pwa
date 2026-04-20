 <!-- Main Content -->
 @extends('layouts.app')
 @section('tittle', 'Setting')
 @section('conten')
      <!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Setting Web</h1>
          <div class="section-header-breadcrumb">
          <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
          <div class="breadcrumb-item ">Setting</div>
        </div>
        </div>
        <div class="section-body">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
              <div class="box">
                  <form action="{{ route('setting.update') }}" method="post" class="form-setting" data-toggle="validator" enctype="multipart/form-data">
                      @csrf
                      <div class="box-body">
                          <div class="alert alert-info alert-dismissible" style="display: none;">
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                              <i class="icon fa fa-check"></i> Perubahan berhasil disimpan
                          </div>
                          <div class="form-group row">
                              <label for="nama_perusahaan" class="col-lg-2 control-label">Nama Perusahaan</label>
                              <div class="col-lg-6">
                                  <input type="text" name="nama_perusahaan" class="form-control" id="nama_perusahaan" required autofocus>
                                  <span class="help-block with-errors"></span>
                              </div>
                          </div>
                          <div class="form-group row">
                              <label for="telepon" class="col-lg-2 control-label">Telepon</label>
                              <div class="col-lg-6">
                                  <input type="text" name="telepon" class="form-control" id="telepon" required>
                                  <span class="help-block with-errors"></span>
                              </div>
                          </div>
                          <div class="form-group row">
                              <label for="alamat" class="col-lg-2 control-label">Alamat</label>
                              <div class="col-lg-6">
                                  <textarea name="alamat" class="form-control" id="alamat" rows="3" required></textarea>
                                  <span class="help-block with-errors"></span>
                              </div>
                          </div>
                          <div class="form-group row">
                              <label for="path_logo" class="col-lg-2 control-label">Logo Perusahaan</label>
                              <div class="col-lg-4">
                                  <input type="file" name="path_logo" class="form-control" id="path_logo"
                                      onchange="preview('.tampil-logo', this.files[0])">
                                  <span class="help-block with-errors"></span>
                                  <br>
                                  <div class="tampil-logo"></div>
                              </div>
                          </div>
                      </div>
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
 <script>
     $(function () {
         showData();
          $('.form-setting').validator().on('submit', function (e) {
             if (! e.preventDefault()) {
                 $.ajax({
                     url: $('.form-setting').attr('action'),
                     type: $('.form-setting').attr('method'),
                     data: new FormData($('.form-setting')[0]),
                     async: false,
                     processData: false,
                     contentType: false
                 })
                 .done(response => {
                     showData();
                     $('.alert').fadeIn();

                     setTimeout(() => {
                         $('.alert').fadeOut();
                     }, 3000);
                 })
                 .fail(errors => {
                     alert('Tidak dapat menyimpan data');
                     return;
                 });
             }
         });
     });

    function showData() {
        $.get('{{ route('setting.show') }}')
            .done(response => {
                $('[name=nama_perusahaan]').val(response.nama_perusahaan);
                $('[name=telepon]').val(response.telepon);
                $('[name=alamat]').val(response.alamat);
                $('title').text(response.nama_perusahaan + ' | Pengaturan');
                let words = response.nama_perusahaan.split(' ');
                let word  = '';
                words.forEach(w => {
                    word += w.charAt(0);
                });
                $('.logo-mini').text(word);
                $('.logo-lg').text(response.nama_perusahaan);
                $('.tampil-logo').html(`<img src="{{ url('/') }}${response.path_logo}" width="200">`);
                $('[rel=icon]').attr('href', `{{ url('/') }}/${response.path_logo}`);
            })
            .fail(errors => {
                alert('Tidak dapat menampilkan data');
                return;
            });
    }
 </script>
 @endpush
 @endsection
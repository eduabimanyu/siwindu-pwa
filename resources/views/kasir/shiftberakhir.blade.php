 <!-- Main Content -->
 @extends('layouts.app')
 @section('tittle', 'Shift Berakhir')


 @section('conten')
      <!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Shift Berakhir</h1>   
            <div class="section-header-breadcrumb">
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-striped">
                              <thead>
                              </thead>
                              <tbody>
                                <tr>
                                  <td>Nama Kasir</td>
                                  <td class="text-right">{{ $shift->user->name }}</td>
                                </tr>
                                <tr>
                                    <td>Wisata</td>
                                    <td class="text-right">{{ $shift->wisatashift->nama_wisata }}</td>
                                </tr>
                                <tr>
                                    <td>Mulai Shift</td>
                                    <td class="text-right">{{ tanggal_indonesia($shift->created_at) }}</td>
                                </tr>
                                <tr>
                                    <td>Shift Berakhir</td>
                                    <td class="text-right">{{ tanggal_indonesia($shift->updated_at) }}</td>
                                </tr>
                              </tbody>
                            </table>
                            <div class="buttons">
                                <button class="btn btn-primary col-12" onclick="nota('{{ route('shiftnota') }}')">Cetak Laporan Shift</button>
                            </div>
                            <div class="buttons">
                            <a href="{{route('shiftkasir.index')}}" class="btn btn-danger col-12">Tidak, Terima Kasih</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

    <script>

      // tambahkan untuk delete cookie innerHeight terlebih dahulu


        function nota(url, title) {
            popupCenter(url, title, 625, 500);
        }

        function popupCenter(url, title, w, h) {
            const dualScreenLeft = window.screenLeft !==  undefined ? window.screenLeft : window.screenX;
            const dualScreenTop  = window.screenTop  !==  undefined ? window.screenTop  : window.screenY;

            const width  = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
            const height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

            const systemZoom = width / window.screen.availWidth;
            const left       = (width - w) / 2 / systemZoom + dualScreenLeft
            const top        = (height - h) / 2 / systemZoom + dualScreenTop
            const newWindow  = window.open(url, title, 
            `
                scrollbars=yes,
                width  = ${w / systemZoom}, 
                height = ${h / systemZoom}, 
                top    = ${top}, 
                left   = ${left}
            `
            );

            if (window.focus) newWindow.focus();
        }
    </script>
  @endsection

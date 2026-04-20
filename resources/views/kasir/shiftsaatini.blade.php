 <!-- Main Content -->
 @extends('layouts.app')
 @section('tittle', 'shiftsaatini')

 @section('conten')
      <!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Shift Saat Ini</h1>
            <div class="section-header-breadcrumb">
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div id="button">
                            <div class="buttons">
                                <button class="btn btn-outline-primary col-12" id="akhirshift">Akhiri Shift</button>
                            </div>
                            <button class="btn btn-outline-warning col-12" onclick="nota('{{ route('shiftnota') }}')">Cetak Laporan Shift</button>
                            </div>
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
                                    <td>
                                        Item Terjual</td>
                                    <td class="text-right"> <a href="javascript:void(0)" data-toggle="modal" data-target="#btn-tambah" data-id="{{ $shift->id_shift }}">{{ $item }}</td>
                                </tr>
                                <tr>
                                    <td>Item Refund</td>
                                    <td class="text-right">{{ $shift->item_refund }}</td>
                                </tr>
                                <tr>
                                    <td><b>Tunai</b></td>
                                    <td class="text-right"></td>
                                </tr>
                                <tr>
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Saldo Tunai</td>
                                    <td class="text-right"><b>Rp.{{ format_uang($shift->saldo_tunai) }}</b></td>
                                </tr>
                                <tr>
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pembayaran</td>
                                    <td class="text-right">Rp.{{ format_uang($bayartunai) }}</td>
                                </tr>
                                <tr>
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Refund</td>
                                    <td class="text-right">Rp.{{ format_uang($shift->item_refund) }}</td>
                                </tr>
                                <tr>
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pendapatan</td>
                                    <td class="text-right"><b>Rp.{{ format_uang($bayartunai + $shift->saldo_tunai) }}</b></td>
                                </tr>
                                @if (!empty($bayarbank))
                                <tr id="banka">
                                    <td><b>Transfer Bank</b></td>
                                    <td class="text-right"></td>
                                </tr>
                                <tr id="bankb">
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bank</td>
                                    <td class="text-right">Rp.{{ format_uang($bayarbank) }}</td>
                                </tr>
                                <tr id="bankc">
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bank Refund</td>
                                    <td class="text-right">Rp.{{ format_uang(0) }}</td>
                                </tr>
                                <tr id="bankd">
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pendapatan Bank</td>
                                    <td class="text-right"><b>Rp.{{ format_uang($bayarbank) }}</b></td>
                                </tr>
                                @endif
                                @if (!empty($bayarqris ))
                                <tr id="qrisa">
                                    <td><b>QRIS</b></td>
                                    <td class="text-right"></td>
                                </tr>
                                <tr id="qrisb">
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ewallet</td>
                                    <td class="text-right">Rp.{{ format_uang($bayarqris) }}</td>
                                </tr>
                                <tr id="qrisc">
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Qris  Refund</td>
                                    <td class="text-right">Rp.{{ format_uang($shift->item_refund) }}</td>
                                </tr>
                                <tr id="qrisd">
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pendapatan QRIS</td>
                                    <td class="text-right"><b>Rp.{{ format_uang($bayarqris ) }}</b></td>
                                </tr>
                                @endif
                                @if (!empty($bayarewalet ))
                                <tr id="ewaleta">
                                    <td><b>Transfer Ewallet</b></td>
                                    <td class="text-right"></td>
                                </tr>
                                <tr id="ewaletb">
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ewallet</td>
                                    <td class="text-right">Rp.{{ format_uang($bayarewalet) }}</td>
                                </tr>
                                <tr id="ewaletc">
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ewallet Refund</td>
                                    <td class="text-right">Rp.{{ format_uang($shift->item_refund) }}</td>
                                </tr>
                                <tr id="ewaletd">
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pendapatan Ewallet</td>
                                    <td class="text-right"><b>Rp.{{ format_uang($bayarewalet ) }}</b></td>
                                </tr>
                                @endif
                                <tr id="total">
                                    <td><b>Total Pendapatan</b></td>
                                    <td class="text-right"><b>Rp.{{ format_uang ($pendapatan+$shift->saldo_tunai) }}</b></td>
                                </tr>
                              </tbody>
                            </table>
                        </div>
                        <div id="akhir" hidden>
                            <hr class="mt-2 mb-2">
                            <form  class="form-transaksi" method="post" >
                                @csrf
                                 <h6> &ensp;&ensp; JUMLAH UANG YANG DIDAPAT</h6>
                                <div class="form-group">
                                    <div class="col-lg-12">
                                        <input type="text" id="jumlah"  name="uangdidapat" class="form-control" placeholder="Jumlah uang yang didapat" >
                                        <input type="hidden" name="id_shift" class="form-control" value=" {{ $shift->id_shift }}" >
                                    </div>
                                </div>
                                <div class="buttons">
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-outline-primary col-12" id="simpanshift">
                                            Akhiri Shift
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="btn-tambah">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Item Terjual  : {{$item}}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <table class="table table-striped">
                <thead>
                </thead>
                <tbody>
                 @foreach($shiftdetail as $detail) 
                  <tr>
                    <td>{{ $detail->nama_item}}</td>
                    <td class="text-right">{{ $detail->sum }}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
       </div>
    </div>
  </div>
</div>

      @push('js')
      <script src="{{ asset('template') }}/plugins/jquery/jquery.min.js"></script>
      <!-- jQuery UI 1.11.4 -->
      <script src="{{ asset('template') }}/plugins/jquery-ui/jquery-ui.min.js"></script>
    <script>

        $(document).on('click','#akhirshift', function (){
              $('#button').attr('hidden',true)
              $('#banka').attr('hidden',true)
              $('#bankb').attr('hidden',true)
              $('#bankc').attr('hidden',true)
              $('#bankd').attr('hidden',true)
              $('#qrisa').attr('hidden',true)
              $('#qrisb').attr('hidden',true)
              $('#qrisc').attr('hidden',true)
              $('#qrisd').attr('hidden',true)
              $('#ewaleta').attr('hidden',true)
              $('#ewaletb').attr('hidden',true)
              $('#ewaletc').attr('hidden',true)
              $('#ewaletd').attr('hidden',true)
              $('#total').attr('hidden',true)
              $('#akhir').attr('hidden',false)
            })

        $(document).on('submit','form', function (event){
        event.preventDefault();
         Swal.fire({
          title: 'Apakah Anda Yakin Ingin Menyimpan Shift?',
          text: "Pastikan Data Terisi Dengan Benar!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Ya, Simpan!'
          }).then((result) => {
          if (result.isConfirmed) {
              $.ajax({
          url :"{{route('shift.update')}}",
          type: $(this).attr('method'),
            typeData : "JSON",
            data : new FormData(this),
            processData :false,
            contentType: false,
          success: function(res,status){
            if($status = '200'){
              setTimeout(() => {
                  Swal.fire({
                  position: 'center',
                  icon: 'success',
                  title: 'Data Berhasil Disimpan',
                  showConfirmButton: false,
                  timer: 1000
                }).then((res)=>{
                 window.location = '{{ route('shiftberakhir') }}'
                })
            });
          }
      },
      })
          }
          })
      })

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
  @endpush
  @endsection

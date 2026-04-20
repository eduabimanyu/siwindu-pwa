 <!-- Main Content -->
 @extends('layouts.app')
 @section('tittle', 'Detail Transaksi')

 @section('conten')
  <!-- Main Content -->
  <div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>Detail Transaksi ID#{{ $transaksis->id_transaksi }}</h1>
        @role('admin')
        <div class="section-header-breadcrumb">
          <div class="breadcrumb-item active"><a href="{{ route('transaksiadmin.index') }}">transaksi</a></div>
          <div class="breadcrumb-item ">Detail transaksi</div>
        </div>
        @endrole
        @role('kasir')
        <div class="section-header-breadcrumb">
          <div class="breadcrumb-item active"><a href="{{ route('aktivitas.index') }}">Aktivitas</a></div>
          <div class="breadcrumb-item ">Detail transaksi</div>
        </div>
        @endrole
      </div>
      <div class="section-body">
        <div class="invoice">
          <div class="invoice-print">
            <div class="row">
              <div class="col-lg-12">
                <div class="row">
                  <div class="col-md-6">
                    {{ tanggal_indonesia($transaksis->created_at) }}<br>
                    Nama Kasir :  {{ $transaksis->users }}<br>
                   <h5> {{ $transaksis->wisatas }}</h5><br>
                  </div>
                </div>
              </div>
            </div>
            <div class="row mt-4">
              <div class="col-md-12">
                <div class="section-title">Detail Transaksi</div>
                <div class="form-group row">
                  <div class="col-lg-5">
                  <form class="form-item" hidden id="item">
                      @csrf
                      <div class="form-group row">
                             <div class="col-lg-5">
                              <div class="input-group">
                                  <input type="hidden" name="id_transaksi" id="id_transaksi" value="{{ $transaksis->id_transaksi}}">
                                  <input type="hidden" name="id_item" id="id_item">
                                  <input type="hidden" class="form-control" name="kode_item" id="kode_item">
                                  <span class="input-group-btn">
                                      <button onclick="tampilItem()" class="btn btn-info btn-flat" type="button"><i class="fa fa-arrow-right"></i> Pilih item</button>
                                  </span>
                              </div>
                          </div>
                      </div>
                  </form>
               </div>
             </div>
                    @role('admin')
                     <div class="table-responsive">
                    <table class="table table-stiped table-transaksi" id="tabel1" hidden>
                    <thead>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th width="20%">Jumlah</th>
                        <th>Diskon</th>
                        <th>Subtotal</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>
                </div>
                @endrole
                <table class="table table-stiped table-transaksi1" id="tabel" >
                  <thead>
                      <th>Kode</th>
                      <th>Nama</th>
                      <th>Harga</th>
                      <th width="15%">Jumlah</th>
                      <th>Diskon</th>
                      <th>Subtotal</th>
                  </thead>
              </table>
              <div>
                <div class="row mt-4" id="transaksi">
                  <div class="col-lg-8">
                    <div class="invoice-detail-item">
                    <div class="section-title">Jenis Pembayaran</div>
                    <div class="invoice-detail-value"> {{ $transaksis->jenis_pembayaran }}</div>
                    <div class="invoice-detail-value">{{ $transaksis->banks }}</div>
                    <div class="invoice-detail-name">{{ $transaksis->norek }}  {{ $transaksis->an }}</div>
                    <div class="invoice-detail-value">{{ $transaksis->ewalet }}</div>
                    <div class="invoice-detail-name">{{ $transaksis->nomor_hp }}  {{ $transaksis->atas_nama}}</div>
                  </div>
                  </div>
                  <div class="col-lg-4 text-right">
                    <div class="invoice-detail-item">
                      <div class="invoice-detail-name" id="subtotal">Subtotal</div>
                      <div class="invoice-detail-value">Rp. {{ format_uang($transaksis->total_harga) }},-</div>
                    </div>
                    <div class="invoice-detail-item">
                      <div class="invoice-detail-name">Diskon</div>
                      <div class="invoice-detail-value" name="diskon" >{{ $transaksis->diskon }} %</div>
                    </div>
                    <hr class="mt-2 mb-2">
                    <div class="invoice-detail-item">
                      <div class="invoice-detail-name" >Total</div>
                      <div class="invoice-detail-value invoice-detail-value-lg">Rp.{{format_uang($transaksis->bayar) }},-</div>
                    </div>
                  </div>
                </div>
                <div class="row" hidden id="transaksi1">
                  <div class="col-lg-8">
                      <div class="tampil-bayar bg-primary" ></div>
                   </div>
                  <div class="col-lg-4">
                      <form action="{{ route('transaksiadmin.simpan') }}" class="form-transaksi" method="post" id="forms">
                          @csrf
                          <input type="hidden" name="id_transaksi" value="{{ $transaksis->id_transaksi }}">
                          <input type="hidden" name="total" id="total" >
                          <input type="hidden" name="total_item" id="total_item" >
                          <input type="hidden" name="bayar" id="bayar">
                          <input type="hidden" name="diterima" id="diterima" value="{{ $transaksis->diterima}}">
                          <div class="form-group row">
                              <label for="totalrp" class="col-lg-4 control-label">Subtotal</label>
                              <div class="col-lg-8">
                                  <input type="text" id="totalrp" class="form-control" readonly  >
                              </div>
                          </div>
                          <div class="form-group row">
                              <label for="diskon" class="col-lg-4 control-label">Diskon (%)</label>
                              <div class="col-lg-4">
                                  <input type="number" name="diskon" id="diskon" class="form-control" value="{{ $transaksis->diskon }}" readonly>
                              </div>
                          </div>
                          <div class="form-group row">
                              <label for="bayar" class="col-lg-4 control-label">Total</label>
                              <div class="col-lg-8">
                                  <input type="text" id="bayarrp" class="form-control" readonly >
                              </div>
                          </div>
                          <div class="form-group row">
                            <label for="jenis_pembayaran" class="col-lg-4 control-label">Jenis Pembayaran</label>
                            <div class="col-lg-8">
                                <select class="form-control form-control-lg" name="jenis_pembayaran" id="jenis_pembayaran" required  >
                                    <option value="Tunai" {{ $transaksis->jenis_pembayaran ==  'Tunai' ? 'selected' : '' }}>Tunai</option>
                                    <option value="Transfer Bank" {{ $transaksis->jenis_pembayaran == 'Transfer Bank' ? 'selected' : '' }}>Transfer Bank</option>
                                    <option value="Transfer Ewallet" {{ $transaksis->jenis_pembayaran == 'Transfer Ewallet' ? 'selected' : '' }}>Transfer Ewallet</option>
                                    <option value="QRIS" {{ $transaksis->jenis_pembayaran ==  'QRIS' ? 'selected' : '' }}>QRIS</option>
                                </select>
                            </div>
                        </div>


                        <div class="form-group row" id="div_transfer" style="display: none;">
                            <label or="bank" class="col-lg-4 control-label">Pilih Bank</label>
                            <div class="col-lg-8">
                            <select class="form-control form-control-lg" name="id_bank" id="id_bank" >
                                <option value="" selected disabled >--Pilih Bank--</option>
                                @foreach($bank as $bank)
                              <option value="{{$bank->id }}"> {{ $bank->nama_bank  }} | {{ $bank->nomor_rekening }}</option>
                              @endforeach  
                            </select>
                            </div>
                        </div>
                        <div class="alert alert-success alert-dismissible"  id="no_rek" style="display:none;" >
                            <p>No.Rekening : <b id="nomor_rekening"></b> </p>
                            <p>A.n : <b id="atas_nama"></b> </p>
                        </div>
                        <div class="form-group row" id="div_ewalet" style="display: none;">
                            <label or="ewalet" class="col-lg-4 control-label">Pilih E-Wallet</label>
                            <div class="col-lg-8">
                            <select class="form-control form-control-lg" name="id_ewalet" id="id_ewalet" >
                                <option value="" selected disabled >--Pilih Ewallet--</option>
                                @foreach($ewalet as $ewalet)
                              <option value="{{$ewalet->id }}"> {{ $ewalet->nama_ewalet }} | {{ $ewalet->nomor_hp }}</option>
                              @endforeach  
                            </select>
                            </div>
                        </div>
                        <div class="alert alert-primary alert-dismissible"  id="no_hp" style="display:none;" >
                            <p>Nomor Hp : <b id="nomor_hp"></b> </p>
                            <p>A.n : <b id="nama_pemilik"></b> </p>
                        </div>
                          <div class="text-md-right">
                               <a href="{{ url()->previous() }}" class="btn btn-warning btn-icon icon-left" >Kembali</a>
                            <button type="submit" class="btn btn-primary btn-icon icon-left"><i class="fa fa-save"></i> Simpan Transaksi</button>
                          </div>
                      </form>
                  </div>
              </div>
              </div>
            </div>
          </div>
          <hr>

          <div class="text-md-left">
            <div class="float-lg-right mb-lg-0 mb-3">
              <a href="{{ url()->previous() }}" class="btn btn-primary btn-icon icon-left" id="kembali1">Kembali</a>
              @role('admin')
              <button class="btn btn-danger btn-icon icon-left" id="edit"><i class="fas fa-pen"></i>Edit</button>
              @endrole
             </div>
            <button class="btn btn-warning btn-icon icon-left"id="print"  onclick="nota('{{ route('transaksi.notab',$transaksis->id_transaksi) }}')" ><i class="fas fa-print"></i> Cetak Nota</button>
          </div>
        </div>
      </div>
    </section>
  </div>
  <div class="modal fade" id="modal-item" tabindex="-1" role="dialog" aria-labelledby="modal-item">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title">Pilih Item</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-item">
                    <thead>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th><i class="fa fa-cog"></i></th>
                    </thead>
                    <tbody>
                        @foreach ($item as $item)
                        @if ($item->wisata == $transaksis->wisata)
                            <tr>
                                <td><span class="label label-success">{{ $item->kode_item }}</span></td>
                                <td>{{ $item->nama_item }}</td>
                                <td>{{ $item->harga }}</td>
                                <td>
                                    <a href="#" class="btn btn-primary btn-xs btn-flat"
                                        onclick="pilihItem('{{ $item->id_item }}', '{{ $item->kode_item }}')">
                                        <i class="fa fa-check-circle"></i>
                                        Pilih
                                    </a>
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
  @push('js')
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.5/dist/sweetalert2.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <script>

//Print Nota
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



    let table, table2;


    $(function () {
        $('body').addClass('sidebar-collapse');

        table = $('.table-transaksi1').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('detailtransaksi.data',$transaksis->id_transaksi) }}',
            },
            columns: [
                {data: 'kode_item'},
                {data: 'nama_item'},
                {data: 'harga'},
                {data: 'lihatjumlah'},
                {data: 'diskon'},
                {data: 'subtotal'},
            ],

            dom: 'Bfrtip',
            buttons: [
                    'copy','excel', 'pdf', 'print'
                ],
            searching: false,
            bInfo : false,
            bSort: false,
            paginate: false
        })
        });


    $(function () {
        $('body').addClass('sidebar-collapse');

        table = $('.table-transaksi').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('detailtransaksi.data',$transaksis->id_transaksi) }}',
            },
            columns: [
                {data: 'kode_item'},
                {data: 'nama_item'},
                {data: 'harga'},
                {data: 'jumlah'},
                {data: 'diskon'},
                {data: 'subtotal'},
                {data: 'aksi', searchable: false, sortable: false},
            ],
            searching: false,
            bInfo : false,
            bSort: false,
            paginate: false
        })
        .on('draw.dt', function () {
            loadForm($('#diskon').val());
            setTimeout(() => {
                $('#diterima').trigger('input');
            }, 300);
        });
        table2 = $('.table-item').DataTable();

        $(document).on('input', '.quantity', function () {
            let id = $(this).data('id');
            let jumlah = parseInt($(this).val());

            if (jumlah < 1) {
                $(this).val(1);
                alert('Jumlah tidak boleh kurang dari 1');
                return;
            }
            if (jumlah > 10000) {
                $(this).val(10000);
                alert('Jumlah tidak boleh lebih dari 10000');
                return;
            }
                $.post(`{{ url('/transaksidetail') }}/${id}`, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'put',
                    'jumlah': jumlah
                })
                .done(response => {
                    $(this).on('mouseout', function () {
                        table.ajax.reload(() => loadForm($('#diskon').val()));
                    });
                })
                // .fail(errors => {
                //     alert('Tidak dapat menyimpan data');
                //     return;
                // });
        });


        $(document).on('input', '#diskon', function () {
            if ($(this).val() == "") {
                $(this).val(0).select();
            }

            loadForm($(this).val());
        });

        $('#diterima').on('input', function () {
            if ($(this).val() == "") {
                $(this).val(0).select();
            }

            loadForm($('#diskon').val(), $(this).val());
        }).focus(function () {
            $(this).select();
        });

        $('.btn-simpan').on('click', function () {
            $('.form-transaksi').submit();
        });
    });

    function tampilItem() {
            $('#modal-item').modal('show');
        }
    function hideItem() {
        $('#modal-item').modal('hide');
    }
    function pilihItem(id, kode) {
        $('#id_item').val(id);
        $('#kode_item').val(kode);
        hideItem();
        tambahItem();
    }
    function tambahItem() {
            $.post('{{ route('transaksidetail.store') }}', $('.form-item').serialize())
                .done(response => {
                    $('#kode_item').focus();
                    table.ajax.reload(() => loadForm($('#diskon').val()));
                })
                .fail(errors => {
                    alert('Tidak dapat menyimpan data');
                    return;
                });
        }

    function deleteData(url) {
        if (confirm('Yakin ingin menghapus data terpilih?')) {
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    table.ajax.reload(() => loadForm($('#diskon').val()));
                })
                .fail((errors) => {
                    alert('Tidak dapat menghapus data');
                    return;
                });
        }
    }

    function loadForm(diskon = 0) {
            $('#total').val($('.total').text());
            $('#total_item').val($('.total_item').text());
        $.get(`{{ url('/transaksidetail/loadformadmin') }}/${diskon}/${$('.total').text()}`)
            .done(response => {
                $('#totalrp').val('Rp. '+ response.totalrp);
                $('#bayarrp').val('Rp. '+ response.bayarrp);
                $('#bayar').val(response.bayar);
            })
            // .fail(errors => {
            //     alert('Tidak dapat menampilkan data');
            //     return;
            // })
    }


    $(function() {


    $('select#jenis_pembayaran').on('change', function(e) {
        var optionSelected = $("option:selected", this);
        var valueSelected = this.value;
        if (valueSelected == "Tunai") {
            $("div#div_transfer").slideUp('slow');
            $("div#no_rek").slideUp('slow');
            $("div#no_hp").slideUp('slow');
            $("div#div_ewalet").slideUp('slow');
        } else if (valueSelected == "Transfer Bank") {
            $("div#div_transfer").slideDown('slow');
            $("div#no_hp").slideUp('slow');
            $("div#div_ewalet").slideUp('slow');
          } else if (valueSelected == "Transfer Ewallet") {
            $("div#div_ewalet").slideDown('slow');
            $("div#no_rek").slideUp('slow');
            $("div#div_transfer").slideUp('slow');
        } else {
            $("div#no_rek").slideUp('slow');
            $("div#no_hp").slideUp('slow');
            $("div#div_transfer").slideUp('slow');
            $("div#div_ewalet").slideUp('slow');
        }
    });
});

    $(document).on('click','#edit', function (){
              $('#transaksi1').attr('hidden',false)
              $('#transaksi').attr('hidden',true)
              $('#tabel1').attr('hidden',false)
              $('#tabel').attr('hidden',true)
              $('#transaksi').attr('hidden',true)
              $('#print').attr('hidden',true)
              $('#edit').attr('hidden',true)
              $('#kembali1').attr('hidden',true)
              $('#item').attr('hidden',false)
            })
</script>
@endpush
  @endsection

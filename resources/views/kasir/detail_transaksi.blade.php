@extends('layouts.app')
@section('tittle', 'Dashboard')
@push('css')
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600&display=swap" rel="stylesheet">
<style>
    .tampil-bayar {
        font-size: 4em;
        color: white;
        font-family: 'Montserrat', sans-serif;
        text-align: center;
        height: 100px;
    }

    .tampil-terbilang {
        padding: 10px;
        background: #f0f0f0;
    }

    .table-transaksi tbody tr:last-child {
        display: none;
    }

    @media(max-width: 768px) {
        .tampil-bayar {
            font-size: 2em;
            height: 82px;
            padding-top: 2px;
        }
    }
</style>
@endpush
@section('conten')
<div class="main-content">
  <section class="section">
      <div class="section-header">
          <h1>Poin Of Sales</h1>
     </div>
          <div class="card">
              <div class="card-body">
                  <form class="form-item" id="item">
                      @csrf
                      <div class="form-group row">
                             <div class="col-lg-5">
                              <div class="input-group">
                                  <input type="hidden" name="id_transaksi" id="id_transaksi" value="{{ $id_transaksi}}">
                                  <input type="hidden" name="id_item" id="id_item">
                                  <input type="hidden" class="form-control" name="kode_item" id="kode_item">
                                  <span class="input-group-btn">
                                      <button onclick="tampilItem()" class="btn btn-info btn-flat" type="button"><i class="fa fa-arrow-right"></i> Pilih item</button>
                                  </span>
                              </div>
                          </div>
                      </div>
                  </form>
                  <div class="section-body" >
                    <div class="row">
                        <div class="col-12 ">
                                <table class="table table-stiped table-transaksi" id="table">
                                    <thead>
                                        <th>Kode</th>
                                        <th>Nama</th>
                                        <th>Harga</th>
                                        <th width="15%">Jumlah</th>
                                        <th>Diskon</th>
                                        <th>Subtotal</th>
                                        <th width="15%"><i class="fa fa-cog"></i></th>
                                    </thead>
                                </table>
                        </div>
                    </div>
                </div>
                  <div class="row" id="tampilbayar1">
                      <div class="col-lg-8">
                          <div class="tampil-bayar bg-primary" ></div>
                       </div>
                      <div class="col-lg-4">
                          <form  class="form-transaksi" method="post" id="forms">
                              @csrf
                              <input type="hidden" name="id_transaksi" value="{{ $id_transaksi }}">
                              <input type="hidden" name="total" id="total">
                              <input type="hidden" name="total_item" id="total_item">
                              <input type="hidden" name="bayar" id="bayar">
                              {{-- <input type="hidden" name="id_member" id="id_member" value="{{ $memberSelected->id_member }}"> --}}
                              <div class="form-group row">
                                  <label for="totalrp" class="col-lg-4 control-label">Total</label>
                                  <div class="col-lg-8">
                                      <input type="text" id="totalrp" class="form-control" readonly>
                                  </div>
                              </div>
                              <div class="form-group row">
                                  <label for="diskon" class="col-lg-4 control-label">Diskon (%)</label>
                                  <div class="col-lg-4">
                                      <input type="number" name="diskon" id="diskon" class="form-control" 
                                          value="{{ empty($diskon->id_promo) ? $diskon : 0 }}" 
                                          >
                                  </div>
                              </div>
                              <div class="form-group row">
                                  <label for="bayar" class="col-lg-4 control-label">Bayar</label>
                                  <div class="col-lg-8">
                                      <input type="text" id="bayarrp" class="form-control" readonly>
                                  </div>
                              </div>
                              <div class="form-group row">
                                  <label for="diterima" class="col-lg-4 control-label">Diterima</label>
                                  <div class="col-lg-8">
                                      <input type="number" id="diterima" class="form-control" name="diterima" value="{{ $transaksi->diterima ?? 0 }}">
                                  </div>
                              </div>
                              <div class="form-group row">
                                  <label for="kembali" class="col-lg-4 control-label">Kembali</label>

                                  <div class="col-lg-8">
                                      <input type="text" id="kembali" name="kembali" class="form-control" value="0" readonly>
                                  </div>
                              </div>
                              <div class="form-group row">
                                <label for="jenis_pembayaran" class="col-lg-4 control-label">Jenis Pembayaran</label>
                                <div class="col-lg-8">
                                    <select class="form-control form-control-lg" name="jenis_pembayaran" id="jenis_pembayaran" required >
                                        <option value="Tunai">Tunai</option>
                                        <option value="QRIS">QRIS</option>
                                        <option value="Transfer Bank">Transfer Bank</option>
                                        <option value="Transfer Ewallet">Transfer E-Wallet</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row" id="div_transfer" style="display: none;">
                                <label or="bank" class="col-lg-4 control-label">Pilih Bank</label>
                                <div class="col-lg-8">
                                <select class="form-control form-control-lg" name="id_bank" id="id_bank" >
                                    <option value="" selected disabled >--Pilih Bank--</option>
                                    @foreach($bank as $bank)
                                  <option value="{{$bank->id }}"> {{ $bank->nama_bank }}</option>
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
                                  <option value="{{$ewalet->id }}"> {{ $ewalet->nama_ewalet }}</option>
                                  @endforeach  
                                </select>
                                </div>
                            </div>

                            <div class="alert alert-primary alert-dismissible"  id="no_hp" style="display:none;" >
                                <p>Nomor Hp : <b id="nomor_hp"></b> </p>
                                <p>A.n : <b id="nama_pemilik"></b> </p>
                            </div>
                               <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan Transaksi</button>
                            </div>
                          </form>
                      </div>
                  </div>
                  <div class="row" id="selesai" hidden>
                    <div class="col-lg-12">
                        <div class="box">
                            <div class="box-body">
                                <div class="alert alert-success alert-dismissible">
                                    <i class="fa fa-check icon"></i>
                                    Data Transaksi telah selesai.
                                </div>
                            </div>
                            <div class="box-footer">
                                <button class="btn btn-warning btn-flat"onclick="nota('{{ route('transaksi.nota') }}')">Cetak  Nota</button>
                                <a href="{{ route('transaksi.baru') }}" class="btn btn-primary btn-flat">Transaksi Baru</a>
                            </div>
                        </div>
                    </div>
                </div>
              </div>
          </div>
      </div>
  </section>
</div>
      @includeIf('kasir.item')
      @push('js')
      <script src="{{ asset('template') }}/plugins/jquery/jquery.min.js"></script>
      <!-- jQuery UI 1.11.4 -->
      <script src="{{ asset('template') }}/plugins/jquery-ui/jquery-ui.min.js"></script>
      <script>

        let table, table2;

        $(function () {
            $('body').addClass('sidebar-collapse');
            table = $('.table-transaksi').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('transaksi.data', $id_transaksi) }}',
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
                    $.post(`{{ url('/transaksi') }}/${id}`, {
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
            $.post('{{ route('transaksi.store') }}', $('.form-item').serialize())
                .done(response => {
                    $('#kode_item').focus();
                    table.ajax.reload(() => loadForm($('#diskon').val()));
                })
                .fail(errors => {
                    alert('Item Sudah Ada Pada Keranjang');
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

        function loadForm(diskon = 0, diterima = 0) {
            $('#total').val($('.total').text());
            $('#total_item').val($('.total_item').text());
            $.get(`{{ url('/transaksi/loadform') }}/${diskon}/${$('.total').text()}/${diterima}`)
                .done(response => {
                    $('#totalrp').val('Rp. '+ response.totalrp);
                    $('#bayarrp').val('Rp. '+ response.bayarrp);
                    $('#bayar').val(response.bayar);
                    $('.tampil-bayar').text('Bayar: Rp. '+ response.bayarrp);
                    $('.tampil-terbilang').text(response.terbilang);

                    $('#kembali').val('Rp.'+ response.kembalirp);
                    if ($('#diterima').val() != 0) {
                        $('.tampil-bayar').text('Kembali: Rp. '+ response.kembalirp);
                        $('.tampil-terbilang').text(response.kembali_terbilang);
                    }
                })
                .fail(errors => {
                    alert('Tidak dapat menampilkan data');
                    return;
                })
        }


        $(function() {
            $('select#id_bank').on('change', function(e) {
            var optionSelected = $("option:selected", this);
            var valueSelected = this.value;
            $.ajax({
                type:"GET",
                url:"getbank?valueSelected="+valueSelected,
                dataType: "json",
                success: function(data) {
                    console.log(data);
                    $('#no_rek').slideUp('slow').slideDown('slow');
                    $('#atas_nama').html(data.atas_nama);
                    $('#nomor_rekening').html(data.nomor_rekening);
                },
                error: function(data) {
                    console.log(data);
                }
            });
        });

        $('select#id_ewalet').on('change', function(e) {
            var optionSelected = $("option:selected", this);
            var valueSelected = this.value;
            $.ajax({
                type:"GET",
                url:"getewalet?valueSelected="+valueSelected,
                dataType: "json",
                success: function(data) {
                    console.log(data);
                    $('#no_hp').slideUp('slow').slideDown('slow');
                    $('#nama_pemilik').html(data.atas_nama);
                    $('#nomor_hp').html(data.nomor_hp);
                },
                error: function(data) {
                    console.log(data);
                }
            });
        });

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


      $(document).on('submit','form', function (event){
        event.preventDefault();
          $.ajax({
          url :"{{route('transaksi.simpan')}}",
          type: $(this).attr('method'),
            typeData : "JSON",
            data : new FormData(this),
            processData :false,
            contentType: false,
          success: function(res,status){
            window.location = '{{ route('transaksi.selesai') }}'
      }, error : function (xhr){
        Swal.fire({
                    position: 'center',
                    icon: 'warning',
                    title: 'Jumlah Diterima Kurang',
                    showConfirmButton: false,
                    timer: 1000
                    })
                }
      })
          
          
      
    })


    </script>
  @endpush
  @endsection

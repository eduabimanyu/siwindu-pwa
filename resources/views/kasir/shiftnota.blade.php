<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rekap Laporan Shift</title>

    <?php
    $style = '
    <style>
        * {
            font-family: "Merchant Copy", sans-serif;
        }
        p {
            display: block;
            margin: 3px;
            font-size: 11pt;

        }
        table td {
            font-size: 10pt;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }

        @media print {
            @page {
                margin: 0;
                size: 75mm
    ';
    ?>
    <?php
    $style .=
        ! empty($_COOKIE['innerHeight'])
            ? $_COOKIE['innerHeight'] .'mm; }'
            : '}';
    ?>
    <?php
    $style .= '
            html, body {
                width: 70mm;
            }
            .btn-print {
                display: none;
            }
        }
    </style>
    ';
    ?>

    {!! $style !!}
</head>
<body onload="window.print()" onfocus="window.close()">
    <button class="btn-print" style="position: absolute; right: 1rem; top: rem;" onclick="window.print()">Print</button>
    <div class="text-center">
        <h4 style="margin-bottom: 5px;">OBYEK WISATA {{ strtoupper(Auth::user()->id_wisata->nama_wisata) }}</h3>
        <p style="font-size:4vw">{{ strtoupper(Auth::user()->id_wisata->alamat) }}</p>
    </div>
    <br>
    <div class="clear-both" style="clear: both;"></div>
    <p class="text-center">-----------------------------------------------------------</p>
    <p class="text-center"><b>SHIFT PRINT</b></p>
    <p class="text-center">-----------------------------------------------------------</p>
    <div class="clear-both" style="clear: both;">
        <p style="float: left;">Nama</p>
        <p style="float: right">{{ strtoupper(auth()->user()->name) }}</p>
    </div>
    <div class="clear-both" style="clear: both;">
        <p style="float: left;">Mulai Shift</p>
        <p style="float: right">{{ t_indo($shift->created_at) }}</p>
    </div>
    <div class="clear-both" style="clear: both;">
        <p style="float: left;">Akhir Shift</p>
        <p style="float: right">{{ t_indo($shift->updated_at) }}</p>
    </div>
    <div class="clear-both" style="clear: both;">
        <p style="float: left;">Item Terjual</p>
        <p style="float: right">{{$item}}</p>
    </div>
    <div class="clear-both" style="clear: both;">
        <p style="float: left;">Item Refund</p>
        <p style="float: right">{{$shift->item_refund }}</p>
    </div>
    <div class="clear-both" style="clear: both;"></div>
    <p class="text-center">-----------------------------------------------------------</p>
    <p class="text-center"><b>MANAJEMEN KAS</b></p>
    <p class="text-center">-----------------------------------------------------------</p>
    <div class="clear-both" style="clear: both;">
        <p style="float: left;">Saldo Tunai Awal</p>
        <p style="float: right">{{format_uang($shift->saldo_tunai) }}</p>
    </div>
    <div class="clear-both" style="clear: both;">
        <p style="float: left;">Pembayaran Tunai</p>
        <p style="float: right">{{ format_uang($bayartunai)}}</p>
    </div>
    <div class="clear-both" style="clear: both;">
        <p style="float: left;">Pendapatan Tunai</p>
        <p style="float: right">{{ format_uang($shift->saldo_tunai + $bayartunai)}}</p>
    </div>
    <div class="clear-both" style="clear: both;">
        <p style="float: left;">Uang Didapat Dilaci</p>
        <p style="float: right">{{ format_uang($shift->uangdidapat)}}</p>
    </div>
    <div class="clear-both" style="clear: both;">
        <p style="float: left;">Selisih Uang Tunai</p>
        <p style="float: right">{{ format_uang($shift->uangdidapat - ($shift->saldo_tunai + $bayartunai))}}</p>
    </div>
    <div class="clear-both" style="clear: both;">
     <p class="text-center">-----------------------------------------------------------</p>
        <p class="text-center"><b>DETAIL TRANSAKSI</b></p>
    <p class="text-center">-----------------------------------------------------------</p>
    </div>
    <table width="100%" style="border: 0;">
        <tr>
            <td><b>ITEM TERJUAL</b></td>
        </tr>
        @foreach($shiftdetail as $detail) 
        <tr>  
            <td colspan="3">{{  $detail->nama_item }}</td>
        </tr>
        <tr>
            <td>{{  $detail->sum}}</td>
            <td></td>
            <td class="text-right">{{ format_uang( $detail->sumtotal ) }}</td>
        </tr>
        @endforeach
    </table>
    <div class="clear-both" style="clear: both;">
        <p style="float: left;"><b>JUMLAH PENDAPATAN</b></p>
        <p style="float: right"><b>{{ format_uang($bayar)}}</b></p>
    </div>
    <div class="clear-both" style="clear: both;">
    <p class="text-center">-----------------------------------------------------------</p>
        <p class="text-center"><b>DETAIL PEMBAYARAN</b></p>
    <p class="text-center">-----------------------------------------------------------</p>
    </div>
{{-- tunai --}}
    <div class="clear-both" style="clear: both;">
        <p style="float: left;"><b>TUNAI</b></p>
    </div>
    <div class="clear-both" style="clear: both;">
        <p style="float: left;">Penjualan Tunai</p>
        <p style="float: right">{{ format_uang($bayartunai)}}</p>
    </div>
    <div class="clear-both" style="clear: both;">
        <p style="float: left;">Tunai Refund</p>
        <p style="float: right">0</p>
    </div>
    <div class="clear-both" style="clear: both;">
        <p style="float: left;"><b>JUMLAH PENDAPATAN</b></p>
        <p style="float: right"><b>{{ format_uang($bayartunai)}}</b></p>
    </div>
    <div class="clear-both" style="clear: both;">
    <p class="text-center">-----------------------------------------------------------</p>
    </div>
{{-- bank --}}
@if (!empty($bayarbank))
    <div class="clear-both" style="clear: both;">
        <p style="float: left;"><b>Bank</b></p>
    </div>
    <div class="clear-both" style="clear: both;">
        <p style="float: left;">Bank</p>
        <p style="float: right">{{ format_uang($bayarbank)}}</p>
    </div>
    <div class="clear-both" style="clear: both;">
        <p style="float: left;"><b>JUMLAH PENDAPATAN</b></p>
        <p style="float: right"><b>{{ format_uang($bayarbank)}}</b></p>
    </div>
    <div class="clear-both" style="clear: both;">
    <p class="text-center">-----------------------------------------------------------</p>
    </div>
@endif
{{-- qris --}}
@if (!empty($bayarqris))
    <div class="clear-both" style="clear: both;">
        <p style="float: left;"><b>QRIS</b></p>
    </div>
    <div class="clear-both" style="clear: both;">
        <p style="float: left;">QRIS</p>
        <p style="float: right">{{ format_uang($bayarqris)}}</p>
    </div>
    <div class="clear-both" style="clear: both;">
        <p style="float: left;"><b>JUMLAH PENDAPATAN</b></p>
        <p style="float: right"><b>{{ format_uang($bayarqris)}}</b></p>
    </div>
    <div class="clear-both" style="clear: both;">
    <p class="text-center">-----------------------------------------------------------</p>
    </div>
@endif
{{-- ewalet --}}
@if (!empty($bayarewalet ))
<div class="clear-both" style="clear: both;">
    <p style="float: left;"><b>EWALET</b></p>
</div>
<div class="clear-both" style="clear: both;">
    <p style="float: left;">Ewalet</p>
    <p style="float: right">{{ format_uang($bayarewalet)}}</p>
</div>
<div class="clear-both" style="clear: both;">
    <p style="float: left;"><b>JUMLAH PENDAPATAN</b></p>
    <p style="float: right"><b>{{ format_uang($bayarewalet)}}</b></p>
</div>
<div class="clear-both" style="clear: both;">
    <p class="text-center">-----------------------------------------------------------</p>
</div>
@endif
<div class="clear-both" style="clear: both;">
    <p style="float: left;"><b>TOTAL TRANSAKSI</b></p>
    <p style="float: right"><b>{{ format_uang($bayarbank +$bayarqris + $bayarewalet + $bayartunai)}}</b></p>
</div>
<div class="clear-both" style="clear: both;">
   <p class="text-center">.</p>
      <p class="text-center">.</p>
         <p class="text-center">.</p>
           <p class="text-center">.</p>
</div>
 <script>
        let body = document.body;
        let html = document.documentElement;
        let height = Math.max(
                body.scrollHeight, body.offsetHeight,
                html.clientHeight, html.scrollHeight, html.offsetHeight
            );

        document.cookie = "innerHeight=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        document.cookie = "innerHeight="+ ((height + 50) * 0.264583);
    </script>
</body>
</html>
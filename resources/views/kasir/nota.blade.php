<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Nota</title>

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
            font-size: 12pt;
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
<body onload="window.print()"  onfocus="window.close()">
    <button class="btn-print" style="position: absolute; right: 1rem; top: rem;" onclick="window.print()">Print</button>
    <div class="text-center">
        <h4 style="margin-bottom: 5px;">OBYEK WISATA {{ strtoupper(Auth::user()->id_wisata->nama_wisata) }}</h4>
        <p style="font-size:4vw">{{ strtoupper(Auth::user()->id_wisata->alamat) }}</p>
        <p style="font-size:4vw">{{ strtoupper(Auth::user()->id_wisata->no_hp) }}</p>
    </div>
    
    <br>
    <div>
        <p style="float: left;">{{ tanggal_indo($transaksi->created_at) }}</p>
         <p style="float: right;">{{ jam_indo($transaksi->created_at) }}</p>
    </div>
        <br>
       <div class="clear-both" style="clear: both;"></div>
       <p style="float: left">Kasir</p>
       <p style="float: right">{{ strtoupper(auth()->user()->name) }}</p>
    <div class="clear-both" style="clear: both;"></div>
    <p style="float: left">Order ID</p>
    <p style="float: right">No: {{ tambah_nol_didepan($transaksi->id_transaksi, 10) }}</p>
    <div class="clear-both" style="clear: both;"></div>
    <p style="float: left">Pembayaran</p>
    <p style="float: right">{{$transaksi->jenis_pembayaran }}</p>
    <p class="text-center">=================================</p>
    <table width="100%" style="border: 0;">
        @foreach ($detail as $item)
            <tr>
                <td colspan="3"><b>{{ $item->item->nama_item }}</b></td>
            </tr>
            <tr>
                <td>{{ $item->jumlah }} x {{ format_uang($item->harga) }}</td>
                <td></td>
                <td class="text-right">{{ format_uang($item->jumlah * $item->harga) }}</td>
            </tr>
        @endforeach
    </table>
    <p class="text-center">=================================</p>

    <table width="100%" style="border: 0;">
        <tr>
            <td><b>Subtotal:</b></td>
            <td class="text-right">Rp.{{ format_uang($transaksi->total_harga) }}</td>
        </tr>
        <tr>
            <td>Diskon:</td>
            <td class="text-right">{{ format_uang($transaksi->diskon) }} %</td>
        </tr>
        <tr>
            <td><b>Total Bayar:</b></td>
            <td class="text-right">Rp.{{ format_uang($transaksi->bayar) }}</td>
        </tr>
        <tr>
            <td>Diterima:</td>
            <td class="text-right">Rp.{{ format_uang($transaksi->diterima) }}</td>
        </tr>
        <tr>
            <td>Kembali:</td>
            <td class="text-right">Rp.{{ format_uang($transaksi->diterima - $transaksi->bayar) }}</td>
        </tr>
    </table>
    <p class="text-center">=================================</p>
    <p class="text-center">-- TERIMA KASIH --</p>

    <br>
    <br>
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
</body >
</html>
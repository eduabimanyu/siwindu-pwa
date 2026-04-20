<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Member;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Item;
use App\Models\Ewalet;
use App\Models\Promo;
use App\Models\Bank;
use App\Models\User;
use App\Models\Shiftdetail;
use App\Models\Shift;
use App\Models\Setting;
use Illuminate\Http\Request;

class DetailTransaksiController extends Controller
{

    public function index()
    {
        $item = Item::orderBy('nama_item')->get();
        $member = Member::orderBy('nama')->get();
        $diskon = Promo::first()->diskon ?? 0;
        $ewalet = Ewalet::orderBy('nama_ewalet')->get();
        $bank = Bank::orderBy('nama_bank')->get();
        // Cek apakah ada transaksi yang sedang berjalan
        if ($id_transaksi = session('id_transaksi')) {
            $transaksi = Transaksi::find($id_transaksi);
            $memberSelected = $transaksi->member ?? new Member();
            return view('kasir.detail_transaksi', compact('item', 'member',  'diskon', 'id_transaksi', 'transaksi', 'memberSelected','ewalet','bank'));
        } else {
            if (auth()->user()->hasRole('kasir')) {
                return redirect()->route('transaksi.baru');
            } else {
                return redirect()->route('dashboardkasir');
            }
        }
    }

    public function data($id)
    {
        $detail = DetailTransaksi::with('item')
            ->where('id_transaksi', $id)
            ->get();

        $data = array();
        $total = 0;
        $total_item = 0;   

        foreach ($detail as $item) {
            $row = array();
            $row['kode_item'] = '<span class="label label-success">'. $item->item['kode_item'] .'</span';
            $row['nama_item'] = $item->item['nama_item'];
            $row['harga']  = 'Rp. '. number_format($item->harga);
            $row['jumlah']      = '<input type="number" class="form-control input-sm quantity" data-id="'. $item->id_transaksi_detail .'" value="'. $item->jumlah .'">';
            $row['lihatjumlah']  = $item->jumlah;
            $row['diskon']      = $item->diskon . '%';
            $row['subtotal']    = 'Rp. '. number_format($item->subtotal);
            $row['aksi']        = '<div class="btn-group">
                                    <button onclick="deleteData(`'. route('transaksi.destroy', $item->id_transaksi_detail) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                                </div>';
            $data[] = $row;

            $total += $item->harga * $item->jumlah - (($item->diskon * $item->jumlah) / 100 * $item->harga);;
            $total_item += $item->jumlah;
        }
        $data[] = [
            'kode_item' => '
                <div class="total hide">'. $total .'</div>
                <div class="total_item hide">'. $total_item .'</div>',
            'nama_item' => '',
            'harga'     => '',
            'jumlah'      => '',
            'lihatjumlah' => '',
            'diskon'      => '',
            'subtotal'    => '',
            'aksi'        => '',
        ];

        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['aksi', 'kode_item', 'jumlah'])
            ->make(true);
    }

    public function detail($id)
    {
        $detail = DetailTransaksi::with('item')
            ->where('id_transaksi', $id)
            ->get();

        $data = array();
        $total = 0;
        $total_item = 0;

        foreach ($detail as $item) {
            $row = array();
            $row['kode_item'] = '<span class="label label-success">'. $item->item['kode_item'] .'</span>';
            $row['nama_item'] = $item->item['nama_item'];
            $row['harga']  = 'Rp. '. number_format($item->harga);
            $row['jumlah']      = '<input type="number" class="form-control input-sm quantity" data-id="'. $item->id_transaksi_detail .'" value="'. $item->jumlah .'">';
            $row['lihatjumlah']  = $item->jumlah;
            $row['diskon']      = $item->diskon . '%';
            $row['subtotal']    = 'Rp. '. number_format($item->subtotal);
            $row['aksi']        = '<div class="btn-group">
                                    <button onclick="deleteData(`'. route('transaksidetail.destroy', $item->id_transaksi_detail) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                                </div>';
            $data[] = $row;

            $total += $item->harga * $item->jumlah - (($item->diskon * $item->jumlah) / 100 * $item->harga);;
            $total_item += $item->jumlah;
        }
        $data[] = [
            'kode_item' => '',
            'jumlah' => '<div class="total " hidden>'. $total .'</div>
                <div class="total_item " hidden>'. $total_item .'</div>',
            'nama_item' => '',
            'harga'     => '',
            'lihatjumlah' => '',
            'diskon'      => '',
            'subtotal'    => '',
            'aksi'        => '',
        ];
        return datatables()
            ->of($data)
            ->rawColumns(['aksi', 'kode_item', 'jumlah'])
            ->make(true);
    }
    public function store(Request $request)
    {
        $user = User::where('id', auth()->id())->first();
        $shift= Shift::where('status','0')->where('kasir', Auth::user()->id)->first();
        $transaksi = Transaksi::where('id_transaksi', $request->id_transaksi)->first();
        $detailtransaksi = DetailTransaksi::where('id_transaksi', $request->id_transaksi)->where('id_item', $request->id_item)->first();
        $item = Item::where('id_item', $request->id_item)->first();
        if ($detailtransaksi>1) {
            return response()->json('Data gagal disimpan', 400);
        }

        $detail = new DetailTransaksi();
        $detail->id_transaksi = $request->id_transaksi;
        $detail->id_wisata = $item->wisata;
        $detail->id_kategori = $item->kategori;
        $detail->id_item = $item->id_item;
        $detail->harga = $item->harga;
        $detail->jumlah =  1;
        $detail->diskon = $item->diskon;
        $detail->subtotal = $item->harga - ($item->diskon / 100 * $item->harga);
        $detail->save();

        $shiftdetail = new Shiftdetail();
        $shiftdetail->id_detailtransaksi = $detail->id_transaksi_detail;
        $shiftdetail->id_wisata = $item->wisata;
        $shiftdetail->id_transaksi = $detail->id_transaksi;
        $shiftdetail->id_shift = $user->id_shift;
        $shiftdetail->id_kasir = Auth::user()->id ;
        $shiftdetail->id_item = $item->id_item;
        $shiftdetail->harga = $item->harga;
        $shiftdetail->jumlah =  1;
        $shiftdetail->diskon = $item->diskon;
        $shiftdetail->subtotal = $item->harga - ($item->diskon / 100 * $item->harga);
        $shiftdetail->jenis_pembayaran = null;
        $shiftdetail->status = 0;
        $shiftdetail->pembayaran_refund= 0;

        $shiftdetail->save();

        return response()->json('Data berhasil disimpan', 200);
    }
    public function update(Request $request, $id)
    {
        $detail = DetailTransaksi::find($id);
        $detail->jumlah = $request->jumlah;
        $detail->subtotal = $detail->harga * $request->jumlah - (($detail->diskon * $request->jumlah) / 100 * $detail->harga);;
        $detail->update();

        $shiftdetail = Shiftdetail::where('id_detailtransaksi',$id)->first();
        $shiftdetail->jumlah = $request->jumlah;
        $shiftdetail->subtotal = $detail->harga * $request->jumlah - (($detail->diskon * $request->jumlah) / 100 * $detail->harga);;
        $shiftdetail->update();

    }

    public function destroy($id)
    {
        $detail = DetailTransaksi::find($id);
        $detail->delete();
        $shiftdetail = Shiftdetail::where('id_detailtransaksi',$id);
        $shiftdetail->delete();
        return response(null, 204);
    }


    public function loadForm($diskon = 0, $total = 0, $diterima = 0)
    {
        $bayar   = $total - ($diskon / 100 * $total);
        $kembali = ($diterima != 0) ? $diterima - $bayar : 0;
        $data    = [
            'totalrp' => number_format($total),
            'bayar' => $bayar,
            'bayarrp' => number_format($bayar),
            'kembalirp' => number_format($kembali),
        ];

        return response()->json($data);
    }

    public function loadFormadmin($diskon = 0, $total = 0)
    {
        $bayar   = $total - ($diskon / 100 * $total);
        $data    = [
            'totalrp' => number_format($total),
            'bayarrp' => number_format($bayar),
            'bayar' => $bayar,
        ];

        return response()->json($data);
    }
}

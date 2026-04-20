<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Kategori;
use App\Models\Wisata;
use App\Models\Item;
use App\Models\Promo;
use App\Models\Bank;
use App\Models\Ewalet;
use App\Models\Transaksi;
use App\Models\Setting;
use App\Models\Shift;
use App\Models\Shiftdetail;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use App\Exports\TransaksiExport;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use DataTables;

class TransaksiController extends Controller
{
    public function index()
    {
        $wisata = Wisata::select('id', 'nama_wisata')->get();
        return view('page.transaksi', compact('wisata'));

    }

    /**
     * Get transactions data for API (JSON response)
     */
    public function data(Request $request)
    {
        // Check if this is an API call
        if ($request->expectsJson() || $request->is('api/*')) {
            $query = Transaksi::with('user')
                ->orderBy('created_at', 'desc');

            // Filter by authenticated user role
            if (auth()->check()) {
                $user = auth()->user();
                $userRoles = $user->roles->pluck('name')->toArray();

                // Only filter by user_id for Kasir role
                // Admin, Manager, Keuangan can see all transactions
                if (in_array('kasir', array_map('strtolower', $userRoles))) {
                    $query->where('id_user', auth()->id());
                }
            }

            $transactions = $query->get();

            return response()->json($transactions);
        }

        // Original DataTables response for web interface
        // ... existing code for DataTables
    }

    public function tunai(Request $request)
    {
        if (!empty($request->filter_wisata)) {
            $transaksi = Transaksi::join1()
                ->where('transaksis.wisata', [$request->filter_wisata])->where('jenis_pembayaran', 'Tunai')
                ->get();
        } else {
            $transaksi = Transaksi::join1()->where('jenis_pembayaran', 'Tunai')
                ->get();
        }
        return datatables()
            ->of($transaksi)
            ->addIndexColumn()
            ->addColumn('tanggal', function ($transaksi) {
                return tanggal_indonesia($transaksi->created_at, false);
            })

            ->addColumn('aksi', function ($transaksi) {
                return '
                <div class="btn-group">
                    <a href="' . route('page.transaksidetail', $transaksi->id_transaksi) . '" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i></a>
                    <button onclick="deleteData(`' . route('transaksiadmin.destroy', $transaksi->id_transaksi) . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi', 'kode_member'])
            ->make(true);
    }
    public function bank(Request $request)
    {
        if (!empty($request->filter_wisata)) {
            $transaksi = Transaksi::join()
                ->where('transaksis.wisata', [$request->filter_wisata])->where('jenis_pembayaran', 'Transfer Bank')
                ->get();
        } else {
            $transaksi = Transaksi::join()->where('jenis_pembayaran', 'Transfer Bank')
                ->get();
        }
        return datatables()
            ->of($transaksi)
            ->addIndexColumn()
            ->addColumn('tanggal', function ($transaksi) {
                return tanggal_indonesia($transaksi->created_at, false);
            })

            ->addColumn('aksi', function ($transaksi) {
                return '
                <div class="btn-group">
                    <a href="' . route('page.transaksidetail', $transaksi->id_transaksi) . '" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i></a>
                    <button onclick="deleteData(`' . route('transaksiadmin.destroy', $transaksi->id_transaksi) . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi', 'kode_member'])
            ->make(true);
    }
    public function ewalet(Request $request)
    {
        if (!empty($request->filter_wisata)) {
            $transaksi = Transaksi::join()
                ->where('transaksis.wisata', [$request->filter_wisata])->where('jenis_pembayaran', 'Transfer Ewallet')
                ->get();
        } else {
            $transaksi = Transaksi::join()->where('jenis_pembayaran', 'Transfer Ewallet')
                ->get();
        }
        return datatables()
            ->of($transaksi)
            ->addIndexColumn()
            ->addColumn('tanggal', function ($transaksi) {
                return tanggal_indonesia($transaksi->created_at, false);
            })

            ->addColumn('aksi', function ($transaksi) {
                return '
                <div class="btn-group">
                    <a href="' . route('page.transaksidetail', $transaksi->id_transaksi) . '" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i></a>
                    <button onclick="deleteData(`' . route('transaksiadmin.destroy', $transaksi->id_transaksi) . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi', 'kode_member'])
            ->make(true);
    }
    public function detail($id)
    {
        // Check if this is an API call
        if (request()->expectsJson() || request()->is('api/*')) {
            $transaksi = Transaksi::with('user')->find($id);

            if (!$transaksi) {
                return response()->json(['message' => 'Transaction not found'], 404);
            }

            $detail_transaksi = DetailTransaksi::with('item')
                ->where('id_transaksi', $id)
                ->get();

            return response()->json([
                'transaction' => $transaksi,
                'items' => $detail_transaksi
            ]);
        }

        // Original web view response
        $item = Item::orderBy('nama_item')->get();
        $diskon = Promo::first()->diskon ?? 0;
        $bank = Bank::all();
        $ewalet = Ewalet::all();
        $id_transaksi = session('id_transaksi');
        $transaksis = Transaksi::join()
            ->where('transaksis.id_transaksi', $id)
            ->first();

        $detail_transaksi = DetailTransaksi::join()
            ->where('detail_transaksi.id_transaksi', $id)
            ->get();

        $data = array(
            'detail_transaksi' => $detail_transaksi,
            'transaksis' => $transaksis
        );
        return view('page.transaksidetail', $data, compact('transaksis', 'diskon', 'detail_transaksi', 'id_transaksi', 'bank', 'ewalet', 'item'));
    }
    public function belum($id)
    {
        $diskon = Promo::first()->diskon ?? 0;
        $item = Item::orderBy('nama_item')->get();
        $bank = Bank::all();
        $ewalet = Ewalet::all();
        $id_transaksi = session('id_transaksi');
        $transaksis = Transaksi::join()
            ->where('transaksis.id_transaksi', $id)
            ->first();

        $detail_transaksi = DetailTransaksi::join()
            ->where('detail_transaksi.id_transaksi', $id)
            ->get();

        $data = array(
            'detail_transaksi' => $detail_transaksi,
            'transaksis' => $transaksis
        );
        return view('kasir.transaksibelum', $data, compact('transaksis', 'diskon', 'detail_transaksi', 'id_transaksi', 'bank', 'ewalet', 'item'));
    }
    public function belumselesai(Request $request)
    {
        if (request()->ajax()) {
            if (!empty($request->wisata)) {
                $transaksi = Transaksi::where('transaksis.status', 'Belum')->where('transaksis.wisata', [$request->wisata])->get();
            } else {
                $transaksi = Transaksi::where('transaksis.status', 'Belum')->get();
            }
            return datatables()
                ->of($transaksi)
                ->addColumn('tanggal', function ($transaksi) {
                    return tanggal_indo($transaksi->created_at, false);
                })
                ->addColumn('jam', function ($transaksi) {
                    return jam_indo($transaksi->created_at, false);
                })
                ->addColumn('aksi', function ($transaksi) {
                    return '
            <div class="btn-group">
                <a href="' . route('page.transaksibelum', $transaksi->id_transaksi) . '" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i></a>
                <button onclick="deleteData(`' . route('transaksikasir.destroy', $transaksi->id_transaksi) . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
            </div>
            ';
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
        return view('kasir.belumselesai');
    }
    public function count(Request $request)
    {
        if (request()->ajax()) {

            if (!empty($request->filter_wisata && $request->start_date === $request->end_date)) {
                $transaksi = Transaksi::where('transaksis.wisata', [$request->filter_wisata])
                    ->whereDate('transaksis.created_at', [$request->start_date])->where('transaksis.status', 'Selesai')
                    ->count();
            } else if (!empty($request->filter_wisata && $request->start_date)) {
                $transaksi = Transaksi::where('transaksis.wisata', [$request->filter_wisata])->whereDate('transaksis.created_at', '>=', [$request->start_date])->whereDate('transaksis.created_at', '<=', [$request->end_date])->where('transaksis.status', 'Selesai')
                    ->count();
            } else {
                $transaksi = Transaksi::whereDate('transaksis.created_at', '>=', [$request->start_date])->whereDate('transaksis.created_at', '<=', [$request->end_date])->where('transaksis.status', 'Selesai')
                    ->count();
            }

            return response()->json($transaksi);

        }

    }
    public function pendapatan(Request $request)
    {
        if (request()->ajax()) {
            if (!empty($request->filter_wisata && $request->start_date === $request->end_date)) {
                $pendapatan = Transaksi::where('transaksis.wisata', [$request->filter_wisata])
                    ->whereDate('transaksis.created_at', [$request->start_date])->where('transaksis.status', 'Selesai')
                    ->sum('total_harga');
            } else if (!empty($request->filter_wisata && $request->start_date)) {
                $pendapatan = Transaksi::where('transaksis.wisata', [$request->filter_wisata])->whereDate('transaksis.created_at', '>=', [$request->start_date])->whereDate('transaksis.created_at', '<=', [$request->end_date])->where('transaksis.status', 'Selesai')
                    ->sum('total_harga');
            } else {
                $pendapatan = Transaksi::whereDate('transaksis.created_at', '>=', [$request->start_date])->whereDate('transaksis.created_at', '<=', [$request->end_date])->where('transaksis.status', 'Selesai')
                    ->sum('total_harga');
            }

            return response()->json(format_uang($pendapatan));

        }

    }
    public function show(Request $request)
    {

        if (!empty($request->filter_wisata) && ($request->start_date)) {
            if (!empty($request->filter_wisata) && ($request->start_date === $request->end_date)) {
                $transaksi = Transaksi::join1()->where('transaksis.wisata', [$request->filter_wisata])
                    ->whereDate('transaksis.created_at', [$request->start_date])->where('transaksis.status', 'Selesai')
                    ->get();
            } else {
                $transaksi = Transaksi::join1()->where('transaksis.wisata', [$request->filter_wisata])->whereDate('transaksis.created_at', '>=', [$request->start_date])->whereDate('transaksis.created_at', '<=', [$request->end_date])->where('transaksis.status', 'Selesai')
                    ->get();
            }
        } else {
            $transaksi = Transaksi::join1()->whereDate('transaksis.created_at', '>=', [$request->start_date])->whereDate('transaksis.created_at', '<=', [$request->end_date])->where('transaksis.status', 'Selesai')
                ->get();
        }
        return datatables()
            ->of($transaksi)
            ->addIndexColumn()
            ->addColumn('tanggal', function ($transaksi) {
                return tanggal_indonesia($transaksi->created_at, false);
            })

            ->addColumn('aksi', function ($transaksi) {
                return '
            <div class="btn-group">
                <a href="' . route('page.transaksidetail', $transaksi->id_transaksi) . '" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i></a>
                <button onclick="deleteData(`' . route('transaksiadmin.destroy', $transaksi->id_transaksi) . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
            </div>
            ';
            })
            ->rawColumns(['aksi', 'kode_member'])
            ->make(true);

    }
    public function shift()
    {


        $wisata = Wisata::select('id', 'nama_wisata')->get();
        $transaksi = Transaksi::all();
        $pendapatan = Transaksi::sum('total_harga');
        if (request()->ajax()) {
            if (!empty($request->filter_wisata)) {
                $transaksi = Transaksi::join()
                    ->where('transaksis.wisata', [$request->filter_wisata])
                    ->get();
            } else {
                $transaksi = Transaksi::join()
                    ->get();
            }
            return datatables()->of($transaksi)
                ->addColumn('aksi', function ($transaksi) {
                    $button = " <button class='edit btn  btn-warning' id='" . $transaksi->id . "' >Edit</button>";
                    $button .= " <button class='hapus btn  btn-danger' id='" . $transaksi->id . "' >Detail</button>";
                    return $button;
                })
                ->rawColumns(['aksi'])
                ->make(true);

        }

        return view('page.transaksi', compact('wisata', 'pendapatan', 'transaksi'));
    }
    public function loadForm($diskon = 0, $total = 0, $diterima = 0)
    {
        $bayar = $total - ($diskon / 100 * $total);
        $kembali = ($diterima != 0) ? $diterima - $bayar : 0;
        $data = [
            'totalrp' => format_uang($total),
            'bayar' => $bayar,
            'bayarrp' => format_uang($bayar),
            'terbilang' => ucwords(terbilang($bayar) . ' Rupiah'),
            'kembalirp' => format_uang($kembali),
            'kembali_terbilang' => ucwords(terbilang($kembali) . ' Rupiah'),
        ];

        return response()->json($data);
    }
    public function create()
    {

        $transaksi = new Transaksi();
        $transaksi->id_member = null;
        $transaksi->wisata = Auth::user()->wisata;
        $transaksi->total_item = 0;
        $transaksi->total_harga = 0;
        $transaksi->diskon = 0;
        $transaksi->bayar = 0;
        $transaksi->diterima = 0;
        $transaksi->jenis_pembayaran = null;
        $transaksi->bank = 0;
        $transaksi->ewalet = 0;
        $transaksi->id_user = auth()->id();
        $transaksi->status = 'Belum';
        $transaksi->shift = 0;
        $transaksi->save();

        session(['id_transaksi' => $transaksi->id_transaksi]);
        return redirect()->route('transaksi.index');
    }
    public function store(Request $request)
    {
        // Check if this is an API call from POS (new format)
        if ($request->expectsJson() || $request->is('api/*')) {
            return $this->storeFromPOS($request);
        }

        // Original web-based store logic
        $transaksi = Transaksi::findOrFail($request->id_transaksi);
        $transaksi->id_member = $request->id_member;
        $transaksi->total_item = $request->total_item;
        $transaksi->total_harga = $request->total;
        $transaksi->diskon = $request->diskon;
        $transaksi->bayar = $request->bayar;
        $transaksi->diterima = $request->diterima;
        $transaksi->jenis_pembayaran = $request->jenis_pembayaran;
        $transaksi->bank = $request->id_bank;
        $transaksi->ewalet = $request->id_ewalet;
        $transaksi->status = 'Selesai';
        $transaksi->shift = 0;
        $transaksi->update();

        $detail = DetailTransaksi::where('id_transaksi', $transaksi->id_transaksi)->get();

        foreach ($detail as $item) {
            $item->diskon = $item->diskon;
            $item->update();
        }
        $shift = Shift::where('status', '0')->where('kasir', $transaksi = auth()->id())->get();
        foreach ($shift as $shift1) {
            $shift1->item_terjual = $shift1->item_terjual + $request->total_item;
            $shift1->pendapatan = $shift1->pendapatan + $request->bayar;
            $shift1->update();

            $shiftdetail = Shiftdetail::where('id_shift', $shift1->id_shift)->where('id_transaksi', $request->id_transaksi)->where('id_kasir', $shift1 = auth()->id())->get();
            foreach ($shiftdetail as $shiftdetail1) {
                $shiftdetail1->jenis_pembayaran = $request->jenis_pembayaran;
                $shiftdetail1->status = 1;
                $shiftdetail1->update();
            }

        }



        $simpan = $request->diterima < $request->bayar;
        if (!$simpan) {
            return response()->json(['text' => 'Data Berhasil Disimpan'], 200);
        } else {
            return response()->json(['text' => 'Data Gagal Disimpan'], 400);
        }

    }

    /**
     * Store transaction from POS (API format)
     */
    protected function storeFromPOS(Request $request)
    {
        try {
            \Log::info('POS Transaction Request:', $request->all());

            // Validate required fields
            if (!$request->wisata || !$request->kasir) {
                return response()->json([
                    'message' => 'Missing required fields: wisata or kasir'
                ], 400);
            }

            $shiftId = $request->id_shift;
            if (!$shiftId) {
                return response()->json([
                    'message' => 'No active shift. Please start a shift first.'
                ], 400);
            }

            if (!$request->items || count($request->items) === 0) {
                return response()->json([
                    'message' => 'No items in transaction'
                ], 400);
            }

            // Create transaction
            $transaksi = new Transaksi();
            $transaksi->wisata = $request->wisata;
            $transaksi->id_user = $request->kasir; // kasir ID goes to id_user field
            $transaksi->shift = $shiftId; // Use 'shift' field (INT) as per database schema
            $transaksi->total_harga = $request->total_harga;
            $transaksi->jenis_pembayaran = $request->jenis_pembayaran;
            $transaksi->nama_pelanggan = $request->nama_pelanggan ?? null; // Optional customer name
            $transaksi->status = $request->status ?? 'Selesai';

            // Set default values for optional fields
            $transaksi->id_member = null;
            $transaksi->diskon = 0;
            $transaksi->total_item = count($request->items); // Calculate total items

            // Payment specific fields
            if ($request->jenis_pembayaran === 'Tunai') {
                $transaksi->bayar = $request->bayar ?? $request->total_harga;
                $transaksi->diterima = $request->bayar ?? $request->total_harga;
            } elseif ($request->jenis_pembayaran === 'Transfer Bank') {
                $transaksi->bank = $request->bank_id ?? 0;
                $transaksi->bayar = $request->total_harga;
                $transaksi->diterima = $request->total_harga;
            } elseif ($request->jenis_pembayaran === 'Transfer Ewallet') {
                $transaksi->ewalet = $request->ewalet_id ?? 0;
                $transaksi->bayar = $request->total_harga;
                $transaksi->diterima = $request->total_harga;
            } elseif ($request->jenis_pembayaran === 'QRIS') {
                $transaksi->bayar = $request->total_harga;
                $transaksi->diterima = $request->total_harga;
            }

            $transaksi->save();
            \Log::info('Transaction created:', ['id' => $transaksi->id_transaksi]);

            // Create transaction details
            $totalItems = 0;
            foreach ($request->items as $item) {
                $modelItem = \App\Models\Item::find($item['id_item']);
                
                $detail = new DetailTransaksi();
                $detail->id_transaksi = $transaksi->id_transaksi;
                $detail->id_wisata = $modelItem ? $modelItem->wisata : $request->wisata;
                $detail->id_kategori = $modelItem ? $modelItem->kategori : 0;
                $detail->id_item = $item['id_item'];
                $detail->harga = $item['harga'];
                $detail->jumlah = $item['jumlah'];
                $detail->subtotal = $item['subtotal'];
                $detail->diskon = 0;
                $detail->save();

                $totalItems += $item['jumlah'];

                // Create shift detail record for each item
                $shiftdetail = new Shiftdetail();
                $shiftdetail->id_detailtransaksi = $detail->id_transaksi_detail ?? 0;
                $shiftdetail->id_wisata = $modelItem ? $modelItem->wisata : $request->wisata;
                $shiftdetail->id_transaksi = $transaksi->id_transaksi;
                $shiftdetail->id_shift = $shiftId;
                $shiftdetail->id_kasir = $request->kasir;
                $shiftdetail->id_item = $item['id_item'];
                $shiftdetail->harga = $item['harga'];
                $shiftdetail->jumlah = $item['jumlah'];
                $shiftdetail->diskon = 0;
                $shiftdetail->subtotal = $item['subtotal'];
                $shiftdetail->jenis_pembayaran = $request->jenis_pembayaran;
                $shiftdetail->pembayaran_refund = 0;
                $shiftdetail->status = 1; // Completed
                $shiftdetail->save();
            }

            \Log::info('Transaction details created:', ['total_items' => $totalItems]);

            // Update shift statistics
            $shift = Shift::find($shiftId);
            if ($shift) {
                $shift->item_terjual = ($shift->item_terjual ?? 0) + $totalItems;
                $shift->pendapatan = ($shift->pendapatan ?? 0) + $request->total_harga;
                $shift->save();
                \Log::info('Shift updated:', ['id' => $shift->id_shift]);
            }

            return response()->json([
                'message' => 'Transaction created successfully',
                'data' => $transaksi
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Transaction creation failed: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            \Log::error('Request data: ' . json_encode($request->all()));
            return response()->json([
                'message' => 'Transaksi gagal: ' . $e->getMessage(),
                'error' => $e->getMessage(),
                'trace' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }
    }
    public function storeadmin(Request $request)
    {
        $transaksi = Transaksi::findOrFail($request->id_transaksi);
        $transaksi->id_member = $request->id_member;
        $transaksi->total_item = $request->total_item;
        $transaksi->total_harga = $request->total;
        $transaksi->diskon = $request->diskon;
        $transaksi->bayar = $request->bayar;
        $transaksi->diterima = $request->diterima;
        $transaksi->Jenis_pembayaran = $request->jenis_pembayaran;
        $transaksi->bank = $request->id_bank;
        $transaksi->ewalet = $request->id_ewalet;
        $transaksi->update();

        $detail = DetailTransaksi::where('id_transaksi', $transaksi->id_transaksi)->get();
        foreach ($detail as $item) {
            $item->diskon = $item->diskon;
            $item->update();

        }
        return redirect()->route('transaksiadmin.index');
    }
    public function destroy($id)
    {
        $transaksi = Transaksi::find($id);
        $detail = DetailTransaksi::where('id_transaksi', $transaksi->id_transaksi)->get();
        foreach ($detail as $item) {
            $item->delete();
        }

        $transaksi->delete();

        return response(null, 204);
    }

    public function selesai()
    {
        $id_transaksi = session('id_transaksi');
        $transaksi = Transaksi::find($id_transaksi);
        $kembalian = $transaksi->diterima - $transaksi->bayar;
        $setting = Setting::first();
        return view('kasir.selesai', compact('setting', 'kembalian'));
    }
    public function selesaib($id)
    {
        $transaksi = Transaksi::find($id);
        $kembalian = $transaksi->diterima - $transaksi->bayar;
        $setting = Setting::first();
        return view('kasir.selesaib', compact('setting', 'kembalian', 'transaksi'));
    }
    public function nota()
    {
        $setting = Setting::first();
        $wisata = Wisata::first();
        $transaksi = Transaksi::find(session('id_transaksi'));
        if (!$transaksi) {
            abort(404);
        }
        $detail = DetailTransaksi::with('item')
            ->where('id_transaksi', session('id_transaksi'))
            ->get();

        return view('kasir.nota', compact('setting', 'transaksi', 'detail', 'wisata'));
    }
    // Print Nota yang ada di Detail Transasi
    public function notab($id)
    {
        $setting = Setting::first();
        $wisata = Wisata::first();
        $transaksi = Transaksi::find($id);
        if (!$transaksi) {
            abort(404);
        }
        $detail = DetailTransaksi::with('item')
            ->where('id_transaksi', $id)
            ->get();

        return view('kasir.nota', compact('setting', 'transaksi', 'detail', 'wisata'));
    }

    /**
     * Get thermal printer format data for transaction receipt
     */
    public function getThermalPrintData($id)
    {
        $transaksi = Transaksi::with('user')
            ->where('id_transaksi', $id)
            ->first();

        if (!$transaksi) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $detail_transaksi = DetailTransaksi::with('item')
            ->where('id_transaksi', $id)
            ->get();

        // Generate items HTML
        $itemsHtml = '';
        foreach ($detail_transaksi as $detail) {
            $itemsHtml .= '
        <tr>
            <td colspan="2">' . $detail->item->nama_item . '</td>
        </tr>
        <tr>
            <td>  Rp ' . number_format($detail->harga, 0, ',', '.') . ' x ' . $detail->jumlah . '</td>
            <td class="right">Rp ' . number_format($detail->subtotal, 0, ',', '.') . '</td>
        </tr>';
        }

        // Get wisata name from wisata table
        $wisata = Wisata::find($transaksi->wisata);
        $wisataName = $wisata ? $wisata->nama_wisata : 'SIWINDU POS';

        // Get kasir name
        $kasirName = $transaksi->user->name ?? 'Kasir';

        // Generate thermal receipt HTML
        $html = '
    <div class="center bold">' . strtoupper($wisataName) . '</div>
    <div class="center">E-TIKET WISATA</div>
    <div class="line"></div>
    <table>
        <tr><td>No. Transaksi:</td><td class="right">#' . $transaksi->id_transaksi . '</td></tr>
        <tr><td>Tanggal:</td><td class="right">' . date('d/m/Y H:i', strtotime($transaksi->created_at)) . '</td></tr>
        <tr><td>Kasir:</td><td class="right">' . $kasirName . '</td></tr>' .
            ($transaksi->nama_pelanggan ? '<tr><td>Pelanggan:</td><td class="right">' . $transaksi->nama_pelanggan . '</td></tr>' : '') . '
    </table>
    <div class="line"></div>
    <div class="bold">DETAIL PEMBELIAN</div>
    <table>
        ' . $itemsHtml . '
    </table>
    <div class="line"></div>
    <table>
        <tr class="bold"><td>TOTAL:</td><td class="right">Rp ' . number_format($transaksi->total_harga, 0, ',', '.') . '</td></tr>
        <tr><td>Pembayaran:</td><td class="right">' . $transaksi->jenis_pembayaran . '</td></tr>
    </table>
    <div class="line"></div>
    <div class="center">Terima Kasih</div>
    <div class="center">Atas Kunjungan Anda</div>
    ';

        return response()->json(['html' => $html]);
    }

    /**
     * Get top selling products with aggregated data
     */
    public function getTopProducts(Request $request)
    {
        $query = DB::table('detail_transaksi')
            ->join('items', 'detail_transaksi.id_item', '=', 'items.id_item')
            ->join('transaksis', 'detail_transaksi.id_transaksi', '=', 'transaksis.id_transaksi')
            ->select(
                'items.nama_item as name',
                DB::raw('SUM(detail_transaksi.jumlah) as quantity'),
                DB::raw('SUM(detail_transaksi.subtotal) as revenue')
            )
            ->groupBy('items.id_item', 'items.nama_item')
            ->orderBy('quantity', 'desc');

        // Filter by user role
        if (auth()->check()) {
            $user = auth()->user();
            $userRoles = $user->roles->pluck('name')->toArray();

            // Only filter by user_id for Kasir role
            if (in_array('kasir', array_map('strtolower', $userRoles))) {
                $query->where('transaksis.id_user', auth()->id());
            }
        }

        // Apply wisata filter if provided
        if ($request->has('wisata') && $request->wisata) {
            $query->where('transaksis.wisata', $request->wisata);
        }

        // Apply date filters if provided
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('transaksis.created_at', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('transaksis.created_at', '<=', $request->end_date);
        }

        $topProducts = $query->limit(5)->get();

        return response()->json($topProducts);
    }


    public function excel(Request $request)
    {
        $filter_wisata = $request->filter_wisata;

        return Excel::download(new TransaksiExport($filter_wisata), 'DataTransaksi.xlsx');
    }


}

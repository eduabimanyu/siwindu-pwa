<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Item;
use App\Models\User;
use App\Models\Wisata;
use App\Models\Shift;
use App\Models\Shiftdetail;
use App\Models\Transaksi;
use App\Models\Kategori;
use Illuminate\Http\Request;
use DataTables;
class ShiftController extends Controller
{
    public function index()
    {
        $wisata = Wisata::select('id', 'nama_wisata')->orderBy('nama_wisata')->get();
        if (request()->ajax()) {
            if (!empty(request()->filter_wisata)) {
                $kategori = Shift::where('wisata', request()->filter_wisata)
                    ->get();
            } else {
                $kategori = Shift::all();
            }
            return datatables()->of($kategori)
                ->addColumn('aksi', function ($kategori) {
                    $button = " <button class='edit btn  btn-warning' id='" . $kategori->id . "' >Edit</button>";
                    $button .= " <button class='hapus btn  btn-danger' id='" . $kategori->id . "' >Hapus</button>";
                    return $button;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
        return view('page.shift', compact('wisata'));
    }

    public function shiftkasir()
    {
        return view('kasir.shiftkasir');
    }
    public function shiftbaru()
    {
        return view('kasir.shiftbaru');
    }

    public function shiftsaatini()
    {
        $user = User::where('id', auth()->id())->first();
        $id_shift = $user->id_shift;
        // $id_shift = session('id_shift');
        $shift = Shift::find($id_shift);
        $shiftdetail = Shiftdetail::getcount()->where('id_shift', $id_shift)->where('status', '1')->get();
        $bayartunai = Shiftdetail::where('id_shift', $id_shift)->where('jenis_pembayaran', 'Tunai')->sum('subtotal');
        $bayarbank = Shiftdetail::where('id_shift', $id_shift)->where('jenis_pembayaran', 'Transfer Bank')->sum('subtotal');
        $bayarqris = Shiftdetail::where('id_shift', $id_shift)->where('jenis_pembayaran', 'QRIS')->sum('subtotal');
        $bayarewalet = Shiftdetail::where('id_shift', $id_shift)->where('jenis_pembayaran', 'Transfer Ewallet')->sum('subtotal');
        $pendapatan = Shiftdetail::where('id_shift', $id_shift)->where('status', '1')->sum('subtotal');
        $item = Shiftdetail::where('id_shift', $id_shift)->where('status', '1')->sum('jumlah');
        return view('kasir.shiftsaatini', compact('item', 'shift', 'id_shift', 'shiftdetail', 'bayartunai', 'bayarbank', 'bayarewalet', 'bayarqris', 'pendapatan'));

    }

    public function store(Request $request)
    {
        $shift = new Shift();
        $shift->wisata = Auth::user()->wisata;
        $shift->kasir = Auth::user()->id;
        $shift->item_terjual = 0;
        $shift->item_refund = 0;
        $shift->saldo_tunai = $request->saldo_tunai;
        $shift->uangdidapat = 0;
        $shift->pendapatan = 0;
        $shift->status = 0;
        $shift->save();

        // Refresh to get the auto-generated id_shift
        $shift->refresh();

        // Now we can safely use $shift->id_shift
        $user = User::where('id', auth()->id())->first();
        $user->id_shift = $shift->id_shift;
        $user->status = 1; // Set status to 1 (in shift)
        $user->update();

        // Check if request is API call
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => 'Shift started successfully',
                'data' => $shift
            ], 201);
        }

        return redirect()->route('shiftsaatini');

    }
    public function update(Request $request)
    {
        // Support both shift_id (from PWA) and id_shift (from web)
        $shiftId = $request->shift_id ?? $request->id_shift;

        if (!$shiftId) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Shift ID is required'
                ], 400);
            }
            return redirect()->back()->with('error', 'Shift ID is required');
        }

        $shift = Shift::find($shiftId);

        if (!$shift) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Shift not found'
                ], 404);
            }
            return redirect()->back()->with('error', 'Shift not found');
        }

        // Calculate totals
        $totalRevenue = Shiftdetail::where('id_shift', $shiftId)->where('status', '1')->sum('subtotal');
        $totalItems = Shiftdetail::where('id_shift', $shiftId)->where('status', '1')->sum('jumlah');

        // Update shift with reconciliation data
        $shift->status = 1; // 1 = completed
        $shift->pendapatan = $totalRevenue;
        $shift->item_terjual = $totalItems;

        // Save reconciliation data if provided
        if ($request->has('actual_cash')) {
            $shift->uangdidapat = $request->actual_cash;
            // Calculate expected cash (modal awal + tunai)
            $tunai = Shiftdetail::where('id_shift', $shiftId)
                ->where('status', '1')
                ->where('jenis_pembayaran', 'Tunai')
                ->sum('subtotal');
            $expectedCash = $shift->saldo_tunai + $tunai;
            // Store difference in a notes field or similar
        }

        if ($request->has('notes')) {
            // Assuming there's a notes field in shifts table
            // If not, you may need to add it via migration
        }

        $shift->update();

        // Update user status to not in shift
        $user = User::find(Auth::user()->id);
        $user->status = 0;
        $user->id_shift = null; // Clear shift ID
        $user->update();

        // Check if request is API call
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => 'Shift ended successfully',
                'data' => $shift
            ], 200);
        }

        return redirect()->back()->with('success', 'Shift berhasil diakhiri');
    }

    /**
     * Get current active shift for authenticated user
     */
    public function getCurrentShift(Request $request)
    {
        $user = Auth::user();

        if (!$user->id_shift) {
            return response()->json([
                'message' => 'No active shift'
            ], 404);
        }

        $shift = Shift::find($user->id_shift);

        if (!$shift) {
            return response()->json([
                'message' => 'Shift not found'
            ], 404);
        }

        // Calculate statistics
        $totalItems = Shiftdetail::where('id_shift', $shift->id_shift)
            ->where('status', '1')
            ->sum('jumlah');

        $totalRevenue = Shiftdetail::where('id_shift', $shift->id_shift)
            ->where('status', '1')
            ->sum('subtotal');

        $totalTransactions = Shiftdetail::where('id_shift', $shift->id_shift)
            ->where('status', '1')
            ->distinct('id_transaksi')
            ->count('id_transaksi');

        $paymentBreakdown = [
            'tunai' => Shiftdetail::where('id_shift', $shift->id_shift)
                ->where('status', '1')
                ->where('jenis_pembayaran', 'Tunai')
                ->sum('subtotal'),
            'transfer' => Shiftdetail::where('id_shift', $shift->id_shift)
                ->where('status', '1')
                ->where('jenis_pembayaran', 'Transfer Bank')
                ->sum('subtotal'),
            'qris' => Shiftdetail::where('id_shift', $shift->id_shift)
                ->where('status', '1')
                ->where('jenis_pembayaran', 'QRIS')
                ->sum('subtotal'),
            'ewallet' => Shiftdetail::where('id_shift', $shift->id_shift)
                ->where('status', '1')
                ->where('jenis_pembayaran', 'Transfer Ewallet')
                ->sum('subtotal'),
        ];

        return response()->json([
            'shift' => $shift,
            'stats' => [
                'total_items' => $totalItems,
                'total_revenue' => $totalRevenue,
                'total_transactions' => $totalTransactions,
                'payment_breakdown' => $paymentBreakdown
            ]
        ]);
    }

    /**
     * Get shift history for authenticated user
     */
    public function getShiftHistory(Request $request)
    {
        $user = Auth::user();

        $shifts = Shift::where('kasir', $user->id)
            ->where('status', '1')
            ->orderBy('updated_at', 'desc')
            ->limit(20)
            ->get();

        return response()->json([
            'data' => $shifts
        ]);
    }
    public function historishift()
    {
        return view('kasir.historishift');
    }

    public function aktivitas()
    {
        return view('kasir.aktivitas');
    }
    public function data(Request $request)
    {
        $transaksi = Transaksi::orderBy('created_at', 'desc');
        // Filter by user's wisata if needed

        if (!empty($request->wisata)) {
            $transaksi = Transaksi::where('transaksis.wisata', [$request->wisata])->where('transaksis.status', 'Selesai')->
                orderBy('created_at', 'desc')->get();
        } else {
            $transaksi = Transaksi::where('transaksis.wisata', [$request->wisata])->where('transaksis.status', 'Selesai')->
                orderBy('created_at', 'desc')->get();
        }
        return datatables()
            ->of($transaksi)
            ->addIndexColumn()
            ->addColumn('tanggal', function ($transaksi) {
                return tanggal_indo($transaksi->created_at, false);
            })
            ->addColumn('jam', function ($transaksi) {
                return jam_indo($transaksi->created_at, false);
            })
            ->addColumn('aksi', function ($transaksi) {
                return '
                <div class="btn-group">
                    <a href="' . route('transaksidetail', $transaksi->id_transaksi) . '" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i></a>
                </div>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);

    }


    public function datahistori()
    {
        $shift = Shift::orderBy('updated_at', 'desc')->where('status', '1')->where('wisata', Auth::user()->wisata)->where('kasir', Auth::user()->id)->get();
        if (request()->ajax()) {
            return datatables()->of($shift)
                ->addColumn('aksi', function ($shift) {
                    return '
                <div class="btn-group">
                    <a href="' . route('shiftdetail', $shift->id_shift) . '" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i></a>
                </div>
                ';
                })->addColumn('tanggalawal', function ($shift) {
                    return tanggal_indonesia($shift->created_at, false);
                })
                // ->addColumn('nama_kasir', function ($shift) {
                //     return $shift->user->name;
                // })
                ->addColumn('tanggalakhir', function ($shift) {
                    return tanggal_indonesia($shift->updated_at, false);
                })
                ->addColumn('idshift', function ($shift) {
                    return $shift->id_shift;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }

    }

    public function datahistoriadmin(Request $request)
    {

        if (!empty($request->filter_wisata) && ($request->start_date)) {
            if (!empty($request->filter_wisata) && ($request->start_date === $request->end_date)) {
                $shift = Shift::orderBy('updated_at', 'desc')
                    ->where('shifts.wisata', [$request->filter_wisata])
                    ->whereDate('shifts.created_at', [$request->start_date])
                    ->where('status', '1')
                    ->get();
            } else {
                $shift = Shift::orderBy('updated_at', 'desc')
                    ->where('shifts.wisata', [$request->filter_wisata])
                    ->whereDate('shifts.created_at', '>=', [$request->start_date])
                    ->whereDate('shifts.created_at', '<=', [$request->end_date])
                    ->where('status', '1')
                    ->get();
            }
        } else {
            $shift = Shift::orderBy('updated_at', 'desc')
                ->whereDate('shifts.created_at', '>=', [$request->start_date])
                ->whereDate('shifts.created_at', '<=', [$request->end_date])
                ->where('status', '1')
                ->get();
        }
        if (request()->ajax()) {
            return datatables()->of($shift)
                ->addColumn('aksi', function ($shift) {
                    if (Auth::user()->hasRole('admin')) {
                        return '
                <div class="btn-group">
                    <a href="' . route('shiftdetail', $shift->id_shift) . '" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i></a>
                </div>
                <button onclick="deleteData(`' . route('shift.destroy', $shift->id_shift) . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                ';
                    } else {
                        return '
                <div class="btn-group">
                    <a href="' . route('shiftdetail', $shift->id_shift) . '" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i></a>
                </div>
                ';
                    }
                })->addColumn('tanggalawal', function ($shift) {
                    return tanggal_indonesia($shift->created_at, false);
                })
                ->addColumn('total_pendapatan', function ($shift) {
                    $pendapatan = Shiftdetail::where('id_shift', $shift->id_shift)->where('status', '1')->sum('subtotal');
                    $total = $pendapatan + $shift->saldo_tunai;
                    return $total;
                })
                ->addColumn('nama_kasir', function ($shift) {
                    return $shift->user->name;
                })
                ->addColumn('tanggalakhir', function ($shift) {
                    return tanggal_indonesia($shift->updated_at, false);
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
    }
    public function destroy($id)
    {
        $shift = Shift::find($id);
        $detail = Shiftdetail::where('id_shift', $shift->id_shift)->get();
        foreach ($detail as $item) {
            $item->delete();
        }

        $shift->delete();

        return response(null, 204);
    }
    public function shiftdetail($id)
    {
        $pendapatan = Shiftdetail::where('id_shift', $id)->where('status', '1')->sum('subtotal');
        $shiftdetail = Shiftdetail::getcount()->where('id_shift', $id)->where('status', '1')->get();
        $item = Shiftdetail::where('id_shift', $id)->where('status', '1')->sum('jumlah');
        $bayar = Shiftdetail::where('id_shift', $id)->where('status', '1')->sum('subtotal');
        $bayartunai = Shiftdetail::where('id_shift', $id)->where('status', '1')->where('jenis_pembayaran', 'Tunai')->sum('subtotal');
        $bayarbank = Shiftdetail::where('id_shift', $id)->where('status', '1')->where('jenis_pembayaran', 'Transfer Bank')->sum('subtotal');
        $bayarqris = Shiftdetail::where('id_shift', $id)->where('jenis_pembayaran', 'QRIS')->sum('subtotal');
        $bayarewalet = Shiftdetail::where('id_shift', $id)->where('status', '1')->where('jenis_pembayaran', 'Transfer Ewallet')->sum('subtotal');
        $shift = Shift::find($id);

        if (!$shift) {
            if (request()->expectsJson()) {
                return response()->json(['error' => 'Shift not found'], 404);
            }
            abort(404);
        }

        // Return JSON for API requests
        if (request()->expectsJson() || request()->is('api/*')) {
            // Get product breakdown using join
            $productBreakdown = DB::table('shiftdetails')
                ->join('items', 'shiftdetails.id_item', '=', 'items.id_item')
                ->where('shiftdetails.id_shift', $id)
                ->where('shiftdetails.status', '1')
                ->select(
                    'items.nama_item as item_name',
                    'items.harga as price',
                    DB::raw('SUM(shiftdetails.jumlah) as quantity'),
                    DB::raw('SUM(shiftdetails.subtotal) as subtotal')
                )
                ->groupBy('shiftdetails.id_item', 'items.nama_item', 'items.harga')
                ->get();

            return response()->json([
                'shift' => $shift,
                'stats' => [
                    'total_items' => $item,
                    'total_revenue' => $pendapatan,
                    'item_terjual' => $item,
                    'pendapatan' => $pendapatan,
                    'payment_breakdown' => [
                        'tunai' => $bayartunai,
                        'transfer' => $bayarbank,
                        'qris' => $bayarqris,
                        'ewallet' => $bayarewalet
                    ],
                    'product_breakdown' => $productBreakdown
                ]
            ]);
        }

        // Return view for web requests
        return view('kasir.shiftdetail', compact('item', 'pendapatan', 'shift', 'shiftdetail', 'bayartunai', 'bayarbank', 'bayarewalet', 'bayarqris', 'bayar'));
    }
    public function shiftberakhir()
    {
        $user = User::where('id', auth()->id())->first();
        $id_shift = $user->id_shift;
        // $id_shift = session('id_shift');
        $shift = Shift::find($id_shift);
        return view('kasir.shiftberakhir', compact('shift'));
    }
    public function shiftnota()
    {
        $user = User::where('id', auth()->id())->first();
        $id_shift = $user->id_shift;
        // $id_shift = session('id_shift');
        $shiftdetail = Shiftdetail::getcount()->where('id_shift', $id_shift)->where('status', '1')->get();
        $bayar = Shiftdetail::where('id_shift', $id_shift)->where('status', '1')->sum('subtotal');
        $bayartunai = Shiftdetail::where('id_shift', $id_shift)->where('status', '1')->where('jenis_pembayaran', 'Tunai')->sum('subtotal');
        $bayarbank = Shiftdetail::where('id_shift', $id_shift)->where('status', '1')->where('jenis_pembayaran', 'Transfer Bank')->sum('subtotal');
        $bayarqris = Shiftdetail::where('id_shift', $id_shift)->where('jenis_pembayaran', 'QRIS')->sum('subtotal');
        $bayarewalet = Shiftdetail::where('id_shift', $id_shift)->where('status', '1')->where('jenis_pembayaran', 'Transfer Ewallet')->sum('subtotal');
        $item = Shiftdetail::where('id_shift', $id_shift)->where('status', '1')->sum('jumlah');
        $shift = Shift::find($id_shift);
        if (!$shift) {
            abort(404);
        }
        $selisih = Shiftdetail::where('id_shift', $id_shift)->where('status', '1')->sum('subtotal');
        return view('kasir.shiftnota', compact('item', 'shift', 'shiftdetail', 'bayartunai', 'bayarbank', 'bayarewalet', 'bayarqris', 'bayar'));
    }
    public function historinota($id)
    {
        $shiftdetail = Shiftdetail::getcount()->where('id_shift', $id)->where('status', '1')->get();
        $bayar = Shiftdetail::where('id_shift', $id)->where('status', '1')->sum('subtotal');
        $bayartunai = Shiftdetail::where('id_shift', $id)->where('status', '1')->where('jenis_pembayaran', 'Tunai')->sum('subtotal');
        $bayarbank = Shiftdetail::where('id_shift', $id)->where('status', '1')->where('jenis_pembayaran', 'Transfer Bank')->sum('subtotal');
        $bayarewalet = Shiftdetail::where('id_shift', $id)->where('status', '1')->where('jenis_pembayaran', 'Transfer Ewallet')->sum('subtotal');
        $bayarqris = Shiftdetail::where('id_shift', $id)->where('jenis_pembayaran', 'QRIS')->sum('subtotal');
        $item = Shiftdetail::where('id_shift', $id)->where('status', '1')->sum('jumlah');
        $shift = Shift::find($id);
        if (!$shift) {
            abort(404);
        }
        $selisih = Shiftdetail::where('id_shift', $id)->where('status', '1')->sum('subtotal');
        return view('kasir.shiftnota', compact('item', 'shift', 'shiftdetail', 'bayartunai', 'bayarbank', 'bayarqris', 'bayarewalet', 'bayar'));
    }

    /**
     * Get thermal printer format data
     */
    public function getThermalPrintData($id)
    {
        $shift = Shift::find($id);
        if (!$shift) {
            return response()->json(['message' => 'Shift not found'], 404);
        }

        $shiftdetail = Shiftdetail::getcount()->where('id_shift', $id)->where('status', '1')->get();
        $bayartunai = Shiftdetail::where('id_shift', $id)->where('status', '1')->where('jenis_pembayaran', 'Tunai')->sum('subtotal');
        $bayarbank = Shiftdetail::where('id_shift', $id)->where('status', '1')->where('jenis_pembayaran', 'Transfer Bank')->sum('subtotal');
        $bayarqris = Shiftdetail::where('id_shift', $id)->where('jenis_pembayaran', 'QRIS')->sum('subtotal');
        $bayarewalet = Shiftdetail::where('id_shift', $id)->where('status', '1')->where('jenis_pembayaran', 'Transfer Ewallet')->sum('subtotal');
        $item = Shiftdetail::where('id_shift', $id)->where('status', '1')->sum('jumlah');
        $pendapatan = Shiftdetail::where('id_shift', $id)->where('status', '1')->sum('subtotal');

        // Get product breakdown
        $productBreakdown = DB::table('shiftdetails')
            ->join('items', 'shiftdetails.id_item', '=', 'items.id_item')
            ->where('shiftdetails.id_shift', $id)
            ->where('shiftdetails.status', '1')
            ->select(
                'items.nama_item',
                'items.harga',
                DB::raw('SUM(shiftdetails.jumlah) as quantity'),
                DB::raw('SUM(shiftdetails.subtotal) as subtotal')
            )
            ->groupBy('shiftdetails.id_item', 'items.nama_item', 'items.harga')
            ->get();

        // Generate product breakdown HTML
        $productHtml = '';
        foreach ($productBreakdown as $product) {
            $productHtml .= '
            <tr>
                <td colspan="2">' . $product->nama_item . '</td>
            </tr>
            <tr>
                <td>  Rp ' . number_format($product->harga, 0, ',', '.') . ' x ' . $product->quantity . '</td>
                <td class="right">Rp ' . number_format($product->subtotal, 0, ',', '.') . '</td>
            </tr>';
        }

        // Generate thermal receipt HTML
        $html = '
        <div class="center bold">LAPORAN SHIFT</div>
        <div class="center">Shift #' . $shift->id_shift . '</div>
        <div class="line"></div>
        <table>
            <tr><td>Kasir:</td><td class="right">' . $shift->user->name . '</td></tr>
            <tr><td>Mulai:</td><td class="right">' . tanggal_indonesia($shift->created_at, false) . '</td></tr>
            <tr><td>Selesai:</td><td class="right">' . tanggal_indonesia($shift->updated_at, false) . '</td></tr>
        </table>
        <div class="line"></div>
        <table>
            <tr><td>Modal Awal:</td><td class="right">Rp ' . number_format($shift->saldo_tunai, 0, ',', '.') . '</td></tr>
            <tr><td>Item Terjual:</td><td class="right">' . $item . '</td></tr>
            <tr><td>Total Pendapatan:</td><td class="right">Rp ' . number_format($pendapatan, 0, ',', '.') . '</td></tr>
        </table>
        <div class="line"></div>
        <div class="bold">RINCIAN PEMBAYARAN</div>
        <table>
            <tr><td>Tunai:</td><td class="right">Rp ' . number_format($bayartunai, 0, ',', '.') . '</td></tr>
            <tr><td>Transfer Bank:</td><td class="right">Rp ' . number_format($bayarbank, 0, ',', '.') . '</td></tr>
            <tr><td>QRIS:</td><td class="right">Rp ' . number_format($bayarqris, 0, ',', '.') . '</td></tr>
            <tr><td>E-Wallet:</td><td class="right">Rp ' . number_format($bayarewalet, 0, ',', '.') . '</td></tr>
        </table>
        <div class="line"></div>
        <div class="bold">DETAIL PRODUK TERJUAL</div>
        <table>
            ' . $productHtml . '
        </table>
        <div class="line"></div>
        <div class="center">Terima Kasih</div>
        ';

        return response()->json(['html' => $html]);
    }

    /**
     * Generate PDF report (placeholder - requires PDF library)
     */
    public function generatePDF($id)
    {
        $shift = Shift::find($id);
        if (!$shift) {
            return response()->json(['message' => 'Shift not found'], 404);
        }

        // For now, return the same data as thermal
        // In production, you would use a PDF library like DomPDF or TCPDF
        return $this->getThermalPrintData($id);
    }
}

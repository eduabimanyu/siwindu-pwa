<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Transaksi;
use App\Models\Shift;
use App\Models\User;
use App\Models\Item;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function stats(Request $request)
    {
        $user = $request->user();

        // Eager load roles if not already loaded
        if (!$user->relationLoaded('roles')) {
            $user->load('roles');
        }

        $today = date('Y-m-d');
        $currentYear = date('Y');

        // Get filters from request
        $wisataFilter = $request->input('wisata');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Base query filters based on user role
        if (!$wisataFilter && $user->hasRole('kasir')) {
            $wisataFilter = $user->wisata;
        }

        // Date range for filtering
        $dateStart = $startDate ?: date('Y-01-01'); // Start of year if not specified
        $dateEnd = $endDate ?: date('Y-12-31'); // End of year if not specified

        // Total transactions today
        $transaksiHariIni = Transaksi::whereDate('created_at', $today)
            ->where('status', 'Selesai')
            ->when($wisataFilter, function ($query) use ($wisataFilter) {
                return $query->where('wisata', $wisataFilter);
            })
            ->count();

        // Total revenue today
        $pendapatanHariIni = Transaksi::whereDate('created_at', $today)
            ->where('status', 'Selesai')
            ->when($wisataFilter, function ($query) use ($wisataFilter) {
                return $query->where('wisata', $wisataFilter);
            })
            ->sum('total_harga');

        // Total transactions in date range
        $totalTransaksi = Transaksi::whereBetween('created_at', [$dateStart, $dateEnd])
            ->where('status', 'Selesai')
            ->when($wisataFilter, function ($query) use ($wisataFilter) {
                return $query->where('wisata', $wisataFilter);
            })
            ->count();

        // Total revenue in date range
        $totalPendapatan = Transaksi::whereBetween('created_at', [$dateStart, $dateEnd])
            ->where('status', 'Selesai')
            ->when($wisataFilter, function ($query) use ($wisataFilter) {
                return $query->where('wisata', $wisataFilter);
            })
            ->sum('total_harga');

        // Transactions per month (for chart)
        $transaksiPerBulan = Transaksi::select(DB::raw("MONTH(created_at) as month"), DB::raw("COUNT(*) as count"))
            ->whereBetween('created_at', [$dateStart, $dateEnd])
            ->where('status', 'Selesai')
            ->when($wisataFilter, function ($query) use ($wisataFilter) {
                return $query->where('wisata', $wisataFilter);
            })
            ->groupBy(DB::raw("MONTH(created_at)"))
            ->pluck('count', 'month')
            ->toArray();

        // Fill missing months with 0
        $transaksiPerBulanArray = [];
        for ($i = 1; $i <= 12; $i++) {
            $transaksiPerBulanArray[] = $transaksiPerBulan[$i] ?? 0;
        }

        // Pendapatan per month (for chart)
        $pendapatanPerBulan = Transaksi::select(DB::raw("MONTH(created_at) as month"), DB::raw("SUM(total_harga) as sum"))
            ->whereBetween('created_at', [$dateStart, $dateEnd])
            ->where('status', 'Selesai')
            ->when($wisataFilter, function ($query) use ($wisataFilter) {
                return $query->where('wisata', $wisataFilter);
            })
            ->groupBy(DB::raw("MONTH(created_at)"))
            ->pluck('sum', 'month')
            ->toArray();

        // Fill missing months with 0
        $pendapatanPerBulanArray = [];
        for ($i = 1; $i <= 12; $i++) {
            $pendapatanPerBulanArray[] = (int) ($pendapatanPerBulan[$i] ?? 0);
        }

        // Current active shift (for kasir)
        $shiftAktif = null;
        if ($user->hasRole('kasir')) {
            $shiftAktif = Shift::where('kasir', $user->id)
                ->where('status', 0) // status 0 = active, 1 = finished
                ->first();

            // Set tunai_masuk to 0 for now
            if ($shiftAktif) {
                $shiftAktif->tunai_masuk = 0;
            }
        }

        // Total users (for admin)
        $totalUsers = 0;
        if ($user->hasRole('admin') || $user->hasRole('manager')) {
            $totalUsers = User::count();
        }

        return response()->json([
            'transaksi_hari_ini' => $transaksiHariIni,
            'pendapatan_hari_ini' => (int) $pendapatanHariIni,
            'total_transaksi' => $totalTransaksi,
            'total_pendapatan' => (int) $totalPendapatan,
            'transaksi_per_bulan' => $transaksiPerBulanArray,
            'pendapatan_per_bulan' => $pendapatanPerBulanArray,
            'shift_aktif' => $shiftAktif,
            'total_users' => $totalUsers,
        ]);
    }
}

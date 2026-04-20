<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Wisata;
use App\Models\Item;
use App\Models\Setting;
use App\Models\Transaksi;
use App\Charts\UserLineChart;

class DashboardController extends Controller
{
    public function index()
    {
        $wisata = Wisata::select('id','nama_wisata')->orderBy('nama_wisata')->get();
        if(Auth::user()->hasRole('manager')){
           $transaksi = Transaksi::select(\DB::raw("COUNT(*) as count"))
            ->whereYear('created_at', date('Y'))->where('status', 'Selesai')
            ->groupBy(\DB::raw("Month(created_at)"))
            ->pluck('count');
            $transaksisum = Transaksi::select(\DB::raw("SUM(total_harga) as sum"))
            ->whereYear('created_at', date('Y'))->where('status', 'Selesai')
            ->groupBy(\DB::raw("Month(created_at)"))
            ->pluck('sum');
            return view ('manager.dashboard',compact('wisata','transaksi','transaksisum'));
        }elseif(Auth::user()->hasRole('admin')){
            $transaksi = Transaksi::select(\DB::raw("COUNT(*) as count"))
            ->whereYear('created_at', date('Y'))->where('status', 'Selesai')
            ->groupBy(\DB::raw("Month(created_at)"))
            ->pluck('count');
            $transaksisum = Transaksi::select(\DB::raw("SUM(total_harga) as sum"))
            ->whereYear('created_at', date('Y'))->where('status', 'Selesai')
            ->groupBy(\DB::raw("Month(created_at)"))
            ->pluck('sum');
            return view ('admin.dashboard',compact('wisata','transaksi','transaksisum'));
        }
        elseif(Auth::user()->hasRole('kasir')){
            return view('kasir.dashboard');
        }
        elseif(Auth::user()->hasRole('keuangan')){
           $transaksi = Transaksi::select(\DB::raw("COUNT(*) as count"))
            ->whereYear('created_at', date('Y'))->where('status', 'Selesai')
            ->groupBy(\DB::raw("Month(created_at)"))
            ->pluck('count');
            $transaksisum = Transaksi::select(\DB::raw("SUM(total_harga) as sum"))
            ->whereYear('created_at', date('Y'))->where('status', 'Selesai')
            ->groupBy(\DB::raw("Month(created_at)"))
            ->pluck('sum');
            return view ('admin.dashboard',compact('wisata','transaksi','transaksisum'));
        }
         elseif(Auth::user()->hasRole('operator')){
           $wisata = Wisata::count();
           $item = Item::count();
            return view ('operator.dashboard',compact('wisata','item'));
        }
        else{
            return view ('pelanggan.dashboard');
        }
    }
    public function profile()
    {
        return view('profile.dashboard');
    }
    public function user()
    {
        return view('user.user');
    }
    public function penjualan()
    {
        return view('penjualan');
    }


    public function chartLineAjax(Request $request)
    {
        $year = $request->has('year') ? $request->year : date('Y');
        $users = User::select(\DB::raw("COUNT(*) as count"))
                    ->whereYear('created_at', $year)
                    ->groupBy(\DB::raw("Month(created_at)"))
                    ->pluck('count');

        $chart = new UserLineChart;

        $chart->dataset('New User Register Chart', 'line', $users)->options([
                    'fill' => 'true',
                    'borderColor' => '#51C1C0'
                ]);

        return $chart->api();
    }


}

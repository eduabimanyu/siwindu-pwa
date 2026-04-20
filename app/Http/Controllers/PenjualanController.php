<?php

namespace App\Http\Controllers;
use App\Models\Wisata;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Kategori;
use App\Models\Bank;
use App\Models\Ewalet;
class PenjualanController extends Controller
{

    public function jenis_pembayaran()
    {


        $bank =Bank::all();
        $ewalet =Ewalet::all();
        $wisata = Wisata::select('id','nama_wisata')->get();
        return view('page.jenis_pembayaran',
        compact('wisata','bank','ewalet'));
    }
    public function tunai()
    {

        $wisata = Wisata::select('id','nama_wisata')->get();
        return view('page.jenis_pembayaran_tunai',
        compact('wisata'));
    }
        public function qris()
    {
        $wisata = Wisata::select('id','nama_wisata')->get();
        return view('page.jenis_pembayaran_qris',
        compact('wisata'));
    }
    public function bank()
    {
        $wisata = Wisata::select('id','nama_wisata')->get();
        return view('page.jenis_pembayaran_bank',
        compact('wisata'));
    }
    public function ewalet()
    {
        $wisata = Wisata::select('id','nama_wisata')->get();
        return view('page.jenis_pembayaran_ewalet',
        compact('wisata'));
    }
    public function count(Request $request)
    {
        if (request()->ajax()) {

                    if (!empty($request->filter_wisata&&$request->start_date===$request->end_date)){
                        $transaksi = Transaksi::where('transaksis.wisata',[$request->filter_wisata])
                        ->whereDate('transaksis.created_at',[$request->start_date])->where('transaksis.status','Selesai')
                        ->count();
                    }else if (!empty($request->filter_wisata&&$request->start_date)){
                        $transaksi =Transaksi::where('transaksis.wisata',[$request->filter_wisata])->whereDate('transaksis.created_at','>=',[$request->start_date])->whereDate('transaksis.created_at','<=',[$request->end_date])->where('transaksis.status','Selesai')
                        ->count();
                    }else{
                        $transaksi =Transaksi::whereDate('transaksis.created_at','>=',[$request->start_date])->whereDate('transaksis.created_at','<=',[$request->end_date])->where('transaksis.status','Selesai')
                        ->count();
                    }

                return response()->json($transaksi);

        }

    }
    public function kategori_count(Request $request)
    {

        if (request()->ajax()) {
            if (!empty($request->filter_wisata)&&($request->start_date)){
              if (!empty($request->filter_wisata)&&($request->start_date===$request->end_date)){
                  $kategori = DetailTransaksi::where('detail_transaksi.id_wisata',[$request->filter_wisata])
                  ->whereDate('detail_transaksi.created_at',[$request->start_date])
                  ->count();
              }else{
                  $kategori =DetailTransaksi::where('detail_transaksi.id_wisata',[$request->filter_wisata])->whereDate('detail_transaksi.created_at','>=',[$request->start_date])->whereDate('detail_transaksi.created_at','<=',[$request->end_date])
                  ->count();
              }
              }else{
                  $kategori =DetailTransaksi::whereDate('detail_transaksi.created_at','>=',[$request->start_date])->whereDate('detail_transaksi.created_at','<=',[$request->end_date])
                  ->count();
               }
              return response()->json($kategori);
      }
    }
    public function items_count(Request $request)
    {
        if (request()->ajax()) {
              if (!empty($request->filter_wisata)&&($request->start_date)){
                if (!empty($request->filter_wisata)&&($request->start_date===$request->end_date)){
                    $item = DetailTransaksi::where('detail_transaksi.id_wisata',[$request->filter_wisata])
                    ->whereDate('detail_transaksi.created_at',[$request->start_date])
                    ->count();
                }else{
                    $item =DetailTransaksi::where('detail_transaksi.id_wisata',[$request->filter_wisata])->whereDate('detail_transaksi.created_at','>=',[$request->start_date])->whereDate('detail_transaksi.created_at','<=',[$request->end_date])
                    ->count();
                }
                }else{
                    $item =DetailTransaksi::whereDate('detail_transaksi.created_at','>=',[$request->start_date])->whereDate('detail_transaksi.created_at','<=',[$request->end_date])
                    ->count();
                 }
                return response()->json($item);
        }

    }
    public function pendapatan(Request $request)
    {
        
        if (request()->ajax()) {

                    if (!empty($request->filter_wisata&&$request->start_date===$request->end_date)){
                        $transaksi = Transaksi::where('transaksis.wisata',[$request->filter_wisata])
                        ->whereDate('transaksis.created_at',[$request->start_date])->where('transaksis.status','Selesai')
                        ->sum('total_harga');
                    }else if (!empty($request->filter_wisata&&$request->start_date)){
                        $transaksi =Transaksi::where('transaksis.wisata',[$request->filter_wisata])->whereDate('transaksis.created_at','>=',[$request->start_date])->whereDate('transaksis.created_at','<=',[$request->end_date])->where('transaksis.status','Selesai')
                        ->sum('total_harga');
                    }else{
                        $transaksi =Transaksi::whereDate('transaksis.created_at','>=',[$request->start_date])->whereDate('transaksis.created_at','<=',[$request->end_date])->where('transaksis.status','Selesai')
                        ->sum('total_harga');
                    }

                return response()->json('Rp. '. format_uang($transaksi));
        }

        
        
        // {
        //     if (request()->ajax()) {
        //           if (!empty($request->filter_wisata)&&($request->start_date)){
        //             if (!empty($request->filter_wisata)&&($request->start_date===$request->end_date)){
        //                 $pendapatan = DetailTransaksi::where('detail_transaksi.id_wisata',[$request->filter_wisata])
        //                 ->whereDate('detail_transaksi.created_at',[$request->start_date])
        //                 ->sum('subtotal');
        //             }else{
        //                 $pendapatan =DetailTransaksi::where('detail_transaksi.id_wisata',[$request->filter_wisata])->whereDate('detail_transaksi.created_at','>=',[$request->start_date])->whereDate('detail_transaksi.created_at','<=',[$request->end_date])
        //                 ->sum('subtotal');
        //             }
        //             }else{
        //                 $pendapatan =DetailTransaksi::whereDate('detail_transaksi.created_at','>=',[$request->start_date])->whereDate('detail_transaksi.created_at','<=',[$request->end_date])
        //                 ->sum('subtotal');
        //              }
        //             return response()->json('Rp. '. format_uang($pendapatan));
        //     }
    
        // }
    }
    public function transaksi_tunai(Request $request)
    {
        if (request()->ajax()) {
                    if (!empty($request->filter_wisata&&$request->start_date===$request->end_date)){
                        $transaksi = Transaksi::where('transaksis.wisata',[$request->filter_wisata])
                        ->whereDate('transaksis.created_at',[$request->start_date])->where('jenis_pembayaran','Tunai')->where('transaksis.status','Selesai')
                        ->count();
                    }else if (!empty($request->filter_wisata&&$request->start_date)){
                        $transaksi =Transaksi::where('transaksis.wisata',[$request->filter_wisata])->whereDate('transaksis.created_at','>=',[$request->start_date])->where('jenis_pembayaran','Tunai')->whereDate('transaksis.created_at','<=',[$request->end_date])->where('transaksis.status','Selesai')
                        ->count();
                    }else{
                        $transaksi =Transaksi::whereDate('transaksis.created_at','>=',[$request->start_date])->whereDate('transaksis.created_at','<=',[$request->end_date])->where('jenis_pembayaran','Tunai')->where('transaksis.status','Selesai')
                        ->count();
                    }

                return response()->json($transaksi);

        }

    }
    public function transaksi_qris(Request $request)
    {
        if (request()->ajax()) {
                    if (!empty($request->filter_wisata&&$request->start_date===$request->end_date)){
                        $transaksi = Transaksi::where('transaksis.wisata',[$request->filter_wisata])
                        ->whereDate('transaksis.created_at',[$request->start_date])->where('jenis_pembayaran','QRIS')->where('transaksis.status','Selesai')
                        ->count();
                    }else if (!empty($request->filter_wisata&&$request->start_date)){
                        $transaksi =Transaksi::where('transaksis.wisata',[$request->filter_wisata])->whereDate('transaksis.created_at','>=',[$request->start_date])->where('jenis_pembayaran','QRIS')->whereDate('transaksis.created_at','<=',[$request->end_date])->where('transaksis.status','Selesai')
                        ->count();
                    }else{
                        $transaksi =Transaksi::whereDate('transaksis.created_at','>=',[$request->start_date])->whereDate('transaksis.created_at','<=',[$request->end_date])->where('jenis_pembayaran','QRIS')->where('transaksis.status','Selesai')
                        ->count();
                    }

                return response()->json($transaksi);

        }

    }
    public function transaksi_bank(Request $request)
    {
        if (request()->ajax()) {
                    if (!empty($request->filter_wisata&&$request->start_date===$request->end_date)){
                        $transaksi = Transaksi::where('transaksis.wisata',[$request->filter_wisata])
                        ->whereDate('transaksis.created_at',[$request->start_date])->where('jenis_pembayaran','Transfer Bank')->where('transaksis.status','Selesai')
                        ->count();
                    }else if (!empty($request->filter_wisata&&$request->start_date)){
                        $transaksi =Transaksi::where('transaksis.wisata',[$request->filter_wisata])->whereDate('transaksis.created_at','>=',[$request->start_date])->where('jenis_pembayaran','Transfer Bank')->whereDate('transaksis.created_at','<=',[$request->end_date])->where('transaksis.status','Selesai')
                        ->count();
                    }else{
                        $transaksi =Transaksi::whereDate('transaksis.created_at','>=',[$request->start_date])->whereDate('transaksis.created_at','<=',[$request->end_date])->where('jenis_pembayaran','Transfer Bank')->where('transaksis.status','Selesai')
                        ->count();
                    }

                return response()->json($transaksi);

        }

    }
    public function transaksi_ewalet(Request $request)
    {
        if (request()->ajax()) {
                    if (!empty($request->filter_wisata&&$request->start_date===$request->end_date)){
                        $transaksi = Transaksi::where('transaksis.wisata',[$request->filter_wisata])
                        ->whereDate('transaksis.created_at',[$request->start_date])->where('jenis_pembayaran','Transfer Ewallet')->where('transaksis.status','Selesai')
                        ->count();
                    }else if (!empty($request->filter_wisata&&$request->start_date)){
                        $transaksi =Transaksi::where('transaksis.wisata',[$request->filter_wisata])->whereDate('transaksis.created_at','>=',[$request->start_date])->where('jenis_pembayaran','Transfer Ewallet')->whereDate('transaksis.created_at','<=',[$request->end_date])->where('transaksis.status','Selesai')
                        ->count();
                    }else{
                        $transaksi =Transaksi::whereDate('transaksis.created_at','>=',[$request->start_date])->whereDate('transaksis.created_at','<=',[$request->end_date])->where('jenis_pembayaran','Transfer Ewallet')->where('transaksis.status','Selesai')
                        ->count();
                    }

                return response()->json($transaksi);

        }

    }
    public function pendapatan_tunai(Request $request)
    {
        if (request()->ajax()) {
            if (!empty($request->filter_wisata&&$request->start_date===$request->end_date)){
                $pendapatan = Transaksi::where('transaksis.wisata',[$request->filter_wisata])
                ->whereDate('transaksis.created_at',[$request->start_date])->where('jenis_pembayaran','Tunai')->where('transaksis.status','Selesai')
                ->sum('total_harga');
            }else if (!empty($request->filter_wisata&&$request->start_date)){
                $pendapatan =Transaksi::where('transaksis.wisata',[$request->filter_wisata])->whereDate('transaksis.created_at','>=',[$request->start_date])->whereDate('transaksis.created_at','<=',[$request->end_date])->where('jenis_pembayaran','Tunai')->where('transaksis.status','Selesai')
                ->sum('total_harga');
            }else{
                $pendapatan =Transaksi::whereDate('transaksis.created_at','>=',[$request->start_date])->whereDate('transaksis.created_at','<=',[$request->end_date])->where('jenis_pembayaran','Tunai')->where('transaksis.status','Selesai')
                ->sum('total_harga');
            }

                return response()->json(format_uang($pendapatan));
        }
    }
     public function pendapatan_qris(Request $request)
    {
        if (request()->ajax()) {
            if (!empty($request->filter_wisata&&$request->start_date===$request->end_date)){
                $pendapatan = Transaksi::where('transaksis.wisata',[$request->filter_wisata])
                ->whereDate('transaksis.created_at',[$request->start_date])->where('jenis_pembayaran','QRIS')->where('transaksis.status','Selesai')
                ->sum('total_harga');
            }else if (!empty($request->filter_wisata&&$request->start_date)){
                $pendapatan =Transaksi::where('transaksis.wisata',[$request->filter_wisata])->whereDate('transaksis.created_at','>=',[$request->start_date])->whereDate('transaksis.created_at','<=',[$request->end_date])->where('jenis_pembayaran','QRIS')->where('transaksis.status','Selesai')
                ->sum('total_harga');
            }else{
                $pendapatan =Transaksi::whereDate('transaksis.created_at','>=',[$request->start_date])->whereDate('transaksis.created_at','<=',[$request->end_date])->where('jenis_pembayaran','QRIS')->where('transaksis.status','Selesai')
                ->sum('total_harga');
            }

                return response()->json(format_uang($pendapatan));
        }
    }
    public function pendapatan_bank(Request $request)
    {
        if (request()->ajax()) {
            if (!empty($request->filter_wisata&&$request->start_date===$request->end_date)){
                $pendapatan = Transaksi::where('transaksis.wisata',[$request->filter_wisata])
                ->whereDate('transaksis.created_at',[$request->start_date])->where('jenis_pembayaran','Transfer Bank')->where('transaksis.status','Selesai')
                ->sum('total_harga');
            }else if (!empty($request->filter_wisata&&$request->start_date)){
                $pendapatan =Transaksi::where('transaksis.wisata',[$request->filter_wisata])->whereDate('transaksis.created_at','>=',[$request->start_date])->whereDate('transaksis.created_at','<=',[$request->end_date])->where('jenis_pembayaran','Transfer Bank')->where('transaksis.status','Selesai')
                ->sum('total_harga');
            }else{
                $pendapatan =Transaksi::whereDate('transaksis.created_at','>=',[$request->start_date])->whereDate('transaksis.created_at','<=',[$request->end_date])->where('jenis_pembayaran','Transfer Bank')->where('transaksis.status','Selesai')
                ->sum('total_harga');
            }

                return response()->json(format_uang($pendapatan));
        }
    }
    public function pendapatan_ewalet(Request $request)
    {
        if (request()->ajax()) {
            if (!empty($request->filter_wisata&&$request->start_date===$request->end_date)){
                $pendapatan = Transaksi::where('transaksis.wisata',[$request->filter_wisata])
                ->whereDate('transaksis.created_at',[$request->start_date])->where('jenis_pembayaran','Transfer Bank')->where('transaksis.status','Selesai')
                ->sum('total_harga');
            }else if (!empty($request->filter_wisata&&$request->start_date)){
                $pendapatan =Transaksi::where('transaksis.wisata',[$request->filter_wisata])->whereDate('transaksis.created_at','>=',[$request->start_date])->whereDate('transaksis.created_at','<=',[$request->end_date])->where('jenis_pembayaran','Transfer Bank')->where('transaksis.status','Selesai')
                ->sum('total_harga');
            }else{
                $pendapatan =Transaksi::whereDate('transaksis.created_at','>=',[$request->start_date])->whereDate('transaksis.created_at','<=',[$request->end_date])->where('jenis_pembayaran','Transfer Bank')->where('transaksis.status','Selesai')
                ->sum('total_harga');
            }

                return response()->json(format_uang($pendapatan));
        }
    }
    public function item()
    {
        $bank =Bank::all();
        $ewalet =Ewalet::all();
        $wisata = Wisata::select('id','nama_wisata')->get();
        return view('page.penjualan_item',
        compact('wisata','bank','ewalet'));
    }
    public function data_item(Request $request)
    {
        if (request()->ajax()) {
            if (!empty($request->filter_wisata)&&($request->start_date)){
                if (!empty($request->filter_wisata)&&($request->start_date===$request->end_date)){
                    $item = DetailTransaksi::getcount()->where('detail_transaksi.id_wisata',[$request->filter_wisata])
                    ->whereDate('detail_transaksi.created_at',[$request->start_date])
                    ->get();
                }else{
                    $item =DetailTransaksi::getcount()->where('detail_transaksi.id_wisata',[$request->filter_wisata])->whereDate('detail_transaksi.created_at','>=',[$request->start_date])->whereDate('detail_transaksi.created_at','<=',[$request->end_date])
                    ->get();
                }
                }else{
                    $item =DetailTransaksi::getcount()->whereDate('detail_transaksi.created_at','>=',[$request->start_date])->whereDate('detail_transaksi.created_at','<=',[$request->end_date])
                    ->get();
                 }
            return datatables()->of($item)
            ->addColumn('sum', function ($item) {
                return 'Rp. '. format_uang($item->sum);
            })
            ->rawColumns(['sum'])
            ->make(true);
        }
    }
    public function kategori()
    {
 
        $wisata = Wisata::select('id','nama_wisata')->get();
        return view('page.penjualan_kategori',
        compact('wisata'));
    }
    public function data_kategori(Request $request)
    {
      if (request()->ajax()) {
        if (!empty($request->filter_wisata)&&($request->start_date)){
            if (!empty($request->filter_wisata)&&($request->start_date===$request->end_date)){
                $kategori = DetailTransaksi::getcount1()->where('detail_transaksi.id_wisata',[$request->filter_wisata])
                ->whereDate('detail_transaksi.created_at',[$request->start_date])
                ->get();
            }else{
                $kategori =DetailTransaksi::getcount1()->where('detail_transaksi.id_wisata',[$request->filter_wisata])->whereDate('detail_transaksi.created_at','>=',[$request->start_date])->whereDate('detail_transaksi.created_at','<=',[$request->end_date])
                ->get();
            }
            }else{
                $kategori =DetailTransaksi::getcount1()->whereDate('detail_transaksi.created_at','>=',[$request->start_date])->whereDate('detail_transaksi.created_at','<=',[$request->end_date])
                ->get();
             }
            return datatables()->of($kategori)
            ->addColumn('sum', function ($kategori) {
                return 'Rp. '. format_uang($kategori->sum);
            })
            ->rawColumns(['sum'])
            ->make(true);
        }
    }
    public function ringkasan()
    {
        $wisata = Wisata::select('id','nama_wisata')->get();
        return view('page.penjualan_ringkasan',
        compact('wisata'));
    }
}

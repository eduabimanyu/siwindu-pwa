<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promo;
class PromoController extends Controller
{
    public function index(Request $request)
    {
        return view('page.promo');
    }

    public function data()
    {
        $promo = Promo::orderBy('nama_promo','asc')->get();
        if (request()->ajax()) {
            return datatables()->of($promo)
                ->addColumn('aksi', function ($promo) {
                    return '
                    <div class="btn-group">
                        <button type="button" onclick="editForm(`'. route('promo.update', $promo->id_promo) .'`)" class="btn  btn-info"><i class="fa fa-pen"></i></button>
                        <button type="button" onclick="deleteData(`'. route('promo.destroy', $promo->id_promo) .'`)" class="btn  btn-danger"><i class="fa fa-trash"></i></button>
                    </div>
                    ';
                })
                ->rawColumns(['aksi'])
                ->make(true);
    }
    
}


    public function store(Request $request)
    {
        $promo = Promo::create($request->all());
        return response()->json('Data berhasil disimpan', 200);

    }

    public function show($id)
    {
        $promo = promo::find($id);

        return response()->json($promo);
    }

    
    public function edits($id)
    {


    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $promo = promo::find($id);
        $promo->update($request->all());
        return response()->json('Data berhasil disimpan', 200);
    
    }

    

    public function destroy($id)
    {
        $data = promo::find($id);
        $hapus = $data->delete();
        if ($hapus){
            return response()->json(['text'=>'Data Berhasil Dihapus'], 200);
        }else{
            return response()->json(['text'=>'Data Gagal Dihapus'], 400);
        }
    }
    
    
    public function getpromo(Request $request){
        $promo = Kategori::where("wisata",$request->wisataID)->pluck('id','nama_kategori');
        return response()->json($promo);
    }
}

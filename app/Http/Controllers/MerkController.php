<?php

namespace App\Http\Controllers;
use App\Models\kategori_asset;
use App\Models\asset;
use App\Models\merk;
use Illuminate\Http\Request;


class MerkController extends Controller
{
    public function index()
    {
        $asset = Asset:: get();
        $kasset = kategori_asset:: get();
        $merk = merk::join()->get();
        if (request()->ajax()) {
            return datatables()->of($merk)
                ->addColumn('aksi', function ($merk) {
                    $button = " <button class='edit btn  btn-info' id='" .$merk->id. "' align='center' ><i class='fa fa-pen'></i></button>";
                    $button .= " <button class='hapus btn  btn-danger ' id='" .$merk->id. "' ><i class='fa fa-trash'></button>";
                    return $button;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
        return view('page.merk',compact('asset','kasset'));
    }
    public function store(Request $request)
    {
        $simpan = merk::create($request->all());
        if ($simpan){
            return response()->json(['text'=>'Data Berhasil Disimpan'], 200);
        }else{
            return response()->json(['text'=>'Data Gagal Disimpan'], 400);
        }
    }

    public function edits(Request $request)
    {

        $data = merk::find($request->id);
        return response()->json($data);
    }

    public function updates(Request $request)
    {

        $data = merk::find($request->id);
        $simpan = $data->update($request->all());
        if ($simpan){
            return response()->json(['text'=>'Data Berhasil Disimpan'], 200);
        }else{
            return response()->json(['text'=>'Data Gagal Disimpan'], 400);
        }
    }

    public function hapus(Request $request)
    {

        $data = merk::find($request->id);
        $hapus = $data->delete($request->all());
        if ($hapus){
            return response()->json(['text'=>'Data Berhasil Dihapus'], 200);
        }else{
            return response()->json(['text'=>'Data Gagal Dihapus'], 400);
        }
    }
    
    public function getkategori(Request $request){
        $merk = asset::where("kategori",$request->kategoriID)->pluck('id','nama_asset');
        return response()->json($merk);
    }


}


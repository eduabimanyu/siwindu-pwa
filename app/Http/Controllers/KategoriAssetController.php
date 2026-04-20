<?php

namespace App\Http\Controllers;
use App\Models\kategori_asset;
use App\Models\asset;
use Illuminate\Http\Request;

class KategoriAssetController extends Controller
{
    public function index()
    {
        $asset = Asset:: get();
        $kasset = kategori_asset::orderBy('nama_kategori_asset','asc')->get();
        if (request()->ajax()) {
            return datatables()->of($kasset)
                ->addColumn('aksi', function ($kasset) {
                    $button = " <button class='edit btn  btn-info' id='" .$kasset->id. "' align='center' ><i class='fa fa-pen'></i></button>";
                    $button .= " <button class='hapus btn  btn-danger ' id='" .$kasset->id. "' ><i class='fa fa-trash'></button>";
                    return $button;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
        return view('page.asset_kategori',compact('asset'));
    }
    public function store(Request $request)
    {
        $simpan = kategori_asset::create($request->all());
        if ($simpan){
            return response()->json(['text'=>'Data Berhasil Disimpan'], 200);
        }else{
            return response()->json(['text'=>'Data Gagal Disimpan'], 400);
        }
    }

    public function edits(Request $request)
    {

        $data = kategori_asset::find($request->id);
        return response()->json($data);
    }

    public function updates(Request $request)
    {

        $data = kategori_asset::find($request->id);
        $simpan = $data->update($request->all());
        if ($simpan){
            return response()->json(['text'=>'Data Berhasil Disimpan'], 200);
        }else{
            return response()->json(['text'=>'Data Gagal Disimpan'], 400);
        }
    }

    public function hapus(Request $request)
    {

        $data = kategori_asset::find($request->id);
        $hapus = $data->delete($request->all());
        if ($hapus){
            return response()->json(['text'=>'Data Berhasil Dihapus'], 200);
        }else{
            return response()->json(['text'=>'Data Gagal Dihapus'], 400);
        }
    }

}


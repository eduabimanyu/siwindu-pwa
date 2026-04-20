<?php

namespace App\Http\Controllers;
use App\Models\asset;
use App\Models\kategori_asset;
use Illuminate\Http\Request;


class AssetController extends Controller
{
    public function index()
    {
        $kasset = kategori_asset:: get();
        $asset = Asset::join()->get();
        if (request()->ajax()) {
            return datatables()->of($asset)
                ->addColumn('aksi', function ($asset) {
                    $button = " <button class='edit btn  btn-info' id='" .$asset->id. "' align='center' ><i class='fa fa-pen'></i></button>";
                    $button .= " <button class='hapus btn  btn-danger ' id='" .$asset->id. "' ><i class='fa fa-trash'></button>";
                    return $button;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
        return view('page.asset',compact('kasset'));
    }
    public function store(Request $request)
    {
        $simpan = Asset::create($request->all());
        if ($simpan){
            return response()->json(['text'=>'Data Berhasil Disimpan'], 200);
        }else{
            return response()->json(['text'=>'Data Gagal Disimpan'], 400);
        }
    }

    public function edits(Request $request)
    {

        $data = Asset::find($request->id);
        return response()->json($data);
    }

    public function updates(Request $request)
    {

        $data = Asset::find($request->id);
        $simpan = $data->update($request->all());
        if ($simpan){
            return response()->json(['text'=>'Data Berhasil Disimpan'], 200);
        }else{
            return response()->json(['text'=>'Data Gagal Disimpan'], 400);
        }
    }

    public function hapus(Request $request)
    {

        $data = Asset::find($request->id);
        $hapus = $data->delete($request->all());
        if ($hapus){
            return response()->json(['text'=>'Data Berhasil Dihapus'], 200);
        }else{
            return response()->json(['text'=>'Data Gagal Dihapus'], 400);
        }
    }

}


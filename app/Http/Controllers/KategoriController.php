<?php

namespace App\Http\Controllers;
use App\Models\Kategori;
use App\Models\Wisata;
use Illuminate\Http\Request;
use DataTables;

class KategoriController extends Controller
{



    public function index(Request $request)
    {

        $wisata = Wisata::select('id', 'nama_wisata')->get();

        if (request()->ajax()) {
            $kategori = Kategori::get();
            return datatables()->of($kategori)
                ->addColumn('aksi', function ($kategori) {
                    $button = " <button class='edit btn  btn-info' id='" . $kategori->id . "' ><i class='fa fa-pen'></i></button>";
                    $button .= " <button class='hapus btn  btn-danger' id='" . $kategori->id . "' ><i class='fa fa-trash'></button>";
                    return $button;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
        return view('page.kategori', compact('wisata'));
    }

    public function data(Request $request)
    {
        // For API requests (from Vue PWA)
        $kategori = Kategori::select('id', 'nama_kategori', 'wisata')->get();

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json($kategori);
        }

        return $kategori;
    }

    public function store(Request $request)
    {
        $simpan = Kategori::create($request->all());
        if ($simpan) {
            return response()->json(['text' => 'Data Berhasil Disimpan'], 200);
        } else {
            return response()->json(['text' => 'Data Gagal Disimpan'], 400);
        }
    }

    public function edits(Request $request)
    {

        $data = Kategori::find($request->id);
        return response()->json($data);
    }

    public function updates(Request $request)
    {
        $data = Kategori::find($request->id);
        $simpan = $data->update($request->all());
        if ($simpan) {
            return response()->json(['text' => 'Data Berhasil Disimpan'], 200);
        } else {
            return response()->json(['text' => 'Data Gagal Disimpan'], 400);
        }
    }
    public function hapus(Request $request)
    {

        $data = kategori::find($request->id);
        $hapus = $data->delete($request->all());
        if ($hapus) {
            return response()->json(['text' => 'Data Berhasil Dihapus'], 200);
        } else {
            return response()->json(['text' => 'Data Gagal Dihapus'], 400);
        }
    }


}


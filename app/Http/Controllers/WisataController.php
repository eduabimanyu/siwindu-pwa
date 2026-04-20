<?php

namespace App\Http\Controllers;
use App\Models\Wisata;
use Illuminate\Http\Request;
use DataTables;

class WisataController extends Controller
{
    public function index()
    {
        $wisata = Wisata::orderBy('nama_wisata', 'asc')->get();

        // For API requests (from Next.js), return simple JSON
        if (request()->is('api/*')) {
            return response()->json($wisata);
        }

        // For DataTables AJAX requests (from Blade views)
        if (request()->ajax()) {
            return datatables()->of($wisata)
                ->addColumn('aksi', function ($wisata) {
                    $button = " <button class='edit btn  btn-info' id='" . $wisata->id . "' ><i class='fa fa-pen'></i></button>";
                    $button .= " <button class='hapus btn  btn-danger' id='" . $wisata->id . "' ><i class='fa fa-trash'></button>";
                    return $button;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }

        return view('page.wisata');
    }
    public function store(Request $request)
    {
        $simpan = Wisata::create($request->all());
        if ($simpan) {
            return response()->json(['text' => 'Data Berhasil Disimpan'], 200);
        } else {
            return response()->json(['text' => 'Data Gagal Disimpan'], 400);
        }
    }

    public function edits(Request $request)
    {

        $data = Wisata::find($request->id);
        return response()->json($data);
    }

    public function updates(Request $request)
    {

        $data = Wisata::find($request->id);
        $simpan = $data->update($request->all());
        if ($simpan) {
            return response()->json(['text' => 'Data Berhasil Disimpan'], 200);
        } else {
            return response()->json(['text' => 'Data Gagal Disimpan'], 400);
        }
    }


    public function hapus(Request $request)
    {

        $data = Wisata::find($request->id);
        $hapus = $data->delete($request->all());
        if ($hapus) {
            return response()->json(['text' => 'Data Berhasil Dihapus'], 200);
        } else {
            return response()->json(['text' => 'Data Gagal Dihapus'], 400);
        }
    }

}


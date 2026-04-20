<?php

namespace App\Http\Controllers;
use App\Models\Ewalet;
use Illuminate\Http\Request;

class EwaletController extends Controller
{
    public function index()
    {
        $ewalet = Ewalet::orderBy('nama_ewalet', 'asc')->get();
        if (request()->ajax()) {
            return datatables()->of($ewalet)->addIndexColumn()
                ->addColumn('aksi', function ($ewalet) {
                    $button = " <button class='edit btn  btn-info' id='" . $ewalet->id . "'  title='Edit' ><i class='fa fa-pen'></i></button>";
                    $button .= " <button class='hapus btn  btn-danger' id='" . $ewalet->id . "' title='Hapus' ><i class='fa fa-trash'></button>";
                    return $button;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
        return view('page.ewalet');
    }
    public function store(Request $request)
    {
        $simpan = ewalet::create($request->all());
        if ($simpan) {
            return response()->json(['text' => 'Data Berhasil Disimpan'], 200);
        } else {
            return response()->json(['text' => 'Data Gagal Disimpan'], 400);
        }
    }
    public function getewalet(Request $request)
    {
        // If valueSelected is provided, return single ewalet (for old functionality)
        if ($request->has('valueSelected')) {
            $data = Ewalet::find($request->valueSelected);
            return response()->json($data);
        }

        // Otherwise return all ewalets for dropdown
        $ewalets = Ewalet::orderBy('nama_ewalet', 'asc')->get();
        return response()->json($ewalets);
    }
    public function edits(Request $request)
    {

        $data = ewalet::find($request->id);
        return response()->json($data);
    }

    public function updates(Request $request)
    {
        $data = Ewalet::find($request->id);
        $simpan = $data->update($request->all());
        if ($simpan) {
            return response()->json(['text' => 'Data Berhasil Disimpan'], 200);
        } else {
            return response()->json(['text' => 'Data Gagal Disimpan'], 400);
        }
    }


    public function hapus(Request $request)
    {

        $data = ewalet::find($request->id);
        $hapus = $data->delete($request->all());
        if ($hapus) {
            return response()->json(['text' => 'Data Berhasil Dihapus'], 200);
        } else {
            return response()->json(['text' => 'Data Gagal Dihapus'], 400);
        }
    }
}

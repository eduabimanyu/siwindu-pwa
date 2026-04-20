<?php

namespace App\Http\Controllers;
use App\Models\departemen;
use Illuminate\Http\Request;


class DepartemenController extends Controller
{
    public function index()
    {
        $departemen = departemen::orderBy('nama_departemen','asc')->get();
        if (request()->ajax()) {
            return datatables()->of($departemen)
                ->addColumn('aksi', function ($departemen) {
                    $button = " <button class='edit btn  btn-info' id='" .$departemen->id. "' align='center' ><i class='fa fa-pen'></i></button>";
                    $button .= " <button class='hapus btn  btn-danger ' id='" .$departemen->id. "' ><i class='fa fa-trash'></button>";
                    return $button;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
        return view('page.departemen');
    }
    public function store(Request $request)
    {
        $simpan = departemen::create($request->all());
        if ($simpan){
            return response()->json(['text'=>'Data Berhasil Disimpan'], 200);
        }else{
            return response()->json(['text'=>'Data Gagal Disimpan'], 400);
        }
    }

    public function edits(Request $request)
    {

        $data = departemen::find($request->id);
        return response()->json($data);
    }

    public function updates(Request $request)
    {

        $data = departemen::find($request->id);
        $simpan = $data->update($request->all());
        if ($simpan){
            return response()->json(['text'=>'Data Berhasil Disimpan'], 200);
        }else{
            return response()->json(['text'=>'Data Gagal Disimpan'], 400);
        }
    }

    public function hapus(Request $request)
    {

        $data = departemen::find($request->id);
        $hapus = $data->delete($request->all());
        if ($hapus){
            return response()->json(['text'=>'Data Berhasil Dihapus'], 200);
        }else{
            return response()->json(['text'=>'Data Gagal Dihapus'], 400);
        }
    }

}


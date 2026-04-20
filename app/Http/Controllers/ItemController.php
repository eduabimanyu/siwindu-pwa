<?php

namespace App\Http\Controllers;
use App\Models\Item;
use App\Models\Wisata;
use App\Models\Kategori;
use Illuminate\Http\Request;
use DataTables;

class ItemController extends Controller
{
    public function index(Request $request)
    {

        $wisata = Wisata::select('id', 'nama_wisata')->get();
        $kategori = Kategori::select('id', 'nama_kategori')->get();
        $kategorid = Kategori::select('id', 'nama_kategori')->get();
        return view('page.item', compact('wisata', 'kategori', 'kategorid'));
    }


    public function data(Request $request)
    {
        // For API requests (from Vue PWA), use 'wisata' parameter
        $wisataFilter = $request->input('wisata') ?? $request->input('filter_wisata');
        $kategoriFilter = $request->input('filter_kategori');

        // Build query
        $query = Item::join('kategoris', 'items.kategori', '=', 'kategoris.id')
            ->select('items.*', 'items.id_item as id', 'kategoris.nama_kategori');

        // Apply wisata filter if provided
        if (!empty($wisataFilter)) {
            $query->where('items.wisata', $wisataFilter);
        }

        // Apply kategori filter if provided
        if (!empty($kategoriFilter)) {
            $query->where('items.kategori', $kategoriFilter);
        }

        $item = $query->get();

        // Check if request is API call (for POS)
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json($item);
        }

        // Return DataTables format for web views
        if (request()->ajax()) {
            return datatables()->of($item)
                ->addColumn('aksi', function ($item) {
                    return '
                    <div class="btn-group">
                        <button type="button" onclick="editForm(`' . route('item.update', $item->id_item) . '`)" class="btn  btn-info"><i class="fa fa-pen"></i></button>
                        <button type="button" onclick="deleteData(`' . route('item.destroy', $item->id_item) . '`)" class="btn  btn-danger"><i class="fa fa-trash"></i></button>
                    </div>
                    ';
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
    }


    public function store(Request $request)
    {
        $item = Item::latest()->first() ?? new Item();
        $request['kode_item'] = 'P' . tambah_nol_didepan((int) $item->id_item + 1, 6);
        $item = Item::create($request->all());
        return response()->json('Data berhasil disimpan', 200);

    }

    public function show($id)
    {
        $item = Item::find($id);

        return response()->json($item);
    }


    public function edits($id)
    {


    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $item = Item::find($id);
        $item->update($request->all());
        return response()->json('Data berhasil disimpan', 200);

    }



    public function destroy($id)
    {
        $data = Item::find($id);
        $hapus = $data->delete();
        if ($hapus) {
            return response()->json(['text' => 'Data Berhasil Dihapus'], 200);
        } else {
            return response()->json(['text' => 'Data Gagal Dihapus'], 400);
        }
    }


    public function getItem(Request $request)
    {
        $Item = Kategori::where("wisata", $request->wisataID)->pluck('id', 'nama_kategori');
        return response()->json($Item);
    }

}


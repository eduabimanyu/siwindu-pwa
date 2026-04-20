<?php

namespace App\Http\Controllers;
use App\Models\kategori_asset;
use App\Models\asset;
use App\Models\merk;
use App\Models\userasset;
use App\Models\departemen;
use App\Models\Wisata;
use App\Models\DataAsset;
use Illuminate\Http\Request;


class dataassetController extends Controller
{
    public function index(Request $request)
    {
        $asset = Asset::orderBy('nama_asset','asc')->get();
        $dataassetb = DataAsset::select('kategori')->get();
        $departemen = Departemen::orderBy('nama_departemen','asc')->get();
        $merk = merk::orderBy('nama_merk','asc')->get();
        $user = userasset::orderBy('nama_user','asc')->get();
        $wisata = Wisata::orderBy('nama_wisata','asc')->get();
        $kasset = kategori_asset::orderBy('nama_kategori_asset','asc')->get();
        if (request()->ajax()) {
            if (!empty($request->filter_kategori)&&($request->filter_departemen)&&($request->filter_kondisi)){
                $dataasset = DataAsset::join()
                ->where('data_assets.kategori',[$request->filter_kategori])
                ->where('data_assets.departemen',[$request->filter_departemen])
                ->where('data_assets.kondisi',[$request->filter_kondisi])
                ->get();
            }else if (!empty($request->filter_kategori)&&($request->filter_departemen)){
                $dataasset = DataAsset::join()
                ->where('data_assets.kategori',[$request->filter_kategori])
                ->where('data_assets.departemen',[$request->filter_departemen])
                ->get();
            }else if (!empty($request->filter_kategori)&&($request->filter_kondisi)){
                $dataasset = DataAsset::join()
                ->where('data_assets.kategori',[$request->filter_kategori])
                ->where('data_assets.kondisi',[$request->filter_kondisi])
                ->get();
            }else if (!empty($request->filter_departemen)&&($request->filter_kondisi)){
                $dataasset = DataAsset::join()
                ->where('data_assets.departemen',[$request->filter_departemen])
                ->where('data_assets.kondisi',[$request->filter_kondisi])
                ->get();
            }else if (!empty($request->filter_kategori)){
                $dataasset =DataAsset::join()->where('data_assets.kategori',[$request->filter_kategori]  )
                ->get();
            }else if (!empty($request->filter_departemen)){
                $dataasset =DataAsset::join()->where('data_assets.departemen',[$request->filter_departemen]  )
                ->get();
            }else if (!empty($request->filter_kondisi)){
                $dataasset =DataAsset::join()->where('data_assets.kondisi',[$request->filter_kondisi]  )
                ->get();
            }else{
                $dataasset =DataAsset::join()
                ->get();
            }
            return datatables()->of($dataasset)
                ->addColumn('aksi', function ($dataasset) {
                    $button = " <button class='view btn  btn-success' id='" .$dataasset->id. "' ><i class='fa fa-eye'></i></button>";
                    $button .= " <button class='edit btn  btn-info' id='" .$dataasset->id. "'  ><i class='fa fa-pen'></i></button>";
                    $button .= " <button class='hapus btn  btn-danger ' id='" .$dataasset->id. "' ><i class='fa fa-trash'></button>";
                    return $button;
                })   ->addColumn('departement', function ($dataasset) {
                    if($dataasset->departemen =='Non Departemen'){
                        return $dataasset->departemen;}
                    else{
                        return $dataasset->departemens;
                    };
                })
                ->addColumn('wisatab', function ($dataasset) {
                    if($dataasset->wisata =='Kantor Perumda'){
                        return $dataasset->wisata;}
                    else{
                        return $dataasset->wisatas;
                    };
                })
                ->addColumn('fotob', function ($dataasset) {
                  return tanggal_indonesia($dataasset->created_at, false);
                 })
                ->rawColumns(['aksi'])
                ->make(true);
        }
        return view('page.dataasset',compact('asset','kasset','user','merk','departemen','wisata','dataassetb'));
    }
    public function store(Request $request)
    {
        $data = new DataAsset();
        $data->kode_asset = $request->kode_asset;
        $data->nama_asset = $request->nama_asset;
        $data->kategori= $request->kategori;
        $data->merk = $request->merk;
        $data->tgl_pembelian = $request->tgl_pembelian;
        $data->harga = $request->harga;
        $data->wisata = $request->wisata;
        $data->departemen = $request->departemen;
        $data->user = $request->user;
        $data->kondisi = $request->kondisi;
        $data->keterangan= $request->keterangan;

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $nama = 'foto-' . date('YmdHis') . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/../../public_html/siwindu/img'), $nama);
            $data->foto = "/img/$nama";
        }

        $save = $data->save();

        if ($save){
            return response()->json(['text'=>'Data Berhasil Disimpan'], 200);
        }else{
            return response()->json(['text'=>'Data Gagal Disimpan'], 400);
        }
    
    }

    public function edits(Request $request)
    {

        $data = DataAsset::find($request->id);
        return response()->json($data);
    }

   public function view(Request $request)
    {

        $data = DataAsset::join()->where('data_assets.id',$request->id)
        ->first();
        return response()->json($data);
        
 
    }

    public function updates(Request $request)
    {

        $data = DataAsset::find($request->id);
        $data->kode_asset = $request->kode_asset;
        $data->nama_asset = $request->nama_asset;
        $data->kategori= $request->kategori;
        $data->merk = $request->merk;
        $data->tgl_pembelian = $request->tgl_pembelian;
        $data->harga = $request->harga;
        $data->wisata = $request->wisata;
        $data->departemen = $request->departemen;
        $data->user = $request->user;
        $data->kondisi = $request->kondisi;
        $data->keterangan= $request->keterangan;

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $nama = 'foto-' . date('YmdHis') . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/../../public_html/siwindu/img'), $nama);
            $data->foto = "/img/$nama";
        }
        
        $simpan = $data->update();
        if ($simpan){
            return response()->json(['text'=>'Data Berhasil Disimpan'], 200);
        }else{
            return response()->json(['text'=>'Data Gagal Disimpan'], 400);
        }
    }

    public function hapus(Request $request)
    {

        $data = DataAsset::find($request->id);
        $hapus = $data->delete($request->all());
        if ($hapus){
            return response()->json(['text'=>'Data Berhasil Dihapus'], 200);
        }else{
            return response()->json(['text'=>'Data Gagal Dihapus'], 400);
        }
    }
    public function getkategoris(Request $request){
        $merk = merk::where("kategori",$request->kategorisID)->pluck('id','nama_merk');
        return response()->json($merk);
    }
    public function getmerk(Request $request){
        $merk = asset::where("kategori",$request->merkID)->pluck('id','nama_merk');
        return response()->json($merk);
    }

}


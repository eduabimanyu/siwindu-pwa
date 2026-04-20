<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class DataAsset extends Model
{
    use HasFactory;
    protected $table = 'data_assets';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public static function join()
    {
        $data = DB::table('data_assets')->orderBy('nama_asset','asc')
        ->leftjoin('assets','data_assets.nama_asset','assets.id')
        ->leftjoin('kategori_assets','data_assets.kategori','kategori_assets.id')
        ->leftjoin('merks','data_assets.merk','merks.id')
        ->leftjoin('departemens','data_assets.departemen','departemens.id')
        ->leftjoin('wisatas','data_assets.wisata','wisatas.id')
        ->leftjoin('userassets','data_assets.user','userassets.id')
        ->select('data_assets.*','assets.nama_asset as asset',
        'kategori_assets.nama_kategori_asset as kasset',
        'departemens.nama_departemen as departemens',
        'userassets.nama_user as users','wisatas.nama_wisata as wisatas','merks.nama_merk as merks');  
        return $data;
    }
}

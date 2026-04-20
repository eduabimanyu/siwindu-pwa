<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class merk extends Model
{
    use HasFactory;
    protected $table = 'merks';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public static function join()
    {
        $data = DB::table('merks')->orderBy('nama_merk','asc')
        ->leftjoin('assets','merks.nama_asset','assets.id')
        ->leftjoin('kategori_assets','merks.kategori','kategori_assets.id')
        ->select('merks.*','assets.nama_asset as asset','kategori_assets.nama_kategori_asset as kasset');  
        return $data;
    }
}

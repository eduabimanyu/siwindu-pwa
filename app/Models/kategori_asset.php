<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class kategori_asset extends Model
{
    use HasFactory;
    protected $table = 'kategori_assets';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public static function join()
    {
        $data = DB::table('kategori_assets')->orderBy('nama_kategori_asset','asc')
        ->join('assets','kategori_assets.nama_asset','assets.id')
        ->select('kategori_assets.*','assets.nama_asset as kasset');  
        return $data;
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class asset extends Model
{
    use HasFactory;

    protected $table = 'assets';
    protected $primaryKey = 'id';
    protected $guarded = [];
    
      public static function join()
    {
        $data = DB::table('assets')->orderBy('nama_asset','asc')
        ->leftjoin('kategori_assets','assets.kategori','kategori_assets.id')
        ->select('assets.*','kategori_assets.nama_kategori_asset as kasset');  
        return $data;
    }
}

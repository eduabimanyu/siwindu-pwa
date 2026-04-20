<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Item extends Model
{
    use HasFactory;

    protected $table = 'items';
    protected $primaryKey = 'id_item';
    protected $guarded = [];

    public static function join()
    {
        $data = DB::table('items')->orderBy('kode_item','asc')
        ->join('wisatas','items.wisata','wisatas.id')
        ->join('kategoris','items.kategori','kategoris.id')
        ->select('items.*','wisatas.nama_wisata as wisatas','kategoris.nama_kategori as kategoris');
        return $data;
    }

    public function shiftdetail()
    {
        return $this->hasMany(Shiftdetail::class);
    }
}


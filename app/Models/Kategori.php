<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Kategori extends Model
{
    use HasFactory;
    protected $fillable = [
        'wisata',
        'nama_kategori',
    ];

    public static function join()
        {
            $data = DB::table('kategoris')->orderBy('nama_kategori','asc')
            ->join('wisatas','kategoris.wisata','wisatas.id')
            ->select('kategoris.*','wisatas.nama_wisata as wisatas');  
            return $data;
        }

}


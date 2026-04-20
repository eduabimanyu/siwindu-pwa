<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DetailTransaksi extends Model
{
    use HasFactory;
    protected $table = 'detail_transaksi';
    protected $primaryKey = 'id_transaksi_detail';
    protected $guarded = [];

    public function item()
    {
        return $this->hasOne(Item::class, 'id_item', 'id_item');
    }
    public static function join()
    {
        $data = DB::table('detail_transaksi')->orderBy('created_at','desc')
        ->join('transaksis','detail_transaksi.id_transaksi','transaksis.id_transaksi')
        ->select('detail_transaksi.*','transaksis.id_transaksi as id_transaksi');
        return $data;
    }
    public static function getcount()
    {
        $data = DB::table('detail_transaksi')
            ->orderBy('id_item','asc')
            ->join('wisatas','detail_transaksi.id_wisata','wisatas.id')
            ->join('kategoris','detail_transaksi.id_kategori','kategoris.id')
            ->join('items','detail_transaksi.id_item','items.id_item')
            ->select('detail_transaksi.*',DB::raw('count(detail_transaksi.id_item) as count'),
              DB::raw('sum(detail_transaksi.subtotal) as sum'),'wisatas.nama_wisata as wisatas','kategoris.nama_kategori as kategoris','items.nama_item')
            ->groupBy('detail_transaksi.id_item');
            return $data;
    }

    public static function getcount1()
    {
        $data = DB::table('detail_transaksi')
            ->orderBy('id_kategori','asc')
            ->join('wisatas','detail_transaksi.id_wisata','wisatas.id')
            ->join('kategoris','detail_transaksi.id_kategori','kategoris.id')
            ->select('detail_transaksi.*',DB::raw('count(detail_transaksi.id_kategori) as count'),
              DB::raw('sum(detail_transaksi.subtotal) as sum'),'wisatas.nama_wisata as wisatas','kategoris.nama_kategori as kategoris','kategoris.nama_kategori')
            ->groupBy('detail_transaksi.id_kategori');
            return $data;
    }

}



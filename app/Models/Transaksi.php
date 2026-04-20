<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class transaksi extends Model
{
   use HasFactory;

    protected $table = 'transaksis';
    protected $primaryKey = 'id_transaksi';
    protected $guarded = [];

    public function member()
    {
        return $this->hasOne(Member::class, 'id_member', 'id_member');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'id_user');
    }

    public static function join()
    {
        $data = DB::table('transaksis')->orderBy('created_at','desc')
        ->join('wisatas','transaksis.wisata','wisatas.id')
        ->join('users','transaksis.id_user','users.id')
        ->leftjoin('banks','transaksis.bank','banks.id')
        ->leftjoin('ewalets','transaksis.ewalet','ewalets.id')
        ->select('transaksis.*','wisatas.nama_wisata as wisatas','users.name as users','banks.nama_bank as banks','banks.nomor_rekening as norek','banks.atas_nama as an','ewalets.nama_ewalet as ewalet','ewalets.nomor_hp','ewalets.atas_nama');
        return $data;
    }

    public static function join1()
    {
        $data = DB::table('transaksis')->orderBy('created_at','desc')
        ->join('wisatas','transaksis.wisata','wisatas.id')
        ->join('users','transaksis.id_user','users.id')
        ->select('transaksis.*','wisatas.nama_wisata as wisatas','users.name as users');
        return $data;
    }
    public static function joinexcel()
    {
        $data = DB::table('transaksis')->orderBy('transaksis.created_at','desc')
        ->join('wisatas','transaksis.wisata','wisatas.id')
        ->join('users','transaksis.id_user','users.id')
        // ->where('transaksis.status','Selesai')
        ->select('wisatas.nama_wisata as wisatas','transaksis.total_item',
        'transaksis.diskon','transaksis.total_harga','transaksis.jenis_pembayaran','users.name as users');
        return $data;
    }

    public function trans(Request $request)
    {
        $data= \DB::table('transaksis')
        ->select([
            \DB::raw('count(*) as wisata'),

        ]);
        return $data;

    }
   
}



<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class shift extends Model
{
    use HasFactory;

    protected $table = 'shifts';
    protected $primaryKey = 'id_shift';
    protected $guarded = [];

public static function join()
    {
        $data = DB::table('shifts')->orderBy('start','desc')
        ->join('wisatas','shifts.wisata','wisatas.id')
        ->join('users','shifts.kasir','users.id')
        ->select('shifts.*','wisatas.nama_wisata as wisatas','users.name as users');
        return $data;
    }

    public function user()
    {
        return $this->belongsTo(User::class,'kasir','id');
    }

    public function wisatashift()
    {
        return $this->belongsTo(Wisata::class,'wisata','id');
    }

    public function shiftdetail()
    {
        return $this->belongsTo(Shift::class,'id_shift','id_shift');
    }
}

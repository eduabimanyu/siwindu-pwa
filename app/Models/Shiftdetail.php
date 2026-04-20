<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class shiftdetail extends Model
{
    use HasFactory;

    protected $table = 'shiftdetails';
    protected $primaryKey = 'id_shiftdetail';
    protected $guarded = [];

    public function shift()
    {
        return $this->belongsTo(Shift::class,'id_shift','id_shift');
    }
    public function shiftdetail()
    {
        return $this->hasMany(Shiftdetail::class);
    }
    public function item()
    {
        return $this->belongsTo(Item::class,'id_item','id_item');
    }

    public static function getcount()
    {
        $data = DB::table('shiftdetails')
            ->orderBy('id_item','asc')
            ->join('items','shiftdetails.id_item','items.id_item')
            ->select('shiftdetails.*',DB::raw('count(shiftdetails.id_item) as count'),
              DB::raw('sum(shiftdetails.jumlah) as sum'),DB::raw('sum(shiftdetails.subtotal) as sumtotal'),
              'items.nama_item')
            ->groupBy('shiftdetails.id_item');
            return $data;
    }
  
}

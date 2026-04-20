<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ewalet extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama_ewalet',
        'nomor_hp',
        'atas_nama',
    ];
}

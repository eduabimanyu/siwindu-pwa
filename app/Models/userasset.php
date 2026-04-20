<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class userasset extends Model
{
    use HasFactory;

    protected $table = 'userassets';
    protected $primaryKey = 'id';
    protected $guarded = [];
}

<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laratrust\Traits\LaratrustUserTrait;
use Illuminate\Support\Facades\DB;
class User extends Authenticatable
{
    use LaratrustUserTrait;
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'wisata',
        'id_shift',
    ];


    public static function join()
    {
         $data = DB::table('users')->orderBy('wisata','asc')
        ->leftjoin('wisatas','users.wisata','wisatas.id')
        ->select('users.*','wisatas.nama_wisata as wisatas');
        return $data ;
    }
    public function shiftkerja()
    {
        return $this->hasMany(Shift::class);
    }

    public function id_wisata()
    {
        return $this->belongsTo(Wisata::class,'wisata','id');
    }
    
        public function id_role()
    {
        return $this->belongsTo(Role::class,'role','id');
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}

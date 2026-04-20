<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Wisata;

class ProfileController extends Controller
{
    public function index()
    {
        $wisata = Wisata::get();
        $profil = auth()->user();
        $role = Role::all();
          foreach ($profil->roles as $roley ) {
          $roles = $roley->id;
        }
        return view('page.profile',compact('wisata','roles','profil','role'));
    }

     public function update(Request $request)
     {
        $user = auth()->user();
        $user->name = $request->nama;
        $user->email = $request->email;
        $user->wisata= $request->wisata;
        if ($request->has('password') && $request->password != "") {
                if ($request->password == $request->password_confirmation) {
                    $user->password = bcrypt($request->password);
                } else {
                    return response()->json('Konfirmasi password tidak sesuai', 422);
                }
            } 
        $save = $user->update();
        if($save){
        $user->syncRoles(explode(',',$request->role));
        return response()->json('Data berhasil disimpan', 200);
        }
    }
}


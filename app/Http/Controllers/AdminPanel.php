<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Role;
use App\Models\Wisata;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminPanel extends Controller
{
    public function index()
    {
        $role = Role::get();
        $wisata = Wisata::get();
        $data = User::first()->with('id_role')->with('id_wisata')->orderby('wisata','desc');
        if (request()->ajax()) {
            return datatables()->of($data)
                ->addColumn('aksi', function ($data) {
                    $button = ' <button class="info btn  btn-info" id="' . $data->id . '" name="info" data-toggle="modal"  data-target="#btn-info" >Info</button>';
                    $button .= " <button class='hapus btn  btn-danger' id='" . $data->id . "' >Hapus</button>";
                    return $button;
                }) ->addColumn('roles', function ($data) {
                        // Pastikan relasi id_wisata sudah dimuat
                        if ($data->id_role) {
                            return $data->id_role->display_name; // Menampilkan display_name dari relasi id_wisata
                        }
                        return 'No roles available'; // Menangani jika tidak ada relasi id_wisata
                    })
                ->addColumn('wisatas', function ($data) {
                        // Pastikan relasi id_wisata sudah dimuat
                        if ($data->id_wisata) {
                            return $data->id_wisata->nama_wisata;
                        }
                        return 'Semua Wisata'; // Menangani jika tidak ada relasi id_wisata
                    })
                ->rawColumns(['aksi'])
                ->make(true);
        }
        return view('page.manageuser',compact('data','role','wisata'));
    }
    public function store(Request $request)
    {
        $rules = [
            'username' =>'required|max:15',
            'email' =>'required|unique:users,email',
            'password' =>'required',
            'role' =>'required',
            'wisata' =>'required',
        ];
        $text = [
            'username.required' =>'Kolom Username tidak boleh kosong',
            'email.required' =>'Kolom Email tidak boleh kosong',
            'email.unique' =>'Email Sudah Terdaftar',
            'password.required' =>'Kolom password tidak boleh kosong',
            'role.required' =>'Role Harus Dipilih',
            'wisata.required' =>'Wisata Harus Dipilih',
        ];

        $validasi =Validator::make($request->all(), $rules, $text);
        if($validasi->fails()){
            return response()->json(['success'=>0, 'text'=>$validasi->errors()->first(),422]);
        }

        $data = new User();
        $data->email = $request->email;
        $data->name = $request->username;
        $data->password = Hash::make($request->password);
        $data->wisata = $request->wisata;
        $data->role = $request->role;
        $save = $data->save();

        //set role
        if ($save){
            $data->attachRole($request->role);
            return response()->json(['text' => 'sip'],200 );
        }
    }
    public function info(Request $request)
    {
      $id =$request->id;
      $user = User::find($id);
      $role = Role::all();

      foreach ($user->roles as $role ) {
        $roles = $role->display_name;
      }
      foreach ($user->roles as $role ) {
        $roled = $role->id;
      }
        return response()->json(['user'=>$user, 'roles'=> $roles,'roled'=> $roled]);
    }
    public function edit(Request $request)
    {
        $rules = [
            'usernames' =>'required|max:15',
            'roles' =>'required',

        ];
        $text = [
            'usernames.required' =>'Kolom Username tidak boleh kosong',
            'roles.required' =>'Role Harus Dipilih',
        ];

        $validasi =Validator::make($request->all(), $rules, $text);
        if($validasi->fails()){
            return response()->json(['success'=>0, 'text'=>$validasi->errors()->first(),422]);
        }
        $data= [
            'name'=> $request->usernames,
            'email'=>$request->emails,
            'wisata'=>$request->wisatas,
            'role'=>$request->roles,
            'password'=>Hash::make($request->passwordbaru),
        ];
        $user = User::find($request->id);
        $save = $user->update($data);
        if ($save){
            $user->syncRoles(explode(',',$request->roles));
            return response()->json(['text'=>'Berhasil Dirubah']);
        }
    }
    public function permission()
    {
        $permission = Permission::all();
        if (request()->ajax()) {
            return datatables()->of($permission)
                ->addColumn('aksi', function ($permission) {
                    $button = ' <button class="info btn  btn-warning" id="' . $permission->id . '" name="info" data-toggle="modal"  data-target="#btn-info" >Info</button>';
                    $button .= " <button class='hapus btn  btn-danger' id='" . $permission->id . "' >Hapus</button>";
                    return $button;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
        return view('page.permission');
    }
    public function hapus(Request $request)
    {

        $data = User::find($request->id);
        $hapus = $data->delete($request->all());
        if ($hapus){
            return response()->json(['text'=>'Data Berhasil Dihapus'], 200);
        }else{
            return response()->json(['text'=>'Data Gagal Dihapus'], 400);
        }
    }

}

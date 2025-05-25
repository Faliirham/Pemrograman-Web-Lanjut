<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $namaLevel = $user->level ? $user->level->level_nama : 'Tidak ada level'; // Pastikan ada pengecekan jika level null
        return view('profile.index', [
            'user' => $user,
            'activeMenu' => 'profile',
            'breadcrumb' => (object)[
                'title' => 'Profil Saya',
                'menu' => 'Profile',
                'submenu' => 'Profil Saya',
                'list' => ['Profile', 'Profil Saya']
            ]
        ]);
    }

    public function upload()
    {
        return view('profile.upload');
    }

    public function upload_ajax(Request $request)
{
    if ($request->ajax() || $request->wantsJson()) {
        // Validasi file
        $validator = Validator::make($request->all(), [
            'foto_profile' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        try {
            // Cek apakah user login
            if (!Auth::check()) {
                return response()->json([
                    'status' => false,
                    'message' => 'User belum login.'
                ]);
            }

            // Ambil file dan beri nama unik
            $file = $request->file('foto_profile');
            $imageName = time() . '.' . $file->getClientOriginalExtension();

            // Simpan ke storage/app/public/foto_profil
            $file->storeAs('public/foto_profil', $imageName);

            // Update field profile_photo di database
            UserModel::find(Auth::id())->update([
                'foto' => $imageName
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Upload berhasil.',
                'image' => $imageName
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    return redirect('/profile');
}
}
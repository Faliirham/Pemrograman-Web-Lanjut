<?php

namespace App\Http\Controllers;
use App\Models\LevelModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class LevelController extends Controller
{
    public function index()
     {
         $breadcrumb = (object) [
             'title' => 'Daftar Level',
             'list' => ['Home', 'Level']
         ];

         $page = (object) [
            'title' => 'Daftar level dalam sistem'
        ];

        $activeMenu = 'level'; // Set menu yang sedang aktif
         $levels = LevelModel::all(); 
         return view('level.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'levels'=>$levels]);
     }
     public function list(Request $request)
     {
         $levels = LevelModel::select('level_id', 'level_kode', 'level_nama');
     
         // Filter berdasarkan level_id jika ada
         if ($request->level_id) {
             $levels->where('level_id', $request->level_id);
         }
     
         return DataTables::of($levels)
             ->addIndexColumn()
             ->addColumn('aksi', function ($level) {
                 $btn = '<a href="' . url('/level/' . $level->level_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                 $btn .= '<a href="' . url('/level/' . $level->level_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                 $btn .= '<form class="d-inline-block" method="POST" action="' . url('/level/' . $level->level_id) . '">';
                 $btn .= csrf_field() . method_field('DELETE');
                 $btn .= '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\')">Hapus</button></form>';
                 return $btn;
             })
             ->rawColumns(['aksi'])
             ->make(true);
     }
 
      // Menampilkan halaman form tambah level
      public function create()
      {
          $breadcrumb = (object) [
              'title' => 'Tambah level',
              'list' => ['Home', 'level', 'Tambah']
          ];
  
          $page = (object) [
              'title' => 'Tambah level baru'
          ];
  
          $levels = LevelModel::all(); // ambil data level untuk ditampilkan di form
          $activeMenu = 'level'; // set menu yang sedang aktif
  
          return view('level.create', [
              'breadcrumb' => $breadcrumb,
              'page' => $page,
              'level' => $levels,
              'activeMenu' => $activeMenu
          ]);
      }
 
      // Menyimpan data level baru
         public function store(Request $request)
         {
     // Validasi input
     $request->validate([
         'level_kode' => 'required|string|min:2|unique:m_level,level_kode',
         'level_nama' => 'required|string|max:100'
     ]);
 
     // Simpan ke database
     LevelModel::create([
         'level_kode' => $request->level_kode,
         'level_nama' => $request->level_nama
     ]);
 
     // Redirect dengan pesan sukses
     return redirect('/level')->with('success', 'Data level berhasil disimpan');
     }
 
     
     // Menampilkan detail user
     public function show(string $id)
     {
         $levels = LevelModel::find($id);
 
         $breadcrumb = (object) [
             'title' => 'Detail level',
             'list' => ['Home', 'level', 'Detail']
         ];
 
         $page = (object) [
             'title' => 'Detail level'
         ];
 
         $activeMenu = 'level'; // set menu yang sedang aktif
 
         return view('level.show', [
             'breadcrumb' => $breadcrumb,
             'page' => $page,
             'level' => $levels,
             'activeMenu' => $activeMenu
         ]);
     }
 
     // Menampilkan halaman form edit level
 public function edit(string $id)
 {
     $levels = LevelModel::find($id);
 
     if (!$levels) {
         return redirect('/level')->with('error', 'Data level tidak ditemukan');
     }
 
     $breadcrumb = (object) [
         'title' => 'Edit Level',
         'list' => ['Home', 'Level', 'Edit']
     ];
 
     $page = (object) [
         'title' => 'Edit Level'
     ];
 
     $activeMenu = 'level'; // set menu yang sedang aktif
 
     return view('level.edit', [
         'breadcrumb' => $breadcrumb,
         'page' => $page,
         'level' => $levels,
         'activeMenu' => $activeMenu
     ]);
 }
 
 // Menyimpan perubahan data level
 public function update(Request $request, string $id)
 {
     $request->validate([
         'level_kode' => 'required|string|max:50|unique:m_level,level_kode,' . $id . ',level_id',
         'level_nama' => 'required|string|max:100'
     ]);
 
     $levels = LevelModel::find($id);
     
     if (!$levels) {
         return redirect('/level')->with('error', 'Data level tidak ditemukan');
     }
 
     $levels->update([
         'level_kode' => $request->level_kode,
         'level_nama' => $request->level_nama
     ]);
 
     return redirect('/level')->with('success', 'Data level berhasil diubah');
 }
 
 // Menghapus data level
 public function destroy(string $id)
 {
     $check = LevelModel::find($id);
     if (!$check) { // Cek apakah data level dengan ID yang dimaksud ada atau tidak
         return redirect('/level')->with('error', 'Data level tidak ditemukan');
     }
 
     try {
         LevelModel::destroy($id); // Hapus data level
 
         return redirect('/level')->with('success', 'Data level berhasil dihapus');
     } catch (\Illuminate\Database\QueryException $e) {
         // Jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
         return redirect('/level')->with('error', 'Data level gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
     }
 }
}


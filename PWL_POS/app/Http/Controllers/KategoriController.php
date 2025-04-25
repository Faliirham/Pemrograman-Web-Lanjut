<?php

namespace App\Http\Controllers;
use App\Models\KategoriModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class KategoriController extends Controller
{
    public function index()
     {
         $breadcrumb = (object) [
             'title' => 'Daftar Kategori',
             'list' => ['Home', 'Kategori']
         ];
         $page = (object) [
            'title' => 'Daftar kategori dalam sistem'
        ];

        $activeMenu = 'kategori'; // Set menu yang sedang aktif
        $kategoris = KategoriModel::all(); 
        return view('kategori.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'kategoris' => $kategoris]);
    }

    // Ambil data kategori dalam bentuk JSON untuk DataTables
    public function list(Request $request)
    {
        $kategoris = KategoriModel::select('kategori_id', 'kategori_kode', 'kategori_nama');

        // Filter berdasarkan kategori_id jika ada
        if ($request->kategori_id) {
            $kategoris->where('kategori_id', $request->kategori_id);
        }

        return DataTables::of($kategoris)
            ->addIndexColumn()
            ->addColumn('aksi', function ($kategori) {
                $btn = '<a href="' . url('/kategori/' . $kategori->kategori_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="' . url('/kategori/' . $kategori->kategori_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="' . url('/kategori/' . $kategori->kategori_id) . '">';
                $btn .= csrf_field() . method_field('DELETE');
                $btn .= '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\')">Hapus</button></form>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    // Menampilkan halaman form tambah kategori
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Kategori',
            'list' => ['Home', 'Kategori', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah kategori baru'
        ];

        $activeMenu = 'kategori';

        return view('kategori.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    // Menyimpan data kategori baru
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'kategori_kode' => 'required|string|min:2|unique:m_kategori,kategori_kode',
            'kategori_nama' => 'required|string|max:100'
        ]);

        // Simpan ke database
        KategoriModel::create([
            'kategori_kode' => $request->kategori_kode,
            'kategori_nama' => $request->kategori_nama
        ]);

        return redirect('/kategori')->with('success', 'Data kategori berhasil disimpan');
    }

    // Menampilkan detail kategori
    public function show(string $id)
    {
        $kategori = KategoriModel::find($id);

        if (!$kategori) {
            return redirect('/kategori')->with('error', 'Data kategori tidak ditemukan');
        }

        $breadcrumb = (object) [
            'title' => 'Detail Kategori',
            'list' => ['Home', 'Kategori', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail kategori'
        ];

        $activeMenu = 'kategori';

        return view('kategori.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'kategori' => $kategori,
            'activeMenu' => $activeMenu
        ]);
    }

    // Menampilkan halaman form edit kategori
    public function edit(string $id)
    {
        $kategori = KategoriModel::find($id);

        if (!$kategori) {
            return redirect('/kategori')->with('error', 'Data kategori tidak ditemukan');
        }

        $breadcrumb = (object) [
            'title' => 'Edit Kategori',
            'list' => ['Home', 'Kategori', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit Kategori'
        ];

        $activeMenu = 'kategori';

        return view('kategori.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'kategori' => $kategori,
            'activeMenu' => $activeMenu
        ]);
    }

    // Menyimpan perubahan data kategori
    public function update(Request $request, string $id)
    {
        $request->validate([
            'kategori_kode' => 'required|string|max:10|unique:m_kategori,kategori_kode,' . $id . ',kategori_id',
            'kategori_nama' => 'required|string|max:100'
        ]);

        $kategori = KategoriModel::find($id);
        
        if (!$kategori) {
            return redirect('/kategori')->with('error', 'Data kategori tidak ditemukan');
        }

        $kategori->update([
            'kategori_kode' => $request->kategori_kode,
            'kategori_nama' => $request->kategori_nama
        ]);

        return redirect('/kategori')->with('success', 'Data kategori berhasil diubah');
    }
    public function destroy(string $id)
    {
        $check = KategoriModel::find($id);
        if (!$check) {
            return redirect('/kategori')->with('error', 'Data kategori tidak ditemukan');
        }
        try {
            KategoriModel::destroy($id);
            return redirect('/kategori')->with('success', 'Data kategori berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/kategori')->with('error', 'Data kategori gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}
<?php
 
 namespace App\Http\Controllers;
 
 use App\Models\BarangModel;
 use App\Models\KategoriModel;
 use Illuminate\Http\Request;
 use Yajra\DataTables\Facades\DataTables;
 
 class BarangController extends Controller
 {
     public function index()
     {
         $breadcrumb = (object) [
             'title' => 'Daftar Barang',
             'list' => ['Home', 'Barang']
         ];
 
         $page = (object) [
             'title' => 'Daftar barang dalam sistem'
         ];
 
         $activeMenu = 'barang';
         $kategori = KategoriModel::all(); 
 
         return view('barang.index', compact('breadcrumb', 'page', 'activeMenu', 'kategori'));
     }
 
     public function list(Request $request)
     {
         $barang = BarangModel::select('barang_id', 'barang_kode', 'barang_nama', 'kategori_id', 'harga_beli', 'harga_jual');
 
         if ($request->kategori_id) {
             $barang->where('kategori_id', $request->kategori_id);
         }
 
         return DataTables::of($barang)
             ->addIndexColumn()
             ->addColumn('aksi', function ($barang) {
                 return '
                     <a href="' . url('/barang/' . $barang->barang_id) . '" class="btn btn-info btn-sm">Detail</a> 
                     <a href="' . url('/barang/' . $barang->barang_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> 
                     <form class="d-inline-block" method="POST" action="' . url('/barang/' . $barang->barang_id) . '">
                         ' . csrf_field() . method_field('DELETE') . '
                         <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\')">Hapus</button>
                     </form>';
             })
             ->rawColumns(['aksi'])
             ->make(true);
     }
 
     public function create()
     {
         $breadcrumb = (object) [
             'title' => 'Tambah Barang',
             'list' => ['Home', 'Barang', 'Tambah']
         ];
 
         $page = (object) [
             'title' => 'Tambah barang baru'
         ];
 
         $activeMenu = 'barang';
         $kategori = KategoriModel::all();
 
         return view('barang.create', compact('breadcrumb', 'page', 'activeMenu', 'kategori'));
     }
 
     public function store(Request $request)
     {
         $request->validate([
             'barang_kode' => 'required|string|min:2|unique:m_barang,barang_kode',
             'barang_nama' => 'required|string|max:100',
             'kategori_id' => 'required|exists:m_kategori,kategori_id',
             'harga_beli' => 'required|numeric',
             'harga_jual' => 'required|numeric'
         ]);
 
         BarangModel::create($request->all());
 
         return redirect('/barang')->with('success', 'Data barang berhasil disimpan');
     }
 
     public function show(string $id)
     {
         $barang = BarangModel::find($id);
 
         if (!$barang) {
             return redirect('/barang')->with('error', 'Data barang tidak ditemukan');
         }
 
         $breadcrumb = (object) [
             'title' => 'Detail Barang',
             'list' => ['Home', 'Barang', 'Detail']
         ];
 
         $page = (object) [
             'title' => 'Detail barang'
         ];
 
         $activeMenu = 'barang';
 
         return view('barang.show', compact('breadcrumb', 'page', 'barang', 'activeMenu'));
     }
 
     public function edit(string $id)
     {
         $barang = BarangModel::find($id);
 
         if (!$barang) {
             return redirect('/barang')->with('error', 'Data barang tidak ditemukan');
         }
 
         $breadcrumb = (object) [
             'title' => 'Edit Barang',
             'list' => ['Home', 'Barang', 'Edit']
         ];
 
         $page = (object) [
             'title' => 'Edit Barang'
         ];
 
         $activeMenu = 'barang';
         $kategori = KategoriModel::all();
 
         return view('barang.edit', compact('breadcrumb', 'page', 'barang', 'activeMenu', 'kategori'));
     }
 
     public function update(Request $request, string $id)
     {
         $request->validate([
             'barang_kode' => 'required|string|max:10|unique:m_barang,barang_kode,' . $id . ',barang_id',
             'barang_nama' => 'required|string|max:100',
             'kategori_id' => 'required|exists:m_kategori,kategori_id',
             'harga_beli' => 'required|numeric',
             'harga_jual' => 'required|numeric'
         ]);
 
         $barang = BarangModel::find($id);
 
         if (!$barang) {
             return redirect('/barang')->with('error', 'Data barang tidak ditemukan');
         }
 
         $barang->update($request->all());
 
         return redirect('/barang')->with('success', 'Data barang berhasil diubah');
     }
 
     public function destroy(string $id)
     {
         $barang = BarangModel::find($id);
         if (!$barang) {
             return redirect('/barang')->with('error', 'Data barang tidak ditemukan');
         }
 
         try {
             $barang->delete();
             return redirect('/barang')->with('success', 'Data barang berhasil dihapus');
         } catch (\Illuminate\Database\QueryException $e) {
             return redirect('/barang')->with('error', 'Data barang gagal dihapus karena masih digunakan di tabel lain');
         }
     }
 }
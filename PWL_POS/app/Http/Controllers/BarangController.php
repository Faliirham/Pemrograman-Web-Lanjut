<?php
 
 namespace App\Http\Controllers;
 
 use Illuminate\Http\Request;
 use Yajra\DataTables\Facades\DataTables;
 use App\Models\BarangModel;
 use App\Models\KategoriModel;
 use Illuminate\Support\Facades\Hash;
 use Illuminate\Support\Facades\Validator;
 use PhpOffice\PhpSpreadsheet\IOFactory;
 
 class BarangController extends Controller
 {
    public function index()
    {
        $activeMenu = 'barang'; 
        $breadcrumb = (object) [ 
            'title' => 'Data Barang', 
            'list' => ['Home', 'Barang'] 
        ]; 
 
        $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get(); 
        return view('barang.index', [ 
            'activeMenu' => $activeMenu, 
            'breadcrumb' => $breadcrumb, 
            'kategori' => $kategori 
        ]); 
    }
 
    public function list(Request $request) 
    { 
        $barang = BarangModel::select(
            'barang_id', 
            'barang_kode', 
            'barang_nama', 
            'harga_beli', 
            'harga_jual', 
            'kategori_id'
        )->with('kategori');

        $kategori_id = $request->input('filter_kategori'); 
        if (!empty($kategori_id)) { 
            $barang->where('kategori_id', $kategori_id); 
        } 

        return DataTables::of($barang) 
            ->addIndexColumn() 
            ->addColumn('aksi', function ($barang) {
                $btn = '<button onclick="modalAction(\'' . url('/barang/' . $barang->barang_id ) . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/barang/' . $barang->barang_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/barang/' . $barang->barang_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            }) 
            ->rawColumns(['aksi']) 
            ->make(true); 
    }
    
 
     // Menampilkan halaman form tambah barang
     public function create()
     {
         $breadcrumb = (object) [
             'title' => 'Tambah Barang',
             'list' => ['Home', 'Barang', 'Tambah']
         ];
 
         $page = (object) [
             'title' => 'Tambah barang baru'
         ];
 
         $kategori   = KategoriModel::all(); // Ambil data kategori untuk ditampilkan di form
         $activeMenu = 'barang'; // Set menu yang sedang aktif
 
         return view('barang.create', [
             'breadcrumb' => $breadcrumb,
             'page' => $page,
             'kategori' => $kategori,
             'activeMenu' => $activeMenu
         ]);
     }
 
     // Menyimpan data barang baru
     public function store(Request $request)
     {
         $request->validate([
             'kode_barang' => 'required|string|min:3|unique:m_barang',
             'nama_barang' => 'required|string|max:100',
             'harga_beli' => 'required|numeric',
             'harga_jual' => 'required|numeric',
             'kategori_id' => 'required|integer'
         ]);
 
         BarangModel::create([
             'kode_barang' => $request->kode_barang,
             'nama_barang' => $request->nama_barang,
             'harga_beli' => $request->harga_beli,
             'harga_jual' => $request->harga_jual,
             'kategori_id' => $request->kategori_id
         ]);
 
         return redirect('/barang')->with('success', 'Data barang berhasil disimpan');
     }

     public function create_ajax() {
        $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get();

        return view('barang.create_ajax')
                    ->with('kategori', $kategori);
    }

    public function store_ajax(Request $request) {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kode_barang' => 'required|string|max:6',
                'nama_barang' => 'required|string|max:100',
                'harga_beli' => 'required|integer',
                'harga_jual' => 'required|integer|gte:harga_beli',
                'kategori_id' => 'required|exists:m_kategori,kategori_id'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  =>'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            BarangModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data barang berhasil disimpan'
            ]);
        }
        redirect('/');
    }
 
     // Menampilkan detail barang
     public function show(string $id)
     {
         // Ambil data barang berdasarkan ID dengan relasi kategori
         $barang = BarangModel::with('kategori')->find($id);
 
         // Jika barang tidak ditemukan, tampilkan halaman 404
         if (!$barang) {
             abort(404, 'Barang tidak ditemukan');
         }
 
         // Konfigurasi breadcrumb untuk navigasi
         $breadcrumb = (object) [
             'title' => 'Detail Barang',
             'list'  => ['Home', 'Barang', 'Detail']
         ];
 
         // Konfigurasi judul halaman
         $page = (object) [
             'title' => 'Detail barang'
         ];
 
         // Menentukan menu yang sedang aktif
         $activeMenu = 'barang';
 
         // Mengembalikan tampilan dengan data yang sudah dikonfigurasi
         return view('barang.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'barang' => $barang, 'activeMenu' => $activeMenu]);
     }

     public function show_ajax(string $id) {
        $barang = BarangModel::find($id);

        return view('barang.show_ajax', ['barang' => $barang]);
    }
 
     // Menampilkan halaman form edit barang
     public function edit(string $id)
     {
         // Ambil data barang berdasarkan ID
         $barang = BarangModel::findOrFail($id);
 
         // Ambil semua data kategori untuk ditampilkan di form
         $kategori = KategoriModel::all();
 
         // Konfigurasi breadcrumb untuk navigasi
         $breadcrumb = (object) [
             'title' => 'Edit Barang',
             'list'  => ['Home', 'Barang', 'Edit']
         ];
 
         // Konfigurasi judul halaman
         $page = (object) [
             'title' => 'Edit barang'
         ];
 
         // Menentukan menu yang sedang aktif
         $activeMenu = 'barang';
 
         // Mengembalikan tampilan dengan data yang sudah dikonfigurasi
         return view('barang.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'barang' => $barang, 'kategori' => $kategori, 'activeMenu' => $activeMenu]);
     }

     public function edit_ajax(string $id) {
        $barang = BarangModel::find($id);
        $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get();

        return view('barang.edit_ajax', ['barang' => $barang, 'kategori' => $kategori]);
    }
 
     // Menyimpan perubahan data barang
     public function update(Request $request, string $id)
     {
         $request->validate([
             'kode_barang' => 'required|string|min:3|unique:m_barang,kode_barang,' . $id . ',barang_id',
             'nama_barang' => 'required|string|max:100',
             'harga_beli' => 'required|numeric',
             'harga_jual' => 'required|numeric',
             'kategori_id' => 'required|integer'
         ]);
 
         $barang = BarangModel::findOrFail($id);
 
         $barang->update([
             'kode_barang' => $request->kode_barang,
             'nama_barang' => $request->nama_barang,
             'harga_beli' => $request->harga_beli,
             'harga_jual' => $request->harga_jual,
             'kategori_id' => $request->kategori_id
         ]);
 
         return redirect('/barang')->with('success', 'Data barang berhasil diubah');
     }

     public function update_ajax(Request $request, $id) {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kode_barang' => 'required|string|max:6',
                'nama_barang' => 'required|string|max:100',
                'harga_beli' => 'required|integer',
                'harga_jual' => 'required|integer|gte:harga_beli',
                'kategori_id' => 'required|exists:m_kategori,kategori_id'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $check = BarangModel::find($id);
            if ($check) {
                $check->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }
 
     // Menghapus data barang
     public function destroy(string $id)
     {
         // Mengecek apakah data barang dengan ID yang dimaksud ada atau tidak
         $check = BarangModel::find($id);
         if (!$check) {
             return redirect('/barang')->with('error', 'Data barang tidak ditemukan');
         }
 
         try {
             // Menghapus data barang berdasarkan ID
             BarangModel::destroy($id);
 
             return redirect('/barang')->with('success', 'Data barang berhasil dihapus');
         } catch (\Illuminate\Database\QueryException $e) {
             // Jika terjadi error ketika menghapus data,
             // redirect kembali ke halaman dengan pesan error
             return redirect('/barang')->with('error', 'Data barang gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
         }
     }

     public function confirm_ajax(string $id) {
        $barang = BarangModel::find($id);

        return view('barang.confirm_ajax', ['barang' => $barang]);
    }

    public function delete_ajax(Request $request, $id)
    {
    if ($request->ajax() || $request->wantsJson()) {
        $barang = BarangModel::find($id);
        if (!$barang) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        try {
            $barang->delete();
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil dihapus'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Data gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini'
            ]);
        }
    }
    return redirect('/');
    }

    public function import()
    {
        return view('barang.import');
    }

    public function import_ajax(Request $request)
     {
         if ($request->ajax() || $request->wantsJson()) {
             $rules = [
                 // validasi file harus xls atau xlsx, max 1MB
                 'file_barang' => ['required', 'mimes:xlsx', 'max:1024']
             ];
             $validator = Validator::make($request->all(), $rules);
             if ($validator->fails()) {
                 return response()->json([
                     'status' => false,
                     'message' => 'Validasi Gagal',
                     'msgField' => $validator->errors()
                 ]);
             }
             $file = $request->file('file_barang'); // ambil file dari request
             $reader = IOFactory::createReader('Xlsx'); // load reader file excel
             $reader->setReadDataOnly(true); // hanya membaca data
             $spreadsheet = $reader->load($file->getRealPath()); // load file excel
             $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif

             $data = $sheet->toArray(null, false, true, true); // ambil data excel

             $insert = [];
             if (count($data) > 1) { // jika data lebih dari 1 baris
                 foreach ($data as $baris => $value) {
                     if ($baris > 1) { // baris ke 1 adalah header, maka lewati
                         $insert[] = [
                            'kategori_id' => $value['A'],
                            'barang_kode' => $value['B'],
                            'barang_nama' => $value['C'],
                            'harga_beli'  => $value['D'],
                            'harga_jual'  => $value['E'],
                            'created_at'  => now(),
                         ];
                     }
                 }
                 if (count($insert) > 0) {
                     // insert data ke database, jika data sudah ada, maka diabaikan
                     BarangModel::insertOrIgnore($insert);
                 }
                 return response()->json([
                     'status' => true,
                     'message' => 'Data berhasil diimport'
                 ]);
             } else {
                 return response()->json([
                     'status' => false,
                     'message' => 'Tidak ada data yang diimport'
                 ]);
             }
         }
         return redirect('/');
     }
}
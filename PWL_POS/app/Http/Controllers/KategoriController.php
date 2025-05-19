<?php

namespace App\Http\Controllers;

use App\Models\KategoriModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;

class KategoriController extends Controller
{
    // Menampilkan halaman awal kategori
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

    // Mengambil data kategori dari database dalam format JSON untuk DataTables
public function list(Request $request)
{
    $kategori = KategoriModel::select(
        'kategori_id',
        'kategori_kode',
        'kategori_nama'
    );

    $kategori_id = $request->input('kategori_id');
    if (!empty($kategori_id)) {
        $kategori->where('kategori_id', $kategori_id);
    }

    return DataTables::of($kategori)
        ->addIndexColumn() // Menambahkan kolom index / nomor urut
        ->addColumn('aksi', function ($kategori) { // Menambahkan kolom aksi
            $btn = '<button onclick="modalAction(\'' . url('/kategori/' . $kategori->kategori_id ) . '\')" class="btn btn-info btn-sm">Detail</button> ';
            $btn .= '<button onclick="modalAction(\'' . url('/kategori/' . $kategori->kategori_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
            $btn .= '<button onclick="modalAction(\''.url('/kategori/' . $kategori->kategori_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
            return $btn;
        })
        ->rawColumns(['aksi']) // Memberitahu bahwa kolom aksi berisi HTML
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

    // Menghapus data kategori
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

    // Menampilkan form tambah kategori
    public function create_ajax() {
        return view('kategori.create_ajax');
    }
    
    // Menyimpan data kategori baru via AJAX
    public function store_ajax(Request $request) {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_kode' => 'required|string|min:2|max:10|unique:m_kategori,kategori_kode',
                'kategori_nama' => 'required|string|min:3|max:100|unique:m_kategori,kategori_nama',
            ];
    
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Validasi Gagal',
                    'msgField'=> $validator->errors(),
                ]);
            }
    
            KategoriModel::create($request->all());
            return response()->json([
                'status'  => true,
                'message' => 'Kategori berhasil ditambahkan'
            ]);
        }
    
        return redirect('/');
    }
    
    // Menampilkan form edit kategori
    public function edit_ajax(string $id) {
        $kategori = KategoriModel::findOrFail($id);
    
        return view('kategori.edit_ajax', ['kategori' => $kategori]);
    }
    
    // Proses update kategori via AJAX
    public function update_ajax(Request $request, string $id) {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_kode' => 'required|string|min:2|max:10|unique:m_kategori,kategori_kode,' . $id . ',kategori_id',
                'kategori_nama' => 'required|string|min:3|max:100'
            ];
    
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }
    
            $kategori = KategoriModel::find($id);
            if ($kategori) {
                $kategori->update($request->all());
    
                return response()->json([
                    'status' => true,
                    'message' => 'Data kategori berhasil diperbarui'
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
    
    // Menampilkan konfirmasi hapus kategori
    public function confirm_ajax(string $id) {
        $kategori = KategoriModel::find($id);
    
        return view('kategori.confirm_ajax', ['kategori' => $kategori]);
    }
    
    // Hapus data kategori via AJAX
    public function delete_ajax(Request $request, string $id) {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                // Cari kategori
                $kategori = KategoriModel::find($id);
                
                if (!$kategori) {
                    return response()->json([
                        'status'  => false,
                        'message' => 'Data tidak ditemukan'
                    ]);
                }
                
                // Hapus kategori
                $kategori->delete();
                
                return response()->json([
                    'status'  => true,
                    'message' => 'Data kategori berhasil dihapus'
                ]);
            } catch (\Exception $e) {
                // Tangkap semua exception
                return response()->json([
                    'status'  => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500); // Kembalikan status 500 untuk error server
            }
        }
        
        // Jika bukan ajax request, redirect
        return redirect('/');
    }

    public function import() 
     { 
         return view('kategori.import');  // Ganti tampilan dengan tampilan kategori
     }
 
     public function import_ajax(Request $request)
     {
         if ($request->ajax() || $request->wantsJson()) {
             $rules = [
                 'file_kategori' => ['required', 'mimes:xlsx', 'max:1024']  // Ganti 'file_level' menjadi 'file_kategori'
             ];
 
             $validator = Validator::make($request->all(), $rules);
             if ($validator->fails()) {
                 return response()->json([
                     'status' => false,
                     'message' => 'Validasi Gagal',
                     'msgField' => $validator->errors()
                 ]);
             }
 
             $file = $request->file('file_kategori');  // Ganti 'file_level' menjadi 'file_kategori'
 
             $reader = IOFactory::createReader('Xlsx');
             $reader->setReadDataOnly(true);
             $spreadsheet = $reader->load($file->getRealPath());
             $sheet = $spreadsheet->getActiveSheet();
             $data = $sheet->toArray(null, false, true, true);
 
             $insert = [];
             if (count($data) > 1) {
                 foreach ($data as $row => $value) {
                     if ($row > 1) {
                         // Pastikan kolom yang diimpor sesuai dengan kolom yang ada pada tabel m_kategori
                         $insert[] = [
                             'kategori_kode' => $value['A'],  // Pastikan kolom 'A' berisi kode kategori
                             'kategori_nama' => $value['B'],  // Pastikan kolom 'B' berisi nama kategori
                             'created_at' => now(),
                             'updated_at' => now()  // Menambahkan updated_at jika diperlukan
                         ];
                     }
                 }
 
                 if (count($insert) > 0) {
                     KategoriModel::insertOrIgnore($insert);  // Menggunakan insertOrIgnore agar data duplikat tidak ditambahkan
                 }
 
                 return response()->json([
                     'status' => true,
                     'message' => 'Data Kategori berhasil diimport'
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

    public function export_excel(){
        // Ambil data kategori
        $kategoris = KategoriModel::select('kategori_id', 'kategori_kode', 'kategori_nama', 'created_at')
            ->orderBy('kategori_id')
            ->get();

        // Load library Spreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header kolom
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Kategori');
        $sheet->setCellValue('C1', 'Nama Kategori');
        $sheet->setCellValue('D1', 'Tanggal Dibuat');

        $sheet->getStyle('A1:E1')->getFont()->setBold(true);
        $no = 1;
        $baris = 2;

        foreach ($kategoris as $kategori) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $kategori->kategori_kode);
            $sheet->setCellValue('C' . $baris, $kategori->kategori_nama);
            $sheet->setCellValue('D' . $baris, $kategori->created_at);
            $baris++;
            $no++;
        }

        // Auto size kolom
        foreach(range('A', 'D') as $columnID){
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Nama sheet
        $sheet->setTitle('Data Kategori');

        // Export ke browser
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Kategori ' . date('Y-m-d H-i-s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');
        header('Expires: 0');
        header('Pragma: public');

        $writer->save('php://output');
        exit;
    }
    public function export_pdf(){
        $kategori = KategoriModel::select('kategori_id', 'kategori_kode', 'kategori_nama', 'created_at')
            ->orderBy('kategori_id')
            ->get();

        //use Barryvdh\DomPDF\Facade\Pdf;
        $pdf = Pdf::loadView('kategori.export_pdf', ['kategori' => $kategori]);
        $pdf->setPaper('a4','potrait'); //set ukuran kertas dan orientasi
        $pdf->setOption("isRemoteEnabled", true); //set true jika ada gambar dari url
        $pdf->render();

        return $pdf->stream('Data Kategori '.date('Y-m-d H:i:s').'.pdf');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;

class LevelController extends Controller
{
    // Menampilkan halaman awal level
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

    //mengambil data dari json
    public function list(Request $request)
    {
        $levels = LevelModel::select(
            'level_id',
            'level_kode',
            'level_nama'
        );

        $level_id = $request->input('level_id');
        if (!empty($level_id)) {
            $levels->where('level_id', $level_id);
        }
    
        return DataTables::of($levels)
            ->addIndexColumn() // Menambahkan kolom index / nomor urut
            ->addColumn('aksi', function ($level) { // Menambahkan kolom aksi
                $btn = '<button onclick="modalAction(\'' . url('/level/' . $level->level_id) . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/level/' . $level->level_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/level/' . $level->level_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // Memberitahu bahwa kolom aksi berisi HTML
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

public function create_ajax() {
    $levels = LevelModel::select('level_id', 'level_nama')->get();
    return view('level.create_ajax')->with('levels', $levels);
}

// Menyimpan data level baru
    public function store_ajax(Request $request) {
        if($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_nama' => 'required|string|min:3|max:50|unique:m_level,level_nama',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Validasi Gagal',
                    'msgField'=> $validator->errors(),
                ]);
            }

            LevelModel::create($request->all());
            return response()->json([
                'status'  => true,
                'message' => 'Level Pengguna berhasil ditambahkan'
            ]);
        }

        return redirect('/');
    }

    // Menampilkan form edit level
    public function edit_ajax(string $id) {
        $level = LevelModel::find($id);

        return view('level.edit_ajax', ['level' => $level]);
    }

    // Proses update level via AJAX
    public function update_ajax(Request $request, string $id) {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_kode' => 'required|string|min:2|max:10|unique:m_level,level_kode,' . $id . ',level_id',
                'level_nama' => 'required|string|min:3|max:50'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $level = LevelModel::find($id);
            if ($level) {
                $level->update($request->all());

                return response()->json([
                    'status' => true,
                    'message' => 'Data level berhasil diperbarui'
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

    // Menampilkan konfirmasi hapus
    public function confirm_ajax(string $id) {
        $level = LevelModel::find($id);

        return view('level.confirm_ajax', ['level' => $level]);
    }

    // Hapus data level via AJAX
    public function delete_ajax(Request $request, string $id) {
        if ($request->ajax() || $request->wantsJson()) {
            $level = LevelModel::find($id);
            if ($level) {
                $level->delete();
                return response()->json([
                    'status'  => true,
                    'message' => 'Data level berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }
        public function import()
    {
        return view('level.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_level' => ['required', 'mimes:xlsx', 'max:1024']
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_level');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);

            $insert = [];
            if (count($data) > 1) {
                foreach ($data as $baris => $value) {
                    if ($baris > 1) {
                        $insert[] = [
                            'level_kode' => $value['A'],
                            'level_nama' => $value['B'],
                            'created_at' => now(),
                        ];
                    }
                }

                if (count($insert) > 0) {
                    LevelModel::insertOrIgnore($insert);
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Data level berhasil diimport'
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
        // Ambil data level yang akan diexport
        $level = LevelModel::select('level_id', 'level_kode', 'level_nama', 'created_at')
            ->orderBy('level_id')
            ->get();

        // Load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); // Ambil sheet yang aktif

        // Set header kolom
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Level');
        $sheet->setCellValue('C1', 'Nama Level');
        $sheet->setCellValue('D1', 'Tanggal Dibuat');

        $sheet->getStyle('A1:D1')->getFont()->setBold(true); // Bold header
        $no = 1; // Nomor data dimulai dari 1
        $baris = 2; // Baris data dimulai dari baris ke 2

        // Loop untuk menulis data level
        foreach ($level as $key => $value) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->level_kode);
            $sheet->setCellValue('C' . $baris, $value->level_nama);
            $sheet->setCellValue('D' . $baris, $value->created_at); // Tanggal dibuat
            $baris++;
            $no++;
        }

        // Set auto size untuk kolom A sampai D
        foreach(range('A', 'D') as $columnID){
            $sheet->getColumnDimension($columnID)->setAutoSize(true); // Set auto size untuk kolom
        }

        // Set title sheet
        $sheet->setTitle('Data Level');

        // Create writer dan tentukan format file
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Level ' . date('Y-m-d H:i:s') . '.xlsx';

        // Set header untuk download file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $writer->save('php://output');
        exit;
    }
    public function export_pdf(){
        $level = LevelModel::select('level_id', 'level_kode', 'level_nama', 'created_at')
        ->orderBy('level_id')
        ->get();

        //use Barryvdh\DomPDF\Facade\Pdf;
        $pdf = Pdf::loadView('level.export_pdf', ['level' => $level]);
        $pdf->setPaper('a4','potrait'); //set ukuran kertas dan orientasi
        $pdf->setOption("isRemoteEnabled", true); //set true jika ada gambar dari url
        $pdf->render();

        return $pdf->stream('Data Level '.date('Y-m-d H:i:s').'.pdf');
    }
}
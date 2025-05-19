<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use App\Models\UserModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Dotenv\Util\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index (){
        $breadcrumb = (object) [
            'title' => 'Daftar User',
            'list'  => ['Home', 'User']
        ];
        
        $page = (object) [
            'title' => 'Daftar user yang terdaftar dalam sistem'
        ];

        $activeMenu = 'user'; // set menu yang sedang aktif

        $level = LevelModel::all(); // ambil data level untuk filter level

        return view('user.index', [
            'breadcrumb' => $breadcrumb, 
            'page' => $page, 
            'level' => $level, 
            'activeMenu' => $activeMenu]);

    }

    // Ambil data user dalam bentuk json untuk datatables
        public function list(Request $request)
    {
    $users = UserModel::select('user_id', 'username', 'nama', 'level_id')->with('level');
    
    // Filter data user berdasarkan level_id
        if ($request->level_id){
        $users->where('level_id',$request->level_id);
        }
        return DataTables::of($users)
        ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom:DT_RowIndex)
        ->addColumn('aksi', function ($user) { // menambahkan kolom aksi
        $btn = '<button onclick="modalAction(\''.url('/user/' . $user->user_id).'\')" class="btn btn-info btn-sm">Detail</button> ';
        
        $btn .= '<button onclick="modalAction(\''.url('/user/' . $user->user_id .
        '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
        
        $btn .= '<button onclick="modalAction(\''.url('/user/' . $user->user_id .
        '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
        
        return $btn;
    })
        ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
        ->make(true);
    }

    public function create(){

        $breadcrumb = (object) [
            'title' => 'Tambah User',
            'list'  => ['Home', 'User', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah user Baru'
        ];

        $level = LevelModel::all();
        $activeMenu = 'user'; // set menu yang sedang aktif

        return view('user.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }

   // Menyimpan data user baru
    public function store(Request $request)
    {
        $request->validate([
            // username harus diisi, berupa string, minimal 3 karakter, dan bernilai unik di tabel m_user kolom username
            'username' => 'required|string|min:3|unique:m_user,username',
            'nama' => 'required|string|max:100', // nama harus diisi, berupa string, dan maksimal 100 karakter
            'password' => 'required|min:5',      // password harus diisi dan minimal 5 karakter
            'level_id' => 'required|integer'     // level_id harus diisi dan berupa angka
        ]);

        UserModel::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => bcrypt($request->password), // password dienkripsi sebelum disimpan
            'level_id' => $request->level_id
        ]);

        return redirect('/user')->with('success', 'Data user berhasil disimpan');
    }
    public function show (string $id) {
        $user = UserModel::with('level')->find($id);

        $breadcrumb =(object) [
            'title' => 'Detail User',
            'list'  => ['Home', 'User', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail user'
        ];

        $activeMenu = 'user';

        return view('user.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'activeMenu' => $activeMenu]);
    }

    //menampilkan halamanan form edit user
    public function edit (string $id) {

        $user = UserModel::find($id);
        $level = LevelModel::all();

        $breadcrumb = (object) [
            'title' => 'Edit User',
            'list'  => ['Home', 'User', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit User'
        ];

        $activeMenu = 'user';

        return view('user.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'level' => $level, 'activeMenu' => $activeMenu]);
    }

    public function update (Request $request, string $id){
        $request->validate([
            'username'=> 'required|string|min:3|unique:m_user,username,'.$id.',user_id',
            'nama'=> 'required|string|max:100',
            'password'=> 'nullable|min:5',
            'level_id'=> 'required|integer'
        ]);

        UserModel::find($id)->update([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => $request->password? bcrypt($request->password) : UserModel::find($id)->password,
            'level_id' => $request->level_id
        ]);

        return redirect('/user')->with('success', 'Data user berhasil diubah');
    }

    public function destroy (string $id) {
        
        $check = UserModel::find($id);
        if(!$check){
            return redirect('/user')->with('error', 'Data User tidak ditemukan');
        }

        try{
            UserModel::destroy($id);

            return redirect('/user')->with('succes','Data user berhasil dihapus');
        }catch(\Illuminate\Database\QueryException $e){
            return redirect('/user')->with('error', 'Data user gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }

    }

    public function create_ajax() {
        $level = LevelModel::select('level_id', 'level_nama')->get();
        return view('user.create_ajax') ->with('level', $level);
    }

    public function store_ajax(Request $request) {
        // cek apakah request berupa ajax
        if($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_id' => 'required|integer',
                'username' => 'required|string|min:3|unique:m_user,username',
                'nama'     => 'required|string|max:100',
                'password' => 'required|min:6'
            ];
        
            $validator = Validator::make($request->all(), $rules);
        
            if($validator->fails()){
                return response()->json([
                    'status'  => false, // response status, false: error/gagal, true: berhasil
                    'message' => 'Validasi Gagal',
                    'msgField'=> $validator->errors(), // pesan error validasi
                ]);
            }
        
            UserModel::create($request->all());
            return response()->json([
                'status'  => true,
                'message' => 'Data user berhasil disimpan'
            ]);
        }
        redirect('/');
        }

        //menampilkan halaman form edit user ajax
        public function edit_ajax(string $id) {
            $user = UserModel::find($id);
            $level = LevelModel::select('level_id', 'level_nama')->get();

            return view('user.edit_ajax', ['user' => $user, 'level'=> $level]);
        }

        public function update_ajax(Request $request, $id){
            // cek apakah request dari ajax
            if ($request->ajax() || $request->wantsJson()) {
            $rules = [
            'level_id' => 'required|integer',
            'username' => 'required|max:20|unique:m_user,username,'.$id.',user_id',
            'nama' => 'required|max:100',
            'password' => 'nullable|min:6|max:20'
            ];
            // use Illuminate\Support\Facades\Validator;
        $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
            return response()->json([
            'status' => false, // respon json, true: berhasil, false: gagal
            'message' => 'Validasi gagal.',
            'msgField' => $validator->errors() // menunjukkan field mana yang error
            ]);
            }
            $check = UserModel::find($id);
            if ($check) {
            if(!$request->filled('password') ){ // jika password tidak diisi, maka hapus dari request
            $request->request->remove('password');
            }
            $check->update($request->all());
            return response()->json([
            'status' => true,
            'message' => 'Data berhasil diupdate'
            ]);
            } else{
            return response()->json([
            'status' => false,
            'message' => 'Data tidak ditemukan'
            ]);
            }
            }
            return redirect('/');
        }
        public function confirm_ajax(string $id){
            $user = UserModel::find($id);

            return view('user.confirm_ajax', ['user' => $user]);
        }

        public function delete_ajax(Request $request, $id)
    {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $user = UserModel::find($id);
            if ($user) {
                $user->delete();
                return response()->json([
                    'status'  => true,
                    'message' => 'Data berhasil dihapus'
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

        public function import(){
            return view('user.import'); 
    }
        public function import_ajax(Request $request){
            if ($request->ajax() || $request->wantsJson()) {
                $rules = [
                    'file_user' => ['required', 'mimes:xlsx', 'max:1024']
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validasi gagal. Pastikan file Excel benar.',
                        'msgField' => $validator->errors()
                    ]);
                }
                    $file = $request->file('file_user');
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
                                    'level_id' => $value['A'],
                                    'username' => $value['B'],
                                    'nama'     => $value['C'],
                                    'password' => Hash::make($value['D']),
                                    'created_at' => now(),
                                ];
                            }
                        }

                        if (count($insert) > 0) {
                            UserModel::insertOrIgnore($insert);
                        }

                        return response()->json([
                            'status' => true,
                            'message' => 'Data user berhasil diimpor.'
                        ]);
                    } else {
                        return response()->json([
                            'status' => false,
                            'message' => 'Tidak ada data yang dapat diimpor.'
                        ]);
                    }

            return redirect('/');
        }
    }

    public function export_excel(){
        // Ambil data user yang akan diexport
        $users = UserModel::select('user_id', 'level_id', 'username', 'nama', 'password', 'created_at', 'updated_at')
            ->orderBy('user_id')
            ->with('level') // Menambahkan relasi dengan level
            ->get();

        // Load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); // Ambil sheet yang aktif

        // Set header kolom
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Level');
        $sheet->setCellValue('C1', 'Nama Level');
        $sheet->setCellValue('D1', 'Username');
        $sheet->setCellValue('E1', 'Nama');
        $sheet->setCellValue('F1', 'Password');
        $sheet->setCellValue('G1', 'Tanggal Dibuat');
        $sheet->setCellValue('H1', 'Tanggal Update');

        $sheet->getStyle('A1:H1')->getFont()->setBold(true); // Bold header
        $no = 1; // Nomor data dimulai dari 1
        $baris = 2; // Baris data dimulai dari baris ke 2

        // Loop untuk menulis data user
        foreach ($users as $key => $value) {
            $sheet->setCellValue('A' . $baris, $no);
            // Menambahkan Level Kode dan Nama Level
            $sheet->setCellValue('B' . $baris, $value->level_id);
            $sheet->setCellValue('C' . $baris, $value->level ? $value->level->level_nama : 'No Level');
            $sheet->setCellValue('D' . $baris, $value->username);
            $sheet->setCellValue('E' . $baris, $value->nama);
            $sheet->setCellValue('F' . $baris, $value->password);
            $sheet->setCellValue('G' . $baris, $value->created_at);
            $sheet->setCellValue('H' . $baris, $value->upadte_at);
            
            $baris++;
            $no++;
        }

        // Set auto size untuk kolom A sampai H
        foreach(range('A', 'H') as $columnID){
            $sheet->getColumnDimension($columnID)->setAutoSize(true); // Set auto size untuk kolom
        }

        // Set title sheet
        $sheet->setTitle('Data User');

        // Create writer dan tentukan format file
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data User ' . date('Y-m-d H:i:s') . '.xlsx';

        // Set header untuk download file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        // Simpan file ke output
        $writer->save('php://output');
        exit;
    }

    public function export_pdf(){
        $user= UserModel::select('user_id', 'level_id', 'username', 'nama', 'password', 'created_at')
        ->orderBy('user_id')
        ->orderBy('level_id')
        ->with('level')
        ->get();

        //use Barryvdh\DomPDF\Facade\Pdf;
        $pdf = Pdf::loadView('user.export_pdf', ['user' => $user]);
        $pdf->setPaper('a4','landscape'); //set ukuran kertas dan orientasi
        $pdf->setOption("isRemoteEnabled", true); //set true jika ada gambar dari url
        $pdf->render();

        return $pdf->stream('Data User '.date('Y-m-d H:i:s').'.pdf');
    }
}
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BarangModel;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    public function index()
    {
        return response()->json(BarangModel::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:m_kategori,kategori_id',
            'barang_kode' => 'required|string|unique:m_barang,barang_kode',
            'barang_nama' => 'required|string|max:100',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $image = $request->file('image');
        $imageName = $image->hashName();
        $image->storeAs('public/barang', $imageName);

        $barang = BarangModel::create([
            'kategori_id' => $request->kategori_id,
            'barang_kode' => $request->barang_kode,
            'barang_nama' => $request->barang_nama,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'image' => $imageName,
        ]);

        return response()->json($barang, 201);
    }

    public function show($id)
    {
        $barang = BarangModel::find($id);

        if (!$barang) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json($barang);
    }

    public function update(Request $request, $id)
    {
        $barang = BarangModel::find($id);

        if (!$barang) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $request->validate([
            'kategori_id' => 'sometimes|exists:m_kategori,kategori_id',
            'barang_kode' => 'sometimes|string|unique:m_barang,barang_kode,' . $id . ',barang_id',
            'barang_nama' => 'sometimes|string|max:100',
            'harga_beli' => 'sometimes|numeric',
            'harga_jual' => 'sometimes|numeric',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($barang->image) {
                Storage::delete('public/barang/' . $barang->image);
            }

            $image = $request->file('image');
            $imageName = $image->hashName();
            $image->storeAs('public/barang', $imageName);
            $barang->image = $imageName;
        }

        $barang->update($request->except('image'));
        $barang->save();

        return response()->json($barang);
    }

    public function destroy($id)
    {
        $barang = BarangModel::find($id);

        if (!$barang) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        if ($barang->image) {
            Storage::delete('public/barang/' . $barang->image);
        }

        $barang->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data Terhapus',
        ]);
    }
}
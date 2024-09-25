<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Barang;
use App\Models\Mutasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    public function index()
    {
        $data = Barang::all();

        return response()->json(['success' => true, 'data' => $data], 200);
    }

    public function show(int $id)
    {
        $data = Barang::find($id);

        if ($data) {
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    public function create(Request $request)
    {
        $messages = [
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'kategori.required' => 'Kategori wajib diisi.',
            'lokasi.required' => 'Lokasi wajib diisi.'

        ];

        $validator = Validator::make($request->all(), [
            'nama_barang' => 'required',
            'kategori' => 'required',
            'lokasi' => 'required',
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()],  422);
        }

        $lastRecord = Barang::orderBy('id', 'desc')->first();
        $lastId = $lastRecord ? $lastRecord->id : 0;
        $newId = $lastId + 1;
        $newCode = 'B' . Carbon::now()->format('ymd') . str_pad($newId, 3, '0', STR_PAD_LEFT);

        try {
            DB::connection('mysql')->beginTransaction();

            $eloquent = new Barang;
            $eloquent->nama_barang = $request->nama_barang;
            $eloquent->kode = $newCode;
            $eloquent->kategori = $request->kategori;
            $eloquent->lokasi = $request->lokasi;
            $eloquent->save();

            DB::connection('mysql')->commit();

            $data = Barang::find($eloquent->id);

            return response()->json(['success' => true, 'message' => 'Berhasil menambah data barang', 'data' => $data]);
        } catch (\Exception $e) {
            DB::connection('mysql')->rollback();
            return $this->error_handler($e);
        }
    }

    public function update(Request $request, int $id)
    {
        $messages = [
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'kategori.required' => 'Kategori wajib diisi.',
            'lokasi.required' => 'Lokasi wajib diisi.'

        ];

        $validator = Validator::make($request->all(), [
            'nama_barang' => 'required',
            'kategori' => 'required',
            'lokasi' => 'required',
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()],  422);
        }

        try {
            DB::connection('mysql')->beginTransaction();

            $eloquent = Barang::find($id);
            $eloquent->nama_barang = $request->nama_barang;
            $eloquent->kategori = $request->kategori;
            $eloquent->lokasi = $request->lokasi;
            $eloquent->save();

            DB::connection('mysql')->commit();

            return response()->json(['success' => true, 'message' => 'Berhasil mengubah data barang', 'data' => $eloquent]);
        } catch (\Exception $e) {
            DB::connection('mysql')->rollback();
            return $this->error_handler($e);
        }
    }

    public function delete(int $id)
    {
        try {
            DB::connection('mysql')->beginTransaction();

            $data = Barang::find($id);

            Mutasi::where('barang_id', $id)->delete();

            $data->delete();

            DB::connection('mysql')->commit();
            return response()->json([
                'success' => true,
                'message' => 'Data barang berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            DB::connection('mysql')->rollback();
            return $this->error_handler($e);
        }
    }

    public function showMutasi($id)
    {
        $data = Barang::where('id', $id)->with('mutasi')->get();

        if ($data) {
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }
}

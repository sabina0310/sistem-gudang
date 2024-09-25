<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Mutasi;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MutasiController extends Controller
{
    public function index()
    {
        $data = Mutasi::all();

        return response()->json(['success' => true, 'data' => $data], 200);
    }

    public function show(int $id)
    {
        $data = Mutasi::where('id', $id)->with('barang')->with('createdBy')->first();

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
            'barang_id.required' => 'Id barang wajib diisi.',
            'tanggal.required' => 'Tanggal wajib diisi.',
            'jenis_mutasi.required' => 'Jenis mutasi wajib diisi.',
            'jumlah.required' => 'Jumlah wajib diisi.'
        ];

        $validator = Validator::make($request->all(), [
            'barang_id' => 'required',
            'tanggal' => 'required',
            'jenis_mutasi' => 'required',
            'jumlah' => 'required',
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()],  422);
        }

        try {
            DB::connection('mysql')->beginTransaction();

            $barang = Barang::find($request->barang_id);

            if ($request->jenis_mutasi == 'keluar' && $barang->jumlah_stok < $request->jumlah) {
                return response()->json(['success' => false, 'message' => 'Jumlah stok barang kurang']);
            }

            $dateFormat = Carbon::createFromFormat('d-m-Y', $request->tanggal)->format('Y-m-d');
            $eloquent = new Mutasi;
            $eloquent->barang_id = $barang->id;
            $eloquent->tanggal = $dateFormat;
            $eloquent->jenis_mutasi = $request->jenis_mutasi;
            $eloquent->jumlah = $request->jumlah;
            $eloquent->created_by = Auth::id();
            $eloquent->save();

            if ($eloquent->jenis_mutasi == 'masuk') {
                $barang->jumlah_stok += $eloquent->jumlah;
                $barang->save();
            } else {
                $barang->jumlah_stok -= $eloquent->jumlah;
                $barang->save();
            }

            DB::connection('mysql')->commit();

            $data = Mutasi::find($eloquent->id);

            return response()->json(['success' => true, 'message' => 'Berhasil menambah data mutasi', 'data' => $data]);
        } catch (\Exception $e) {
            DB::connection('mysql')->rollback();
            return $this->error_handler($e);
        }
    }

    public function update(Request $request, int $id)
    {
        $messages = [
            'barang_id.required' => 'Id barang wajib diisi.',
            'tanggal.required' => 'Tanggal wajib diisi.',
            'jenis_mutasi.required' => 'Jenis mutasi wajib diisi.',
            'jumlah.required' => 'Jumlah wajib diisi.'
        ];

        $validator = Validator::make($request->all(), [
            'barang_id' => 'required',
            'tanggal' => 'required',
            'jenis_mutasi' => 'required',
            'jumlah' => 'required',
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()],  422);
        }

        try {
            DB::connection('mysql')->beginTransaction();

            $barang = Barang::find($request->barang_id);

            $dateFormat = Carbon::createFromFormat('d-m-Y', $request->tanggal)->format('Y-m-d');

            $eloquent = Mutasi::find($id);
            $eloquent->tanggal = $dateFormat;

            if ($eloquent->jenis_mutasi != $request->jenis_mutasi) {
                if ($request->jenis_mutasi == 'keluar') {
                    $barang->jumlah_stok -= $eloquent->jumlah;

                    if ($barang->jumlah_stok < $request->jumlah) {
                        return response()->json(['success' => false, 'message' => 'Jumlah stok barang kurang']);
                    }
                    $barang->jumlah_stok -= $request->jumlah;
                } else {
                    $barang->jumlah_stok += $eloquent->jumlah;
                    $barang->jumlah_stok += $request->jumlah;
                }
            } else {
                if ($request->jenis_mutasi == 'masuk') {
                    $selisihStok = $request()->jumlah - $eloquent->jumlah_stok;

                    $barang->jumlah_stok += $selisihStok;
                    $barang->save();
                } else {
                    $selisihStok = $request()->jumlah - $eloquent->jumlah_stok;

                    if ($barang->jumlah_stok < $selisihStok) {
                        return response()->json(['success' => false, 'message' => 'Jumlah stok barang kurang']);
                    }

                    $barang->jumlah_stok -= $selisihStok;
                }
            }

            $barang->save();
            $eloquent->jenis_mutasi = $request->jenis_mutasi;
            $eloquent->jumlah = $request->jumlah;
            $eloquent->save();


            DB::connection('mysql')->commit();

            return response()->json(['success' => true, 'message' => 'Berhasil mengubah data mutasi', 'data' => $eloquent]);
        } catch (\Exception $e) {
            DB::connection('mysql')->rollback();
            return $this->error_handler($e);
        }
    }

    public function delete(int $id)
    {
        try {
            $data = Mutasi::find($id);
            $barang = Barang::find($data->barang_id);
            if ($data->jenis_mutasi == 'masuk') {
                $barang->jumlah_stok -= $data->jumlah;
            } else {
                $barang->jumlah_stok += $data->jumlah;
            }

            $barang->save();
            $data->delete();

            DB::connection('mysql')->commit();

            return response()->json(['success' => true, 'message' => 'Berhasil menghapus data mutasi']);
        } catch (\Exception $e) {
            DB::connection('mysql')->rollback();
            return $this->error_handler($e);
        }
    }
}

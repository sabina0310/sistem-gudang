<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $data = User::all();

        return response()->json(['success' => true, 'data' => $data]);
    }

    public function show(int $id)
    {
        $data = User::find($id);

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
            'nama.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password wajib diisi.'
        ];

        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()],  422);
        }

        try {

            DB::connection('mysql')->beginTransaction();

            $eloquent = new User;
            $eloquent->nama = $request->nama;
            $eloquent->email = $request->email;
            $eloquent->password = bcrypt($request->password);
            $eloquent->save();

            DB::connection('mysql')->commit();

            return response()->json(['success' => true, 'message' => 'Berhasil menambah data user'], 200);
        } catch (\Exception $e) {
            DB::connection('mysql')->rollback();
            return $this->error_handler($e);
        }
    }

    public function edit(Request $request, int $id)
    {
        $messages = [
            'nama.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid'
        ];

        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'email' => 'required|email'
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()],  422);
        }

        try {

            DB::connection('mysql')->beginTransaction();

            $eloquent = User::find($id);
            $eloquent->nama = $request->nama;
            $eloquent->email = $request->email;
            if ($request->password != null) {
                $eloquent->password = bcrypt($request->password);
            }
            $eloquent->save();

            DB::connection('mysql')->commit();

            return response()->json(['success' => true, 'message' => 'Berhasil mengubah data user'], 200);
        } catch (\Exception $e) {
            DB::connection('mysql')->rollback();
            return $this->error_handler($e);
        }
    }

    public function delete(int $id)
    {
        try {
            DB::connection('mysql')->beginTransaction();

            $data = User::find($id);

            $data->delete();

            DB::connection('mysql')->commit();
            return response()->json([
                'success' => true,
                'message' => 'Data user berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            DB::connection('mysql')->rollback();
            return $this->error_handler($e);
        }
    }

    public function showMutasi($id)
    {
        $data = User::where('id', $id)->with('mutasi')->first();

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

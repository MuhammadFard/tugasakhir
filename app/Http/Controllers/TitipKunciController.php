<?php

namespace App\Http\Controllers;

use App\Models\TitipKunci;
use App\Models\RekapData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

class TitipKunciController extends Controller
{
    public function index()
    {
        $titipKuncis = TitipKunci::all();
        return Response::json($titipKuncis, 200);
    }

    public function create()
    {
        return view('titip-kunci.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'no_pol' => 'required|string|max:255',
            'nama_petugas' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $titipKunci = new TitipKunci();
            $titipKunci->tanggal_masuk = now();
            $titipKunci->no_pol = $request->no_pol;
            $titipKunci->nama_petugas = auth() -> user() -> username;
            $titipKunci->save();

            $rekapData = new RekapData();
            $rekapData->id_titipKunci = $titipKunci->id; // Perbaiki nama kolom
            $rekapData->save();

            DB::commit();

            return response()->json(['message' => 'Data berhasil disimpan'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function show(TitipKunci $titipKunci)
    {
        return Response::json($titipKunci, 200);
    }

    public function edit(TitipKunci $titipKunci)
    {
        return view('titip-kunci.edit', compact('titipKunci'));
    }

    public function update(Request $request, TitipKunci $titipKunci)
    {
        $validatedData = $request->validate([
            'no_pol' => 'required|string|max:255',
            'nama_petugas' => 'required|string|max:255',
        ]);

        $titipKunci->update($validatedData);

        return Response::json(['message' => 'Data berhasil diperbarui'], 200);
    }

    public function destroy(TitipKunci $titipKunci)
    {
        try {
            DB::beginTransaction();

            $titipKunci->rekapData()->delete();
            $titipKunci->delete();

            DB::commit();

            return response()->json(['message' => 'Data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Gagal menghapus data: ' . $e->getMessage()], 500);
        }
    }
}

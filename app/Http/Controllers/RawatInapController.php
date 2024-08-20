<?php

namespace App\Http\Controllers;

use App\Models\RawatInap;
use App\Models\RekapData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;

class RawatInapController extends Controller
{
    public function index()
    {
        try {
            $rawatInaps = RawatInap::all();
            return response()->json($rawatInaps, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal mengambil data: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_pol' => 'required|string|max:255',
            'tanggal_keluar' => 'nullable|date',
        ]);

        try {
            DB::beginTransaction();

            $rawatInap = new RawatInap();
            $rawatInap->tanggal_masuk = now();
            $rawatInap->tanggal_keluar = $request->input('tanggal_keluar', '9999-12-31 23:59:59');
            $rawatInap->no_pol = $request->input('no_pol');
            $rawatInap->nama_petugas = auth()->user()->username;
            $rawatInap->save();

            $rekapData = new RekapData();
            $rekapData->id_rawatInap = $rawatInap->id;
            $rekapData->save();

            DB::commit();

            return response()->json(['message' => 'Data berhasil disimpan'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function show(RawatInap $rawatInap)
    {
        return Response::json($rawatInap, 200);
    }

    public function update(Request $request, RawatInap $rawatInap)
    {
        $request->validate([
            'no_pol' => 'required|string|max:255',
            'tanggal_keluar' => 'nullable|date|after:today',
        ]);

        try {
            DB::beginTransaction();

            $rawatInap->tanggal_keluar = $request->input('tanggal_keluar', $rawatInap->tanggal_keluar);
            $rawatInap->no_pol = $request->input('no_pol', $rawatInap->no_pol);
            $rawatInap->save();

            DB::commit();

            return response()->json(['message' => 'Data rawat inap berhasil diperbarui'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Gagal memperbarui data: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(RawatInap $rawatInap)
    {
        try {
            DB::beginTransaction();

            $rawatInap->rekapData()->delete();
            $rawatInap->delete();

            DB::commit();

            return response()->json(['message' => 'Data rawat inap berhasil dihapus'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Gagal menghapus data: ' . $e->getMessage()], 500);
        }
    }
}

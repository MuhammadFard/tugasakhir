<?php

namespace App\Http\Controllers;

use App\Models\RekapData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class RekapDataController extends Controller
{
    public function index()
    {
        try {
            // Hanya ambil data rekap data tanpa relasi karena hanya untuk menampilkan
            $rekapData = RekapData::all();
            return Response::json($rekapData, 200);
        } catch (\Exception $e) {
            return Response::json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    // Hapus metode store karena kita hanya ingin melihat dan menghapus

    public function show(RekapData $rekapData)
    {
        try {
            
            return Response::json($rekapData->load(['rawatInap', 'titipKunci']), 200);
        } catch (\Exception $e) {
            return Response::json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
    
    public function destroy(RekapData $rekapData)
    {
        try {
            $rekapData->delete();
            return Response::json(['message' => 'Rekap data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return Response::json(['message' => 'Gagal menghapus rekap data: ' . $e->getMessage()], 500);
        }
    }
}

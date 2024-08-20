<?php

namespace App\Http\Controllers;

use App\Models\RekapData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class RekapDataController extends Controller
{
    public function index()
    {
        try {
            $rekapData = RekapData::all();
            return Response::json($rekapData, 200);
        } catch (\Exception $e) {
            return Response::json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
    
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

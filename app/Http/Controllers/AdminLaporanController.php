<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;

class AdminLaporanController extends Controller
{
    public function index()
    {
        $laporans = Laporan::with('pelapor')->latest()->get();
        return response()->json([
            'status' => 'success',
            'laporans' => $laporans
        ]);
    }

    public function show($id)
    {
        $laporan = Laporan::with('pelapor')->find($id);

        if (!$laporan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Laporan not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'laporan' => $laporan,
        ]);
    }

    public function update(Request $request, $id)
    {
        $laporan = Laporan::find($id);

        if (!$laporan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Laporan not found',
            ], 404);
        }

        $request->validate([
            'status' => 'required|in:Baru Masuk,Sedang Diverifikasi,Selesai Ditindaklanjuti',
        ]);

        $laporan->status = $request->status;
        $laporan->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Laporan status updated successfully',
            'laporan' => $laporan,
        ]);
    }

    public function destroy($id)
    {
        $laporan = Laporan::find($id);

        if (!$laporan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Laporan not found',
            ], 404);
        }

        // Delete photo if exists
        if ($laporan->foto && File::exists(public_path($laporan->foto))) {
            File::delete(public_path($laporan->foto));
        }

        $laporan->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Laporan deleted successfully',
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laporan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;

class LaporanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $laporans = Laporan::with('pelapor')->get();
        return response()->json([
            'status' => 'success',
            'data' => $laporans
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kategori' => 'required|in:Keamanan,Aksesibilitas,Fasilitas Rusak',
            'lokasi' => 'required|string|max:255',
            'foto' => 'required|image|max:2048',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = \Illuminate\Support\Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/laporans'), $filename);
            $fotoPath = 'uploads/laporans/' . $filename;
        }

        $laporan = Laporan::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'kategori' => $request->kategori,
            'lokasi' => $request->lokasi,
            'foto' => $fotoPath,
            'pelapor_id' => auth('api')->id(),
            'status' => 'Baru Masuk',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Laporan created successfully',
            'data' => $laporan
        ], 201);
    }

    public function show($id)
    {
        $laporan = Laporan::with('pelapor')->find($id);
        if (!$laporan) {
            return response()->json(['status' => 'error', 'message' => 'Laporan not found'], 404);
        }
        return response()->json([
            'status' => 'success',
            'data' => $laporan
        ]);
    }

    public function update(Request $request, $id)
    {
        $laporan = Laporan::find($id);
        if (!$laporan) {
            return response()->json(['status' => 'error', 'message' => 'Laporan not found'], 404);
        }

        // Check ownership
        if ($laporan->pelapor_id !== auth('api')->id()) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'judul' => 'string|max:255',
            'deskripsi' => 'string',
            'kategori' => 'in:Keamanan,Aksesibilitas,Fasilitas Rusak',
            'lokasi' => 'string|max:255',
            'foto' => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['foto', 'status', 'pelapor_id']); 

        if ($request->hasFile('foto')) {
            if ($laporan->foto && file_exists(public_path($laporan->foto))) {
                unlink(public_path($laporan->foto));
            }

            $file = $request->file('foto');
            $filename = \Illuminate\Support\Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/laporans'), $filename);
            $data['foto'] = 'uploads/laporans/' . $filename;
        }

        $laporan->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Laporan updated successfully',
            'data' => $laporan
        ]);
    }

    public function destroy($id)
    {
        $laporan = Laporan::find($id);
        if (!$laporan) {
            return response()->json(['status' => 'error', 'message' => 'Laporan not found'], 404);
        }

        // Check ownership
        if ($laporan->pelapor_id !== auth('api')->id()) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        // Delete photo
        if ($laporan->foto && file_exists(public_path($laporan->foto))) {
            unlink(public_path($laporan->foto));
        }

        $laporan->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Laporan deleted successfully'
        ]);
    }
}

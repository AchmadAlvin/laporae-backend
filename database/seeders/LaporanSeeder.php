<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Laporan;

class LaporanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Laporan::create([
            'pelapor_id' => 2,
            'judul' => 'Trotoar Rusak dan Berlubang',
            'deskripsi' => 'Trotoar di depan stasiun rusak parah, sulit dilewati pejalan kaki dan berbahaya bagi pengguna kursi roda.',
            'kategori' => 'Fasilitas Rusak',
            'lokasi' => 'Depan Stasiun Madiun, Jl Pahlawan',
            'foto' => 'storage/laporans/laporan1.jpg',
            'status' => 'Baru Masuk',
        ]);
        Laporan::create([
            'pelapor_id' => 3,
            'judul' => 'Halte Bus Tidak Ada Ramp',
            'deskripsi' => 'Halte Bus di Pasar Besar tidak ada jalan landai (ramp) untuk naik turun kursi roda, sangat menyulitkan.',
            'kategori' => 'Aksesibilitas',
            'lokasi' => 'Halte Pasar Besar, Jl Sudirman',
            'foto' => 'storage/laporans/laporan2.jpg',
            'status' => 'Sedang Diverifikasi',
        ]);
        Laporan::create([
            'pelapor_id' => 2,
            'judul' => 'Lampu Jalan Mati',
            'deskripsi' => 'Lampu jalan di sekitar Terminal Purboyo banyak yang mati, membuat suasana menjadi gelap dan rawan di malam hari.',
            'kategori' => 'Keamanan',
            'lokasi' => 'Sekitar Terminal Purboyo',
            'foto' => 'storage/laporans/laporan3.jpg',
            'status' => 'Selesai Ditindaklanjuti'
        ]);
    }
}

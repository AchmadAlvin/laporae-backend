# LaporAE Backend API

## Getting Started (Untuk Frontend Team)

Ikuti langkah ini untuk menjalankan backend di local kalian.

### 1. Setup Awal
Pastikan sudah install **PHP** dan **Composer**.

1. Buka terminal di folder ini.
2. Install dependencies:
   composer install
3. Copy file env:
   cp .env.example .env

4. Generate app key:
   php artisan key:generate
5. Generate JWT secret (untuk login):
   php artisan jwt:secret
6. Setup Database & Tables:
   php artisan migrate

### 2. Serve
php artisan serve

### 3. Dokumentasi API & Cara Pakai
- **Base URL API**: `http://localhost:8000/api`
- **Dokumentasi Lengkap**: Import file `LaporAE_API.postman_collection.json` (ada di folder ini) ke aplikasi **Postman** (Klik tombol **Import** di pojok kiri atas -> **Upload Files**).
- **Auth**: Gunakan endpoint `login` untuk dapat token, lalu pakai token itu di Header `Authorization: Bearer <token>` untuk request lainnya.

### 4. Catatan Penting untuk Frontend
- **Upload Foto (Create Laporan)**:
  Saat membuat laporan baru (`POST /api/laporans`), **JANGAN** kirim data sebagai JSON raw.
  Kalian **WAJIB** menggunakan `FormData` (multipart/form-data) karena ada upload file gambar.
  
  Contoh format request body:
  - `judul`: "Jalan Rusak" (Text)
  - `deskripsi`: "Ada lubang besar..." (Text)
  - `kategori`: "Fasilitas Rusak" (Text)
  - `lokasi`: "Jl. Sudirman" (Text)
  - `foto`: [File Gambar] (File)

- **Header Request**:
  Selalu sertakan header `Accept: application/json` di setiap request agar backend mengembalikan response JSON yang benar jika terjadi error.

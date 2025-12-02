# LaporAE Backend API

## Getting Started (ngge manusia FE).

### 1. Setup
composer install
cp .env.example .env
php artisan key:generate

Generate JWT secret (untuk login):
php artisan jwt:secret

Setup Database & Tables:
   php artisan migrate

### 2. Serve
php artisan serve

### 3. Dokumentasi API & Cara Pakai
- **Base URL API**: `http://127.0.0.1:8000/api`
- **Dokumentasi**: Import file `LaporAE_API.postman_collection.json` ke aplikasi **Postman** (Klik tombol **Import** di pojok kiri atas **Upload Files**).
- **Auth**: Gunakan endpoint `login` untuk dapat token, lalu pakai token itu di Header `Authorization: Bearer <token>` untuk request lainnya.

### 4. NOTEEEE!!!!!!!
- **Upload Foto (Create Laporan)**:
  Saat membuat laporan baru (`POST /api/laporans`), **JANGAN** kirim data sebagai JSON raw, **WAJIB** menggunakan `FormData` (multipart/form-data) karena ada upload file gambar.
  
  Contoh format request body:
  - `judul`: "Jalan Rusak" (Text)
  - `deskripsi`: "Ada lubang besar..." (Text)
  - `kategori`: "Fasilitas Rusak" (Text)
  - `lokasi`: "Jl. Sudirman" (Text)
  - `foto`: [File Gambar] (File)

- **Header Request**:
  Selalu sertakan header `Accept: application/json` di setiap request agar backend mengembalikan response JSON yang benar jika terjadi error.

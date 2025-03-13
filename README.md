# Todo API

Todo API adalah sebuah aplikasi backend yang dibangun menggunakan [Laravel](https://laravel.com/), sebuah framework PHP yang elegan dan ekspresif. Aplikasi ini menyediakan API untuk mengelola daftar tugas (todo) yang memungkinkan pengguna untuk membuat, membaca, memperbarui, dan menghapus tugas mereka.

## 🚀 Fitur

- **Autentikasi Pengguna**: Menggunakan Laravel Sanctum untuk otentikasi berbasis token.
- **Manajemen Checklist**:
  - Membuat checklist baru
  - Melihat daftar checklist
  - Memperbarui checklist yang ada
  - Menghapus checklist
- **Manajemen Checklist Items**:
  - Menambahkan item ke dalam checklist
  - Melihat item dalam checklist
  - Memperbarui status item
  - Menghapus item dalam checklist

## 🛠 Instalasi

1. **Clone repositori**:
   ```bash
   git clone https://github.com/erziealdrian02/todo-api.git
   cd todo-api
   ```

2. **Instal dependensi**:
   ```bash
   composer install
   ```

3. **Salin file environment**:
   ```bash
   cp .env.example .env
   ```

4. **Atur kunci aplikasi**:
   ```bash
   php artisan key:generate
   ```

5. **Konfigurasi database**:
   Sesuaikan pengaturan database di file `.env` sesuai dengan konfigurasi Anda.

6. **Migrasi database**:
   ```bash
   php artisan migrate
   ```
   **Atau jika tidak ingin melakukan migrasi, download file SQL berikut dan import ke database Anda:** [Download SQL](#)

7. **Jalankan server pengembangan**:
   ```bash
   php artisan serve
   ```

## 📌 Penggunaan

Setelah server berjalan, Anda dapat menggunakan alat seperti [Postman](https://www.postman.com/) atau [cURL](https://curl.se/) untuk berinteraksi dengan API.

### 🔑 Autentikasi

Gunakan endpoint `/login` untuk mendapatkan token autentikasi. Sertakan token ini di header `Authorization` dengan format:
```
Bearer {token}
```
untuk mengakses endpoint yang dilindungi.

### 📌 Endpoint Utama

#### **Checklist**
- `GET /api/checklist` → Mengambil daftar checklist.
- `POST /api/checklist` → Membuat checklist baru.
- `GET /api/checklist/{id}` → Mengambil checklist berdasarkan ID.
- `PUT /api/checklist/{id}` → Memperbarui checklist berdasarkan ID.
- `DELETE /api/checklist/{id}` → Menghapus checklist berdasarkan ID.

#### **Checklist Items**
- `GET /api/checklist/{checklist_id}/item` → Mengambil daftar item dalam checklist.
- `POST /api/checklist/{checklist_id}/item` → Menambahkan item ke checklist.
- `GET /api/checklist/{checklist_id}/item/{item_id}` → Mengambil item dalam checklist berdasarkan ID.
- `PUT /api/checklist/{checklist_id}/item/{item_id}` → Memperbarui status item dalam checklist.
- `PUT /api/checklist/{checklist_id}/item/rename/{item_id}` → Mengubah nama item dalam checklist.
- `DELETE /api/checklist/{checklist_id}/item/{item_id}` → Menghapus item dalam checklist.

## 🤝 Kontribusi

Kontribusi sangat diterima. Silakan fork repositori ini dan buat pull request untuk perbaikan atau penambahan fitur.

## 📄 Lisensi

Proyek ini dilisensikan di bawah [MIT License](https://opensource.org/licenses/MIT).

---

✨ Semoga README ini membantu Anda dalam memahami dan menjalankan proyek "todo-api" dengan lebih baik! 🚀

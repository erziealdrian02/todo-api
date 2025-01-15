<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

<hr>

# Backend Todo Repositories API

Sebuah RESTful API sederhana untuk mengelola tugas (Todo). API ini dirancang untuk memenuhi kebutuhan backend aplikasi Todo dengan fitur seperti menambahkan, membaca, memperbarui, dan menghapus data Todo.

---

## 🚀 Fitur Utama
- CRUD Todo (Create, Read, Update, Delete)
- Filter dan pencarian Todo berdasarkan kriteria
- Pengelolaan status Todo (selesai atau belum selesai)
- Autentikasi menggunakan token (opsional)

---

## 📦 Instalasi

1. **Clone repository ini:**
   ```bash
   git clone https://github.com/erziealdrian02/todo-api.git
   ```
2. **Masuk ke direktori proyek:**
   ```bash
   cd todo-api
   ```
3. **Install Composer:**
   ```bash
   install composer
   ```
4. **Install dependencies:**
   ```bash
   npm install
   ```
   atau
   ```bash
   yarn install
   ```
5. **Konfigurasi environment:**
   - Buat file `.env` berdasarkan template `.env.example`.
   - Isi konfigurasi berikut:
     ```
     DB_HOST=localhost
     DB_PORT=3306
     DB_USER=root
     DB_PASSWORD=yourpassword
     DB_NAME=todo-db
     PORT=3000
     ```

6. **Migrasi database (jika diperlukan):**
   ```bash
   php artisan migrate
   ```
7. **Jalankan server:**
   ```bash
   npm run artisan
   ```
---

## 🛠️ API Endpoints

### **Authentication**
| Method | Endpoint        | Deskripsi                |
|--------|-----------------|--------------------------|
| POST   | `/api/login`  | Login user dan dapatkan token. |
| POST   | `/api/register` | Registrasi user baru.        |
| POST   | `/api/logout` | Log-Out user .           |

### **Todo (After Authentication)**
| Method | Endpoint          | Deskripsi                            |
|--------|-------------------|--------------------------------------|
| GET    | `/api/todo`       | Mendapatkan daftar semua Todo.       |
| POST   | `/api/todo`       | Menambahkan Todo baru.              |
| GET    | `/api/todo/:id`   | Mendapatkan detail Todo tertentu.    |
| PUT    | `/api/todo/:id`   | Memperbarui Todo tertentu.           |
| DELETE | `/api/todo/:id`   | Menghapus Todo tertentu.             |

### **Todo (After Authentication)**
| Endpoint          | Deskripsi                            |
|-------------------|--------------------------------------|
| `/api`       | Dokumentasi Swagger.       |

---

## 📋 Contoh Penggunaan API

### **POST /api/auth/login**
**Request Body:**
```json
{
  "email": "user@example.com",
  "password": "yourpassword"
}
```
**Response:**
```json
{
  "token": "your-jwt-token"
}
```

### **GET /api/todo**
**Header:**
```
Authorization: Bearer your-jwt-token
```
**Response:**
```json
[
  {
    "id": 1,
    "title": "Belajar Node.js",
    "description": "Menyelesaikan tugas tentang Node.js",
    "status": "pending",
    "created_at": "2025-01-15T10:00:00Z",
    "updated_at": "2025-01-15T12:00:00Z"
  }
]
```

---

## 🔗 Dokumentasi API
Dokumentasi lengkap API tersedia melalui Postman:
- **Postman Link**: [Postman Collection](https://postman.example.com/your-link)

---

## 👨‍💻 Kontribusi
1. Fork repository ini.
2. Buat branch baru untuk fitur/bugfix: `git checkout -b feature-branch`.
3. Commit perubahan Anda: `git commit -m 'Add feature X'`.
4. Push ke branch Anda: `git push origin feature-branch`.
5. Buat Pull Request.

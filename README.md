# 🚗 Rental Mobil API

Sistem API untuk manajemen rental mobil berbasis Laravel 11 dengan autentikasi JWT.

![Laravel](https://img.shields.io/badge/Laravel-11.x-red?style=flat-square&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue?style=flat-square&logo=php)
![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)

## 📋 Deskripsi

Rental Mobil API adalah sistem backend untuk aplikasi rental mobil yang menyediakan fitur lengkap untuk mengelola:

-   **Autentikasi** - Register, Login, Logout dengan JWT Token
-   **Kategori Mobil** - Manajemen kategori kendaraan
-   **Mobil** - CRUD data mobil dengan upload gambar
-   **Booking** - Pemesanan mobil dengan kalkulasi harga otomatis
-   **Pembayaran** - Konfirmasi pembayaran dengan upload bukti
-   **Review** - Rating dan ulasan dari customer
-   **Activity Log** - Pencatatan aktivitas (admin only)

## 🏗️ Arsitektur

```
app/
├── Http/
│   └── Controllers/
│       └── Api/
│           ├── AuthController.php
│           ├── BookingController.php
│           ├── CarController.php
│           ├── CategoryController.php
│           ├── PaymentController.php
│           └── ReviewController.php
├── Models/
│   ├── ActivityLog.php
│   ├── Booking.php
│   ├── Car.php
│   ├── Category.php
│   ├── Payment.php
│   ├── Review.php
│   └── User.php
└── Providers/
```

## 🛠️ Tech Stack

-   **Framework:** Laravel 11
-   **PHP Version:** 8.2+
-   **Authentication:** JWT (php-open-source-saver/jwt-auth)
-   **Database:** MySQL/PostgreSQL
-   **Testing:** PHPUnit

## 📦 Requirements

-   PHP >= 8.2
-   Composer
-   MySQL/PostgreSQL
-   Node.js (untuk frontend assets)

## 🚀 Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/R3n-o/rental-mobil.git
cd rental-mobil
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Konfigurasi Environment

```bash
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
```

### 4. Konfigurasi Database

Edit file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rental_mobil
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Migrasi Database

```bash
php artisan migrate
php artisan db:seed
```

### 6. Storage Link

```bash
php artisan storage:link
```

### 7. Jalankan Server

```bash
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

## 📚 API Endpoints

### Authentication

| Method | Endpoint        | Deskripsi            | Auth |
| ------ | --------------- | -------------------- | ---- |
| POST   | `/api/register` | Registrasi user baru | ❌   |
| POST   | `/api/login`    | Login user           | ❌   |
| POST   | `/api/logout`   | Logout user          | ✅   |

### Categories

| Method | Endpoint               | Deskripsi               | Auth |
| ------ | ---------------------- | ----------------------- | ---- |
| GET    | `/api/categories`      | List semua kategori     | ❌   |
| POST   | `/api/categories`      | Tambah kategori (admin) | ✅   |
| PUT    | `/api/categories/{id}` | Update kategori (admin) | ✅   |
| DELETE | `/api/categories/{id}` | Hapus kategori (admin)  | ✅   |

### Cars

| Method | Endpoint                | Deskripsi            | Auth |
| ------ | ----------------------- | -------------------- | ---- |
| GET    | `/api/cars`             | List semua mobil     | ❌   |
| GET    | `/api/cars/{id}`        | Detail mobil         | ❌   |
| POST   | `/api/cars`             | Tambah mobil (admin) | ✅   |
| PUT    | `/api/cars/{id}`        | Update mobil (admin) | ✅   |
| DELETE | `/api/cars/{id}`        | Hapus mobil (admin)  | ✅   |
| PATCH  | `/api/cars/{id}/status` | Update status mobil  | ✅   |

### Bookings

| Method | Endpoint             | Deskripsi                  | Auth |
| ------ | -------------------- | -------------------------- | ---- |
| GET    | `/api/bookings`      | List booking (sesuai role) | ✅   |
| POST   | `/api/bookings`      | Buat booking baru          | ✅   |
| GET    | `/api/bookings/{id}` | Detail booking             | ✅   |
| DELETE | `/api/bookings/{id}` | Batalkan booking           | ✅   |

### Payments

| Method | Endpoint             | Deskripsi                | Auth |
| ------ | -------------------- | ------------------------ | ---- |
| POST   | `/api/payments`      | Konfirmasi pembayaran    | ✅   |
| PUT    | `/api/payments/{id}` | Update status pembayaran | ✅   |

### Reviews

| Method | Endpoint       | Deskripsi     | Auth |
| ------ | -------------- | ------------- | ---- |
| POST   | `/api/reviews` | Tambah review | ✅   |

### Activity Logs

| Method | Endpoint             | Deskripsi                 | Auth |
| ------ | -------------------- | ------------------------- | ---- |
| GET    | `/api/activity-logs` | List activity log (admin) | ✅   |

## 🔐 Autentikasi

API menggunakan JWT (JSON Web Token) untuk autentikasi. Setelah login, sertakan token di header:

```
Authorization: Bearer {your_jwt_token}
```

## 👥 User Roles

| Role       | Deskripsi                        |
| ---------- | -------------------------------- |
| `admin`    | Full access ke semua fitur       |
| `customer` | Dapat booking, bayar, dan review |

## 📊 Database Schema

### Users

-   id, name, email, password, phone, address, sim_number, role

### Categories

-   id, name

### Cars

-   id, category_id, name, brand, model, plate_number, daily_rent_price, is_available, image

### Bookings

-   id, user_id, car_id, start_date, end_date, total_price, status

### Payments

-   id, booking_id, payment_date, amount, payment_method, proof_image, status

### Reviews

-   id, user_id, car_id, rating, comment

## 🧪 Testing

Jalankan unit test:

```bash
php artisan test
```

Atau menggunakan PHPUnit langsung:

```bash
./vendor/bin/phpunit
```

## 📁 Postman Collection

File koleksi Postman tersedia di:

```
docs/Rental-Mobil.postman_collection.json
```

Import ke Postman untuk testing API.

## 📝 Contoh Request

### Register User

```json
POST /api/register
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "phone": "081234567890",
    "address": "Jl. Contoh No. 123"
}
```

### Login

```json
POST /api/login
{
    "email": "john@example.com",
    "password": "password123"
}
```

### Buat Booking

```json
POST /api/bookings
{
    "car_id": 1,
    "start_date": "2026-01-15",
    "end_date": "2026-01-17"
}
```

## 🤝 Contributing

1. Fork repository ini
2. Buat branch fitur (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## 📄 License

Project ini menggunakan lisensi MIT. Lihat file [LICENSE](LICENSE) untuk detail.

## 👨‍💻 Author

**Maulidin**

---

⭐ Jangan lupa beri star jika project ini membantu!

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

# Admin Login Setup & Test

## 1. Pastikan Server Sudah Running

-   Buka terminal di folder `pbl4`
-   Jalankan: `php artisan serve`
-   Server akan jalan di `http://127.0.0.1:8000`

## 2. Buat Admin (register dulu)

**Endpoint:** `POST http://127.0.0.1:8000/api/admin/register`

**Headers:**

```
Content-Type: application/json
```

**Body (raw JSON):**

```json
{
    "nama": "admin",
    "password": "password123"
}
```

**Expected Response (201):**

```json
{
    "message": "Admin terdaftar",
    "user": {
        "id": 1,
        "nama": "admin",
        "created_at": "...",
        "updated_at": "..."
    },
    "token": "1|abc..."
}
```

## 3. Login Admin

**Endpoint:** `POST http://127.0.0.1:8000/api/admin/login`

**Headers:**

```
Content-Type: application/json
```

**Body (raw JSON):**

```json
{
    "nama": "admin",
    "password": "password123"
}
```

**Expected Response (200):**

```json
{
    "user": {
        "id": 1,
        "nama": "admin",
        "created_at": "...",
        "updated_at": "..."
    },
    "token": "1|abc..."
}
```

## 4. Troubleshooting

### Error: "Nama atau password salah"

-   Pastikan admin sudah dibuat dengan endpoint register
-   Pastikan password yang dimasukkan sesuai saat register

### Error: "Nama sudah terdaftar"

-   Admin dengan nama tersebut sudah ada
-   Ganti nama atau gunakan nama berbeda

### Error: HTML halaman error (500, 404, etc)

-   Cek error log di `storage/logs/laravel.log`
-   Pastikan database sudah di-migrate: `php artisan migrate`
-   Pastikan server running dengan `php artisan serve`

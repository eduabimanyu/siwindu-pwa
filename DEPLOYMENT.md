# Panduan Deploy Siwindu PWA ke Hosting

Aplikasi ini terdiri dari dua bagian utama:
1. **Backend (Laravel)**: Menyediakan API dan mengelola database.
2. **Frontend (Vue.js PWA)**: Antarmuka pengguna untuk Mobile/Desktop.

Berikut adalah panduan langkah demi langkah untuk melakukan *deployment* ke **Shared Hosting (cPanel)** atau **VPS**.

---

## Opsi 1: Shared Hosting (cPanel)

Jika Anda menggunakan hosting dengan cPanel, ikuti langkah-langkah berikut:

### 1. Persiapan Build Frontend (Lokal)
Karena cPanel *shared hosting* biasanya tidak memiliki Node.js, kita harus melakukan *build* frontend di komputer lokal terlebih dahulu.

1. Buka terminal dan masuk ke folder frontend:
   ```bash
   cd vue-pwa-frontend
   ```
2. Pastikan file `.env` di folder ini sudah mengarah ke URL production backend Anda. Contoh:
   ```env
   VITE_API_URL=https://api.domainanda.com/api
   ```
3. Lakukan build:
   ```bash
   npm run build
   ```
4. Setelah selesai, folder `dist` akan tercipta di dalam `vue-pwa-frontend`. File-file di dalam folder `dist` inilah yang nantinya akan di-upload.

### 2. Upload Backend (Laravel)
1. Buka **File Manager** di cPanel Anda.
2. Buat sebuah folder baru di luar folder `public_html`, misalnya bernama `siwindu-backend`.
3. Compress semua isi folder utama proyek ini (kecuali folder `vue-pwa-frontend`, `node_modules`, `.git`, dan file besar seperti `.zip` atau `.sql`) menjadi file `.zip`.
4. Upload file zip tersebut ke folder `siwindu-backend` di cPanel Anda, lalu ekstrak.

### 3. Konfigurasi Public Backend & Upload Frontend
1. Pindahkan **seluruh isi** dari folder `public` milik Laravel yang ada di dalam `siwindu-backend` ke dalam folder `public_html` hosting Anda.
2. Buka file `index.php` yang baru saja Anda pindahkan ke `public_html` lalu edit baris berikut:
   ```php
   // Ubah jalur ini untuk mengarah ke folder backend yang baru
   require __DIR__.'/../siwindu-backend/vendor/autoload.php';
   require_once __DIR__.'/../siwindu-backend/bootstrap/app.php';
   ```
3. Upload seluruh isi folder `dist` (hasil build frontend di Langkah 1) langsung ke dalam folder `public_html` juga.
   > **Catatan**: Jika API dan Frontend berada dalam satu domain, frontend Vue PWA Anda kini dapat diakses di `https://domainanda.com` dan API Laravel di `https://domainanda.com/api`.

### 4. Konfigurasi Database & .env
1. Buat database MySQL baru di cPanel melalui menu **MySQL Databases**.
2. Masukkan struktur database Anda menggunakan menu **phpMyAdmin** (import file `.sql` Anda).
3. Di dalam folder `siwindu-backend`, salin file `.env.example` menjadi `.env`.
4. Edit file `.env` dan sesuaikan pengaturan database serta ubah `APP_ENV` dan `APP_DEBUG`:
   ```env
   APP_NAME=SiwinduPWA
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://domainanda.com

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nama_database_anda
   DB_USERNAME=user_database_anda
   DB_PASSWORD=password_database_anda
   ```

### 5. Penyelesaian Backend
1. Jika hosting Anda menyediakan terminal SSH, masuk ke folder `siwindu-backend` lalu jalankan:
   ```bash
   php artisan key:generate
   php artisan optimize:clear
   php artisan storage:link
   ```
2. Selesai! Coba akses website Anda.

---

## Opsi 2: Virtual Private Server (VPS) / Ubuntu

Bila Anda menggunakan VPS, prosesnya jauh lebih leluasa karena Anda memiliki akses root.

### 1. Prasyarat Server
Pastikan VPS Anda telah diinstal:
- Nginx / Apache
- PHP 8.1+ & ekstensi yang dibutuhkan (bcmath, ctype, fileinfo, json, mbstring, pdo, tokenizer, xml)
- MySQL / MariaDB
- Node.js (Minimal versi 18) & NPM
- Composer
- Git

### 2. Clone Repository
```bash
cd /var/www
git clone https://github.com/eduabimanyu/siwindu-pwa.git
cd siwindu-pwa
```

### 3. Setup Backend Laravel
1. Instal dependensi PHP:
   ```bash
   composer install --optimize-autoloader --no-dev
   ```
2. Atur *environment*:
   ```bash
   cp .env.example .env
   # Edit file .env dan sesuaikan DB (sama seperti langkah di cPanel)
   nano .env
   ```
3. Generate Key dan Cache:
   ```bash
   php artisan key:generate
   php artisan migrate --force  # Jika Anda belum melakukan dump database
   php artisan storage:link
   php artisan optimize
   ```
4. Ubah kepemilikan folder agar dapat diakses web server:
   ```bash
   chown -R www-data:www-data /var/www/siwindu-pwa
   chmod -R 775 storage bootstrap/cache
   ```

### 4. Setup Frontend Vue PWA
1. Masuk ke folder frontend:
   ```bash
   cd vue-pwa-frontend
   ```
2. Sesuaikan file `.env`:
   ```bash
   nano .env
   # Pastikan VITE_API_URL menunjuk ke domain production (misal: https://api.domainanda.com/api)
   ```
3. Instal library dan lakukan build:
   ```bash
   npm install
   npm run build
   ```

### 5. Konfigurasi Nginx
Buat file konfigurasi Nginx baru (contoh `/etc/nginx/sites-available/siwindu.conf`):

```nginx
server {
    listen 80;
    server_name domainanda.com;
    root /var/www/siwindu-pwa/vue-pwa-frontend/dist; # Arahkan ke hasil build frontend

    index index.html;

    # Konfigurasi agar Vue Router PWA bisa diakses tanpa error 404
    location / {
        try_files $uri $uri/ /index.html;
    }

    # Konfigurasi reverse proxy untuk API Laravel
    location /api {
        alias /var/www/siwindu-pwa/public;
        try_files $uri $uri/ @api;
        
        location ~ \.php$ {
            include snippets/fastcgi-php.conf;
            fastcgi_pass unix:/var/run/php/php8.2-fpm.sock; # Sesuaikan versi PHP
            fastcgi_param SCRIPT_FILENAME $request_filename;
        }
    }

    location @api {
        rewrite /api/(.*)$ /api/index.php?/$1 last;
    }
}
```

Aktifkan konfigurasi, test Nginx, dan *restart*:
```bash
ln -s /etc/nginx/sites-available/siwindu.conf /etc/nginx/sites-enabled/
nginx -t
systemctl restart nginx
```

Selesai! Kini aplikasi berhasil berjalan di server produksi.

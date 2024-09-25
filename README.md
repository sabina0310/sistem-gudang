# Sistem Gudang dengan Laravel dan JWT Authentication

Project Laravel ini yang dibangun menggunakan Laravel V.10 dengan autentikasi menggunakan JWT (Json Web Token). Proyek ini mendukung login, logout, CRUD, serta pengelolaan mutasi barang dan stok.

## Fitur
- Autentikasi JWT untuk API
- CRUD untuk pengguna, barang, dan mutasi
- History mutasi untuk tiap barang dan pengguna

## Requirement

Sebelum memulai instalasi, pastikan Anda sudah menginstal beberapa alat berikut:

- [PHP](https://www.php.net/) versi 8.1 atau lebih tinggi
- [Composer](https://getcomposer.org/) sebagai dependency manager untuk PHP
- [MySQL](https://www.mysql.com/)

## Instalasi
### 1. Clone Repository
git clone https://github.com/sabina0310/sistem-gudang

### 2. Masuk Direktori Project 
cd repository-name

### 3. Install Dependencies
composer install

### 4. Copy .env file
Buat salinan file .env.example dan beri nama .env

### 5. Generate Application Key
Generate Application Key

### 6. Konfigurasi JWT
php artisan jwt:secret

### 7. Migrasi dan Seeder Database
php artisan migrate --seed

### 8. Jalankan Server
php artisan serve

### 9. Test Login 
Gunakan akun berikut untuk login 
email : admin@gmail.com
password: 123

## Dokumentasi API
Berikut link dokumentasi REST API sistem gudang
https://documenter.getpostman.com/view/38324462/2sAXqwZKi8

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
git clone https://github.com/username/repository-name.git

### 2. Masuk Direktori Project 
cd repository-name

### 3. Instal Dependencies
composer install

### 4. Copy .env file
Buat salinan file .env.example dan beri nama .env

### 5. Generate Application Key
Generate Application Key

### 6. Konfigurasi JWT
php artisan jwt:secret

### 7. Migrasi dan Seeder Database


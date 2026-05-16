# MCU & Polyclinic Management System

Selamat datang di repositori aplikasi **MCU & Polyclinic Management System**. Aplikasi ini dibangun menggunakan framework **Laravel 12** untuk mengelola data Medical Check-Up (MCU), Poliklinik, Laboratorium, hingga sistem Kuitansi dan Invoice.

---

## 📌 Persyaratan Sistem (Prerequisites)

Sebelum memulai proses instalasi, pastikan server atau komputer lokal Anda sudah memenuhi persyaratan berikut:
* **PHP >= 8.2** (Disarankan PHP 8.3+)
* Ekstensi PHP wajib: `BCMath`, `Ctype`, `Fileinfo`, `JSON`, `Mbstring`, `OpenSSL`, `PDO`, `Tokenizer`, `XML`
* **Composer** (Versi terbaru)
* **MySQL / MariaDB**
* **Web Server** (Apache, Nginx, atau Laravel Artisan CLI)

---

## 🛠️ Langkah-Langkah Instalasi

Ikuti panduan di bawah ini untuk memasang aplikasi di lingkungan lokal atau server Anda:

### 1. Clone Repositori
Pertama, unduh source code aplikasi ini ke komputer Anda:
```bash
git clone [https://github.com/Meteor95/mcu_polyclinic_app.git](https://github.com/Meteor95/mcu_polyclinic_app.git)
cd mcu_polyclinic_app
```
### 2. Install Dependencies via Composer

Jalankan perintah berikut untuk mengunduh semua library PHP yang dibutuhkan oleh Laravel:

```bash
composer install
```
### 3. Konfigurasi Environment (.env)

Salin file konfigurasi bawaan .env.example menjadi .env:
```bash
cp .env.example .env
```

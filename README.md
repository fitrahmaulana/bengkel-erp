## Pengaturan Awal

### 1. Clone Repository
Cari lokasi di komputer Anda untuk menyimpan proyek. Direktori yang dibuat khusus untuk proyek biasanya merupakan pilihan yang baik.

Buka konsol bash di lokasi tersebut dan clone proyeknya.

`git clone https://github.com/fitrahmaulana/bengkel-erp.git`

### 2. Masuk ke Direktori Proyek
Anda perlu masuk ke direktori proyek yang baru saja dibuat, jadi masuklah ke dalamnya.

`cd bengkel_erp`

### 3. Instal Dependensi Composer
Setiap kali Anda meng-clone proyek Laravel baru, Anda harus menginstal semua dependensi proyek. Ini termasuk menginstal Laravel dan paket lain yang diperlukan.

`composer install`

### 4. Instal Dependensi NPM
Seperti halnya composer, npm mengelola paket javascript, css, dan node. Pastikan untuk menginstal dependensi tersebut juga.

`npm install`

### 5. Salin File .env
File .env biasanya tidak di-commit ke kontrol sumber untuk alasan keamanan. Namun, ada .env.example yang merupakan template dari file .env yang dibutuhkan proyek.

Salin file .env.example dan beri nama .env untuk menyiapkan konfigurasi deployment lokal Anda di langkah-langkah selanjutnya.

`cp .env.example .env`

### 6. Buat Kunci Enkripsi Aplikasi
Laravel memerlukan kunci enkripsi aplikasi yang biasanya dihasilkan secara acak dan disimpan di file .env. Kunci ini digunakan untuk mengenkripsi berbagai elemen dalam aplikasi, seperti cookie dan hash kata sandi.

Gunakan perintah ini untuk menghasilkan kunci enkripsi:

`php artisan key:generate`

### 7. Buat Database Kosong untuk Aplikasi
Buat database kosong untuk proyek Anda menggunakan alat database pilihan Anda (phpmyadmin, datagrip, atau klien mysql lainnya).

### 8. Tambahkan Informasi Database di File .env
Tambahkan kredensial koneksi database di file .env agar Laravel dapat terhubung ke database yang baru saja Anda buat.

Isi opsi **DB_HOST**, **DB_PORT**, **DB_DATABASE**, **DB_USERNAME**, dan **DB_PASSWORD** dengan kredensial database yang Anda buat.

### 9. Branding & Nama
Di file .env, Anda dapat menyesuaikan nilai untuk **MIX_APP_BRANDING** dan **MIX_APP_NAME**. Perhatikan bahwa **MIX_APP_BRANDING** bisa berupa string kosong jika tidak ada branding yang diinginkan.

### 10. Migrasi Database
Setelah kredensial Anda diisi di file .env, Anda dapat melakukan migrasi database. Ini akan membuat semua tabel yang diperlukan di database Anda.

`php artisan migrate`

## Selama Pengembangan

### Mengkompilasi Aset
Untuk mengkompilasi semua aset sass dan js menggunakan webpack, jalankan perintah berikut.

`npm run dev`

Anda juga dapat menjalankan perintah berikut yang akan terus berjalan di terminal dan memantau semua file terkait untuk perubahan. Webpack akan otomatis mengkompilasi ulang aset Anda ketika mendeteksi perubahan.

`npm run watch`

### Server Pengembangan Lokal
Untuk menjalankan server pengembangan lokal, Anda bisa menjalankan perintah berikut. Ini akan memulai server pengembangan di **http://localhost:8000**.

`php artisan serve`

# GEMARI DIGITAL 🎓💼

**GEMARI DIGITAL** (Gerakan & Sistem Informasi Pengelolaan Prakerin dan Magang Mahasiswa) adalah aplikasi berbasis web yang dirancang khusus untuk mempermudah instansi dalam mengelola siklus operasional, data diri, penempatan, kehadiran, dan administrasi peserta Praktik Kerja Lapangan (Siswa SMK) serta Mahasiswa Magang secara terpadu, efisien, dan transparan.

---

## 🚀 Fitur Unggulan

### 1. 👥 Manajemen Data Peserta Lengkap
- **Siswa (Prakerin):** Manajemen biodata siswa SMK, asal sekolah, jurusan, NIS, kontak, dan wali.
- **Mahasiswa (Magang):** Manajemen biodata mahasiswa, asal kampus, program studi, NIM, kontak, dan mentor.
- **Klasifikasi Jenis Magang:**
  - **Magang Reguler:** Skema magang kemitraan standar.
  - **Magang Berbayar:** Skema magang mandiri / khusus dengan insentif/pembayaran.
- **Quick Search & Filter:** Pencarian real-time berdasarkan Nama, NIS/NIM, Unit Penempatan, dan Jenis Magang.

### 2. 🗂️ Manajemen Berkas & Dokumen Digital
- Upload dan pratinjau Pas Foto (3x4).
- Unggah dan verifikasi Surat Permohonan Magang (PDF).
- Unggah dan review Laporan Akhir Magang (PDF).
- Saluran akses dokumen aman dengan otorisasi khusus superadmin/admin humas/peserta.

### 3. 🖨️ Cetak Dokumen Otomatis (Print-Ready)
- **ID Card Peserta:** Kartu tanda pengenal dengan foto, nama, nomor induk, unit penempatan, dan logo instansi.
- **Lembar Biodata Resmi:** Rekapitulasi profil lengkap peserta magang untuk arsip resmi.
- **Sertifikat Kelulusan:** Template sertifikat resmi siap cetak lengkap dengan nomor otomatis dan tanggal penerbitan.

### 4. 📅 Manajemen Kuota & Monitoring Kalender
- Validasi kapasitas kuota penerimaan peserta berdasarkan rentang tanggal aktif.
- Kalender interaktif (FullCalendar) untuk melihat jadwal mulai dan berakhirnya masa magang setiap peserta secara visual.

### 5. 🔐 Multi-Role Access Control (RBAC) & Keamanan
- **Superadmin:** Kontrol penuh atas seluruh data, master instansi (kampus & sekolah), kuota, dan user management.
- **Admin Humas:** Pengelolaan harian peserta, penugasan pembimbing/unit, dan monitoring laporan.
- **Peserta (Siswa & Mahasiswa):** Akses mandiri untuk melengkapi data profil, upload berkas, dan mencetak dokumen.
- Autentikasi aman dan sistem Reset Password menggunakan OTP via email.

---

## 🛠️ Tech Stack

- **Backend:** Laravel Framework (PHP)
- **Database:** MySQL
- **Frontend & Styling:** Blade Templating, Tailwind CSS, Alpine.js
- **Plugins:** SweetAlert2, Tom Select, FullCalendar

---

## 📦 Panduan Instalasi Lokal

1. **Clone Repository**
   ```bash
   git clone https://github.com/farhanrmdh77/GEMARI_DIGITAL.git
   cd GEMARI_DIGITAL
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment**
   Salin berkas `.env.example` menjadi `.env`:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Sesuaikan konfigurasi database di `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=gemari_digital
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Migrasi Database & Storage Link**
   ```bash
   php artisan migrate
   php artisan storage:link
   ```

5. **Jalankan Aplikasi**
   ```bash
   php artisan serve
   ```
   Akses di peramban: `http://localhost:8000`

---

## 📄 Lisensi
Sistem ini dikembangkan untuk kebutuhan operasional pengelolaan peserta magang dan prakerin BPK Perwakilan Provinsi Jambi / Instansi terkait.

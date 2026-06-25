# Sistem Keuangan GKJ Dayu

Sistem informasi pengelolaan keuangan untuk Gereja Kristen Jawa (GKJ) Dayu — mencakup perencanaan anggaran, pengajuan dan pencairan dana, realisasi penerimaan/pengeluaran, hingga pelaporan (cetak PDF & Excel). Dibangun dengan PHP native dan arsitektur berbasis peran (*role-based*).

## Arsitektur

Sistem membagi fungsionalitas ke dalam tiga direktori utama berdasarkan hak akses pengguna:

| Direktori | Peran | Fokus Utama |
|-----------|-------|-------------|
| `admin/` | Administrator | Data master, manajemen user, perencanaan anggaran |
| `bendahara/` | Bendahara | Pengelolaan dana, pencairan, dashboard grafik, ekspor Excel |
| `MPH/` | Majelis Pekerja Harian | Peninjauan transaksi, konfirmasi pengajuan, laporan realisasi |

Setiap folder peran memiliki `index.php` sebagai *front controller* yang menangkap permintaan berdasarkan segmen numerik pada URL, lalu memuat berkas fungsional (`inc[NamaFungsi].php`) melalui mapping `switch-case`.

## Teknologi

- **Backend:** PHP 8.1 native (tanpa framework)
- **Database:** MariaDB 10.4/MySQL via `mysqli_*`
- **Frontend:** Bootstrap 5.3, DataTables, jQuery 3.4.1, ionicons, SweetAlert2, Chart.js
- **Ekspor Dokumen:** TCPDF (PDF) dan tabel HTML/PHP (Excel)
- **Deployment:** Docker (Apache + PHP 8.1 + MariaDB 10.11)

## Struktur Direktori

```
.
├── admin/          # Modul Administrator
├── bendahara/      # Modul Bendahara (dashboard, grafik, ekspor Excel)
├── MPH/            # Modul Majelis Pekerja Harian
├── _function_i/    # Fungsi inti CRUD & helper form
├── cetak/          # Ekspor laporan PDF (TCPDF) & Excel
├── tcpdf/          # Library vendor TCPDF
├── bootstrap/      # Bootstrap 5.3 (CSS & JS lokal)
├── js/             # jQuery & script frontend
├── _img/           # Aset gambar dan ikon
├── uploads/        # Berkas lampiran transaksi (diabaikan git)
├── index.php       # Entry point & autentikasi login
├── styles.css      # CSS global
├── Dockerfile
└── docker-compose.yml
```

## Menjalankan dengan Docker

```bash
docker-compose up -d
```

Aplikasi akan tersedia di `http://localhost:8000`. Service `db` (MariaDB 10.11) akan otomatis menjalankan import skema database saat pertama kali dijalankan.

Variabel koneksi database dikonfigurasi melalui environment variable:

| Variabel | Keterangan |
|----------|------------|
| `DB_HOST` | Host database (default: `localhost`) |
| `DB_USERNAME` | Username database (default: `root`) |
| `DB_PASSWORD` | Password database |
| `DB_DATABASE` | Nama database (default: `gkj_dayu`) |

## Alur Bisnis

```
1. PERENCANAAN     Admin → Fiskal → Program → Rencana Penerimaan/Pengeluaran
2. PENGAJUAN DANA  Komisi/Admin → Pengajuan → MPH/Admin meninjau & menyetujui
3. PENCAIRAN       Bendahara → dana dicairkan dari rekening
4. REALISASI       Admin/Bendahara → input Penerimaan & Pengeluaran aktual
5. PELAPORAN       Semua role → laporan web | cetak PDF | unduh Excel
```

## Catatan Keamanan

- Hash password saat ini menggunakan `md5()` — direkomendasikan migrasi ke `bcrypt`/`password_hash()`.
- Validasi input integer menggunakan `intval()`; query DML menggunakan `mysqli_real_escape_string()`.
- Setiap `index.php` per peran memeriksa sesi (`$_SESSION`) sebelum merender halaman.
- Berkas unggahan diberi prefix `time()` untuk mencegah penimpaan nama file.

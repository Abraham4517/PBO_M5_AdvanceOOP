# Advance OOP
Studi Kasus : Sistem Pengelolaan Uang Kas Kelas

## Permasalahan
Pencatatan pembayaran uang kas mingguan oleh bendahara kelas saat ini masih menggunakan buku tulis manual, yang menyebabkan pelacakan tunggakan siswa menjadi lambat, rentan terhadap kesalahan perhitungan saldo, berisiko kehilangan data jika buku rusak, serta kurangnya transparansi laporan keuangan bagi anggota kelas. Tujuan pembuatan sistem pengelolaan uang kas untuk mendigitalisasi pengelolaan uang kas sehingga pencatatan transaksi masuk dan keluar menjadi lebih akurat, aman, dan seluruh siswa dapat memantau transparansi dana secara real-time. 

[Previous Code](https://github.com/Abraham4517/PBO_M4_Encapsulation )

Pada tahap sebelumnya, sistem baru memiliki satu class Siswa yang berdiri sendiri untuk mencatat tunggakan individu. Agar menjadi aplikasi yang utuh, sistem perlu diperluas untuk membedakan peran pengurus (Bendahara), mencatat variasi transaksi (kas masuk dan kas keluar), serta menyusun rekapitulasi saldo akhir di dalam sebuah buku kas. 

## Desain Class & Struktur OOP
Aplikasi ini dirancang menggunakan 6 entitas utama yang saling berelasi:   
  * BisaDicatat (Interface): Kontrak kemampuan yang mewajibkan setiap objek transaksi menyediakan rincian catatan (getRincianTransaksi()) dan perhitungan dampak saldo (hitungDampakSaldo()).
  * Siswa (Parent Class): Mengelola data dasar anggota kelas ($nama_siswa, $nis, $jumlah_tunggakan) dengan enkapsulasi protected dan validasi input.
  * Bendahara (Subclass dari Siswa): Mewakili bendahara kelas yang juga merupakan siswa, dilengkapi atribut periode jabatan dan overriding pada method cek_tunggakan().
  * TransaksiKas (Abstract Class): Kerangka dasar seluruh transaksi keuangan kelas yang menyimpan ID transaksi, tanggal, dan validasi nominal.
  * TransaksiMasuk & TransaksiKeluar (Subclass dari TransaksiKas): Mengimplementasikan detail transaksi penyetoran kas oleh siswa (menambah saldo) dan pengeluaran dana kelas (mengurangi saldo).
  * BukuKas (Composition Class): Wadah buku besar yang menampung kumpulan objek transaksi dan menghitung akumulasi saldo akhir secara otomatis.

## Alasan Pemilihan Relasi
  * Inheritance ("is-a"): Digunakan pada Bendahara extends Siswa karena secara nyata bendahara kelas adalah seorang siswa di kelas tersebut yang juga wajib membayar kas, serta pada TransaksiMasuk dan TransaksiKeluar yang merupakan jenis spesifik dari TransaksiKas.
  *   Abstract Class & Interface ("can-do"): TransaksiKas dijadikan abstract class karena tidak boleh ada objek "transaksi abstrak" yang berdiri sendiri tanpa kejelasan apakah uang tersebut masuk atau keluar. Interface BisaDicatat memastikan BukuKas hanya bergantung pada kontrak kemampuan pencatatan, bukan pada detail class konkret.
  *   Composition ("has-a"): Digunakan pada BukuKas yang menyimpan array objek TransaksiKas. Relasi ini dipilih karena BukuKas memiliki banyak catatan transaksi (has-a), bukan merupakan jenis dari transaksi.
  *   Overriding & Polymorphism: Method hitungDampakSaldo() di-override agar menghasilkan nilai positif pada TransaksiMasuk dan negatif pada TransaksiKeluar. Hal ini memungkinkan BukuKas menerapkan polymorphism saat menjumlahkan saldo dan mencetak riwayat transaksi melalui perulangan foreach secara bersih tanpa memerlukan percabangan if/else. 


## Requirements
Pastika pengguna mempunyai perangkat lunak berikut:
1.  **XAMPP:** Sebagai *local web server* dan menyediakan *environment* PHP (PHP 8.x direkomendasikan).
2.  **Visual Studio Code (VS Code):** Sebagai *code editor* untuk menulis dan menjalankan kode.

## Setup

1. **Install dan Buka XAMPP:**
   * Buka aplikasi **XAMPP Control Panel**.
   * Klik tombol **Start** pada modul **Apache** dan **MySQL** hingga warnanya berubah menjadi hijau.
2. **Siapkan Folder Proyek:**
   * Buka folder instalasi XAMPP Anda (biasanya di `C:\xampp\htdocs\`).
   * Buat folder baru di dalam `htdocs`, misalnya beri nama `modifier`.
3. **Simpan Kode:**
   * Buka **VS Code**, lalu buka folder yang baru saja dibuat (*File* -> *Open Folder*).
   * Buat file PHP baru dan *paste* seluruh kode program ke dalam file tersebut.

## Usage
1. Buka web browser Anda (Google Chrome, Firefox, dll).
2. Ketik URL berikut pada *address bar*:
   `http://localhost/modifier/20251020006_Abraham_Banyu_Pradipta_PBO_M5.php`
3. Tekan *Enter*, browser akan langsung mengeksekusi sistem OOP dan menampilkan status anggota kelas serta rekapitulasi buku kas secara utuh.


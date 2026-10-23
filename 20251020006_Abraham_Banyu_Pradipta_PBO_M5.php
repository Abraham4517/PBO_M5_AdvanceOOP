<?php
namespace TugasRumahPBO5; 

use InvalidArgumentException;
use Exception;


interface BisaDicatat {
    public function getRincianTransaksi(): string;
    public function hitungDampakSaldo(): int;
}

class Siswa { // protected agar tetap aman dan bisa diwarisi subclass
    protected string $nama_siswa;
    protected string $nis;
    protected int $jumlah_tunggakan;

    public function __construct(string $nama_siswa, string $nis, int $jumlah_tunggakan) {
        if (trim($nama_siswa) === "") {
            throw new InvalidArgumentException("Nama siswa tidak boleh kosong.");
        }
        if ($jumlah_tunggakan < 0) {
            throw new InvalidArgumentException("Jumlah tunggakan awal tidak boleh negatif.");
        }
        $this->nama_siswa = $nama_siswa;
        $this->nis = $nis;
        $this->jumlah_tunggakan = $jumlah_tunggakan;
    }

    public function getNamaSiswa(): string {
        return $this->nama_siswa;
    }

    public function getNis(): string {
        return $this->nis;
    }

    public function getJumlahTunggakan(): int {
        return $this->jumlah_tunggakan;
    }

    public function cek_tunggakan(): void {
        echo "[Siswa Reguler] {$this->nama_siswa} (NIS: {$this->nis}) - Sisa Tunggakan: Rp" . number_format($this->jumlah_tunggakan, 0, ',', '.') . "<br>";
    }

    public function bayar_kas(int $nominal): void {
        if ($nominal <= 0) {
            throw new InvalidArgumentException("Nominal pembayaran harus lebih dari Rp0.");
        }

        echo "{$this->nama_siswa} membayar kas sebesar Rp" . number_format($nominal, 0, ',', '.') . "<br>";
        $this->jumlah_tunggakan -= $nominal;

        if ($this->jumlah_tunggakan < 0) {
            $this->jumlah_tunggakan = 0;
        }
    }
}

class Bendahara extends Siswa {
    private string $periodeJabatan;

    public function __construct(string $nama_siswa, string $nis, int $jumlah_tunggakan, string $periodeJabatan) {
        parent::__construct($nama_siswa, $nis, $jumlah_tunggakan);
        $this->periodeJabatan = $periodeJabatan;
    }

    public function cek_tunggakan(): void {
        echo "[Bendahara Kelas - Periode {$this->periodeJabatan}] {$this->nama_siswa} (NIS: {$this->nis}) - Sisa Tunggakan: Rp" . number_format($this->jumlah_tunggakan, 0, ',', '.') . "<br>";
    }
}

abstract class TransaksiKas implements BisaDicatat {
    protected string $idTransaksi;
    protected string $tanggal;
    protected int $nominal;

    public function __construct(string $idTransaksi, string $tanggal, int $nominal) {
        if ($nominal <= 0) {
            throw new InvalidArgumentException("Nominal transaksi harus lebih dari Rp0.");
        }
        $this->idTransaksi = $idTransaksi;
        $this->tanggal = $tanggal;
        $this->nominal = $nominal;
    }

    abstract public function hitungDampakSaldo(): int;
    abstract public function getRincianTransaksi(): string;
}

class TransaksiMasuk extends TransaksiKas {
    private Siswa $siswaPembayar; // Relasi ke objek Siswa

    public function __construct(string $idTransaksi, string $tanggal, int $nominal, Siswa $siswaPembayar) {
        parent::__construct($idTransaksi, $tanggal, $nominal);
        $this->siswaPembayar = $siswaPembayar; 
        $this->siswaPembayar->bayar_kas($nominal); // Otomatis menjalankan method bayar_kas pada objek Siswa
    }

    public function hitungDampakSaldo(): int {
        return $this->nominal;
    }

    public function getRincianTransaksi(): string {
        return "[{$this->idTransaksi} | {$this->tanggal}] KAS MASUK dari {$this->siswaPembayar->getNamaSiswa()}: +Rp" . number_format($this->nominal, 0, ',', '.');
    }
}

class TransaksiKeluar extends TransaksiKas {
    private string $keperluan;

    public function __construct(string $idTransaksi, string $tanggal, int $nominal, string $keperluan) {
        parent::__construct($idTransaksi, $tanggal, $nominal);
        $this->keperluan = $keperluan;
    }

    public function hitungDampakSaldo(): int {
        return -$this->nominal;
    }

    public function getRincianTransaksi(): string {
        return "[{$this->idTransaksi} | {$this->tanggal}] KAS KELUAR untuk {$this->keperluan}: -Rp" . number_format($this->nominal, 0, ',', '.');
    }
}

class BukuKas {
    private string $periodeBulan;
    private array $daftarTransaksi = []; // Menyimpan objek-objek BisaDicatat dan TransaksiKas

    public function __construct(string $periodeBulan) {
        $this->periodeBulan = $periodeBulan;
    }

    public function catatTransaksi(BisaDicatat $transaksi): void {
        $this->daftarTransaksi[] = $transaksi;
    }

    public function hitungSaldoAkhir(): int {
        $saldo = 0;
        foreach ($this->daftarTransaksi as $transaksi) {
            $saldo += $transaksi->hitungDampakSaldo();
        }
        return $saldo;
    }

    public function tampilkanRekap(): void {
        echo "<b>=== Rekap Buku Kas Kelas (Periode: {$this->periodeBulan}) ===</b><br>";
        echo "Jumlah Transaksi Tercatat: " . count($this->daftarTransaksi) . " transaksi<br>";
        echo "<b>Riwayat Aliran Dana:</b><br>";

        foreach ($this->daftarTransaksi as $transaksi) {
            echo "- " . $transaksi->getRincianTransaksi() . "<br>";
        }

        echo "<b>Total Saldo Akhir Kas Kelas: Rp" . number_format($this->hitungSaldoAkhir(), 0, ',', '.') . "</b><br>";
    }
}

function tampilkanStatusAnggota(Siswa $anggota): void {
    $anggota->cek_tunggakan();
}

// Eksekusi Program
try {
    echo "<b>=== 1. Status Awal Anggota Kelas ===</b><br>";
    $siswa1 = new Siswa("Abraham Banyu Pradipta", "20251020006", 50000);
    $siswa2 = new Siswa("Budi Santoso", "20251020007", 20000);
    $bendahara = new Bendahara("Siti Aminah", "20251020008", 10000, "2026/2027");

    $daftarAnggota = [$siswa1, $siswa2, $bendahara];
    foreach ($daftarAnggota as $anggota) {
        tampilkanStatusAnggota($anggota);
    }
    echo "<br>";

    echo "<b>=== 2. Proses Pencatatan Transaksi Kas ===</b><br>";
    $bukuKasOktober = new BukuKas("Oktober 2026");

    $trx1 = new TransaksiMasuk("TRX-01", "05-10-2026", 30000, $siswa1);
    $trx2 = new TransaksiMasuk("TRX-02", "06-10-2026", 20000, $siswa2);
    $trx3 = new TransaksiMasuk("TRX-03", "07-10-2026", 10000, $bendahara);

    $trx4 = new TransaksiKeluar("TRX-04", "10-10-2026", 15000, "Pembelian Spidol & Penghapus Papan Tulis");

    $bukuKasOktober->catatTransaksi($trx1);
    $bukuKasOktober->catatTransaksi($trx2);
    $bukuKasOktober->catatTransaksi($trx3);
    $bukuKasOktober->catatTransaksi($trx4);
    echo "<br>";

    echo "<b>=== 3. Status Tunggakan Setelah Pembayaran ===</b><br>";
    foreach ($daftarAnggota as $anggota) {
        tampilkanStatusAnggota($anggota);
    }
    echo "<br>";

    $bukuKasOktober->tampilkanRekap();

} catch (Exception $e) {
    echo "<span style='color:red;'><b>ERROR:</b> " . $e->getMessage() . "</span><br>";
}
?>
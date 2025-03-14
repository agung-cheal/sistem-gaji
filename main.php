<?php

//File model gaji.php untuk menyimpan data karyawan
define('GAJI_FILE', __DIR__ . '/model/gaji.php');

//Pastikan folder model ada
if (!file_exists(__DIR__ . '/model')) {
    mkdir(__DIR__. '/model', 0777, true);
}

//Fungsi untuk membaca data karyawan dari file
function bacaKaryawan() {
    if (!file_exists(GAJI_FILE)) {
        return [];
    }
    $data = include GAJI_FILE;
    return is_array($data) ? $data : [];
}

//Fungsi untuk menyimpan data karyawan ke file
function SimpanKaryawan($data) {
    file_put_contents(GAJI_FILE, '<?php return ' . var_export($data, true) . ';');
}

//Fungsi menampilkan daftar karyawan
function lihatKaryawan() {
    $karyawan = bacaKaryawan();
    if (empty($karyawan)) {
        echo "Belum ada data karyawan.\n";
        return;
    }
    echo "\nDaftar Karyawan:\n";
    echo "============= Daftar Karyawan =============\n";
    foreach ($karyawan as $index => $k) {
        echo "[$index] Nama: {$k['nama']}, Jabatan: {$k['jabatan']}\n";
    }
    echo "=============================================\n";
}

// Fungsi menambahkan karyawan
function tambahKaryawan() {
    $jabatanTersedia = ['Manager', 'Supervisor', 'Staff', 'Marketing'];
    $karyawan = bacaKaryawan();
    echo "Masukkan nama karyawan: ";
    $nama = trim(fgets(STDIN));
    echo "Masukkan jabatan karyawan (Manager/Supervisor/Staff/Marketing): ";
    $jabatan = trim(fgets(STDIN));
    if (!in_array($jabatan, $jabatanTersedia)) {
        echo "Jabatan tidak valid! Hanya boleh Manager, Supervisor, Staff, atau Marketing.\n";
        return;
    }
    $karyawan[] = ['nama' => $nama, 'jabatan' => $jabatan ];
    simpanKaryawan($karyawan);
    echo "Karyawan berhasil ditambahkan.\n";
}

//Fungsi memperbarui data karyawan
function updateKaryawan() {
    $jabatanTersedia = ['Manager', 'Supervisor', 'Staff', 'Marketing'];
    $karyawan = bacaKaryawan();
    lihatKaryawan();
    echo "Masukkan nomor karyawan yang akan diupdate: ";
    $index = (int)trim(fgets(STDIN));
    if (!isset($karyawan[$index])) {
        echo "Karyawan tidak ditemukan!\n";
        return;
    }
    echo "Masukkan nama baru: ";
    $nama = trim(fgets(STDIN));
    echo "Masukkan jabatan baru (Manager/Supervisor/Staff/Marketing): ";
    $jabatan = trim(fgets(STDIN));
    if (!in_array($jabatan, $jabatanTersedia)) {
        echo "Jabatan tidak valid!\n";
        return;
    }
    $karyawan[$index] = ['nama' => $nama, 'jabatan' => $jabatan];
    simpanKaryawan($karyawan);
    echo "Data karyawan berhasil diperbarui!\n";
}

//Fungsi menghapus karyawan
function hapusKaryawan() {
    $karyawan = bacaKaryawan();
    lihatKaryawan();
    echo "Masukkan nomor karyawan yang akan dihapus: ";
    $index = (int)trim(fgets(STDIN));
    if (!isset($karyawan[$index])) {
        echo "Karyawan tidak ditemukan!\n";
        return;
    }
    echo "Apakah Anda yakin ingin menghapus {$karyawan[$index]['nama']}? (y/n): ";
    $konfirmasi = trim(fgets(STDIN));
    if (strtolower($konfirmasi) !== 'y') {
        echo "Penghapusan dibatalkan.\n";
        return;
    }
    unset($karyawan[$index]);
    simpanKaryawan(array_values($karyawan));
    echo "Karyawan berhasil dihapus!\n";
}


//Fungsi menghitung gaji karyawan berdasarkan jabatan dan lembur
function hitunggaji() {
    $gajiJabatan = [
        'Manager' => 10000000,
        'Supervisor' => 8000000,
        'Staff' => 5000000
    ];
    $karyawan = bacaKaryawan();
    lihatKaryawan();
    echo "Masukkan nomor karyawan yang akan dihitung gajinya: ";
    $index = (int)trim(fgets(STDIN));
    if (!isset($karyawan[$index])) {
        echo "Karyawan tidak ditemukan!\n";
        return;
    }
    echo "Masukkan jumlah jam lembur: ";
    $jamLembur = (int)trim(fgets(STDIN));
    $jabatan = $karyawan[$index]['jabatan'];
    $gajiPokok = $gajiJabatan[$jabatan] ?? 4000000; //Default gaji jika todak terdaftar
    $gajiLembur = $jamLembur * 50000; //Tarif lembur per jam
    $totalGaji = $gajiPokok + $gajiLembur;
    echo "Gaji {$karyawan[$index]['nama']} ({$jabatan}) adalah Rp. " . number_format($totalGaji, 0, ',', '.' ) . "\n";
}

// Menu utama
while (true) {
    echo "\nSistem Manajemen Gaji Karyawan\n";
    echo "1. Lihat Karyawan\n";
    echo "2. Tambah Karyawan\n";
    echo "3. Update karyawam\n";
    echo "4. Hapus Karyawan\n";
    echo "5. Hitung Gaji Karyawan\n";
    echo "6. Keluar aplikasi\n";
    echo "Pilih aksi (1-6): ";
    $pilihan = trim(fgets(STDIN));
    switch ($pilihan) {
        case '1':
            lihatKaryawan();
            break;
        case '2':
            tambahKaryawan();
            break;
        case '3':
            updateKaryawan();
            break;
        case '4':
            hapusKaryawan();
            break;
        case '5':
            hitungGaji();
            break;
        case '6':
            exit("Keluar dari aplikasi. Thank You\n");
        default:
            echo "Pilihan tidak valid! Silahkan masukkan nomor aksi yang tersedia.\n ";

    }
}

?>
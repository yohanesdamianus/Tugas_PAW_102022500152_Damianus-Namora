<?php
// 1. KONEKSI KE DATABASE MYSQL
$host = "localhost";
$user = "root";       // Username bawaan XAMPP
$pass = "";           // Password bawaan XAMPP kosong
$db   = "klinik_db";  // Nama database yang Anda buat di phpMyAdmin

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

// 2. DEKLARASI VARIABEL AWAL
$rekam_medis = "";
$nama_pasien = "";
$domisili = "";
$kontak_darurat = "";
$pesan = "";

// 3. PROSES SIMPAN DATA KETIKA TOMBOL SUBMIT DITEKAN
if (isset($_POST['submit'])) {
    // Menangkap data dari form
    $rekam_medis = $_POST['rekam_medis'];
    $nama_pasien = $_POST['nama_pasien'];
    $domisili = $_POST['domisili'];
    $kontak_darurat = $_POST['kontak_darurat'];

    // Query untuk memasukkan data ke tabel pasien
    $sql = "INSERT INTO pasien (rekam_medis, nama_pasien, domisili, kontak_darurat) 
            VALUES ('$rekam_medis', '$nama_pasien', '$domisili', '$kontak_darurat')";

    if ($conn->query($sql) === TRUE) {
        $pesan = "<p style='color: green; font-weight: bold;'>Data pasien berhasil disimpan ke database!</p>";
        // Mengosongkan form kembali setelah berhasil disimpan
        $rekam_medis = "";
        $nama_pasien = "";
        $domisili = "";
        $kontak_darurat = "";
    } else {
        $pesan = "<p style='color: red;'>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Pasien - Klinik Rawat Jalan</title>
    <link rel="stylesheet" href="CSS/cssRS.css">   
</head>
<body>
    
    <div class="header">
        <h1>
            <img src="gambar/rumahSakit.jpeg" alt="Logo" height="60px" align="middle" class="logo">
            Sistem Informasi Klinik Rawat Jalan
        </h1> 
    </div>

    <div class="nav">
        <ul>
            <li><a href="beranda.php">Beranda</a></li>
            <li><a href="manajemen_pasien.php">Manajemen Pasien</a></li>
            <li><a href="data_dokter.html">Data Dokter</a></li>
            <li><a href="farmasi.html">Katalog Farmasi</a></li>
        </ul>
    </div>
    
    <h2>Formulir Data Pasien</h2>
    
    <!-- Menampilkan pesan sukses/gagal -->
    <?php echo $pesan; ?>

    <form method="POST" action="">
        <label for="rekam_medis">Nomor Rekam Medis Dasar:</label>
        <input type="text" id="rekam_medis" name="rekam_medis" placeholder="Contoh: RM-001" value="<?php echo $rekam_medis; ?>" required>
        
        <label for="nama_pasien">Profil Pasien (Nama Lengkap):</label>
        <input type="text" id="nama_pasien" name="nama_pasien" placeholder="Masukkan nama lengkap pasien" value="<?php echo $nama_pasien; ?>" required>
        
        <label for="domisili">Domisili:</label>
        <textarea name="domisili" id="domisili" cols="50" rows="3" placeholder="Masukkan alamat lengkap domisili" required><?php echo $domisili; ?></textarea>
        
        <label for="kontak_darurat">Kontak Darurat:</label>
        <input type="text" id="kontak_darurat" name="kontak_darurat" placeholder="Nomor Telepon/HP Darurat" value="<?php echo $kontak_darurat; ?>" required>
        
        <input type="submit" name="submit" value="Simpan Data">
    </form>

    <!-- Bagian Tabel Data -->
    <h2>Direktori Pasien</h2>
    <table>
        <thead>
            <tr>
                <th>No. Rekam Medis</th>
                <th>Profil Pasien</th>
                <th>Domisili</th>
                <th>Kontak Darurat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>RM-001</td>
                <td>Andi Susanto</td>
                <td>Jl. Telekomunikasi, Bandung</td>
                <td>081122334455</td>
                <td>
                    <button class="edit">Edit</button> 
                    <button class="delete">Hapus</button>
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html> 
<?php
// 1. KONEKSI KE DATABASE MYSQL
$host = "localhost";
$user = "root";       // Username bawaan XAMPP
$pass = "";           // Password bawaan XAMPP kosong
$db   = "klinik_db";  // Nama database

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
$is_edit = false; // Penanda apakah sedang dalam  mode edit
$id_lama = "";    // Menyimpan ID lama jika rekam medis diubah

// ==========================================
// [DELETE] PROSES HAPUS DATA
// ==========================================
if (isset($_GET['hapus'])) {
    $id_hapus = $_GET['hapus'];
    $sql_hapus = "DELETE FROM pasien WHERE rekam_medis = '$id_hapus'";
    if ($conn->query($sql_hapus) === TRUE) {
        $pesan = "<p style='color: green; font-weight: bold;'>Data pasien berhasil dihapus!</p>";
    } else {
        $pesan = "<p style='color: red;'>Gagal menghapus: " . $conn->error . "</p>";
    }
}

// ==========================================
// [UPDATE - TAHAP 1] AMBIL DATA UNTUK DIEDIT
// ==========================================
if (isset($_GET['edit'])) {
    $is_edit = true;
    $id_lama = $_GET['edit'];
    $sql_edit = "SELECT * FROM pasien WHERE rekam_medis = '$id_lama'";
    $result_edit = $conn->query($sql_edit);
    
    if ($result_edit->num_rows > 0) {
        $row = $result_edit->fetch_assoc();
        $rekam_medis = $row['rekam_medis'];
        $nama_pasien = $row['nama_pasien'];
        $domisili = $row['domisili'];
        $kontak_darurat = $row['kontak_darurat'];
    }
}

// ==========================================
// [CREATE & UPDATE] PROSES SIMPAN / EDIT DATA
// ==========================================
if (isset($_POST['submit'])) {
    $rekam_medis = $_POST['rekam_medis'];
    $nama_pasien = $_POST['nama_pasien'];
    $domisili = $_POST['domisili'];
    $kontak_darurat = $_POST['kontak_darurat'];
    
    if (isset($_POST['is_edit']) && $_POST['is_edit'] == '1') {
        // [UPDATE - TAHAP 2] Update data yang ada
        $id_lama_post = $_POST['id_lama'];
        $sql = "UPDATE pasien SET 
                rekam_medis = '$rekam_medis', 
                nama_pasien = '$nama_pasien', 
                domisili = '$domisili', 
                kontak_darurat = '$kontak_darurat' 
                WHERE rekam_medis = '$id_lama_post'";
                
        if ($conn->query($sql) === TRUE) {
            $pesan = "<p style='color: green; font-weight: bold;'>Data pasien berhasil diperbarui!</p>";
            // Kosongkan form setelah update
            $rekam_medis = ""; $nama_pasien = ""; $domisili = ""; $kontak_darurat = ""; $is_edit = false;
        } else {
            $pesan = "<p style='color: red;'>Error Update: " . $conn->error . "</p>";
        }
    } else {
        // [CREATE] Masukkan data baru
        $sql = "INSERT INTO pasien (rekam_medis, nama_pasien, domisili, kontak_darurat) 
                VALUES ('$rekam_medis', '$nama_pasien', '$domisili', '$kontak_darurat')";
                
        if ($conn->query($sql) === TRUE) {
            $pesan = "<p style='color: green; font-weight: bold;'>Data pasien berhasil disimpan ke database!</p>";
            // Kosongkan form setelah simpan
            $rekam_medis = ""; $nama_pasien = ""; $domisili = ""; $kontak_darurat = "";
        } else {
            $pesan = "<p style='color: red;'>Error Insert: " . $conn->error . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Pasien - Klinik Rawat Jalan</title>
    <link rel="stylesheet" href="CSS/cssRS.css">   
    <style>
        /* Tambahan CSS seperlunya agar tabel rapi */
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn-edit { background-color: #4CAF50; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; }
        .btn-delete { background-color: #f44336; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; }
    </style>
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
            <li><a href="dokter.html">Data Dokter</a></li>
            <li><a href="farmasi.html">Katalog Farmasi</a></li>
            <li><a href="poli_klinik.php">Ruang Poliklinik</a></li>
        </ul>
    </div>
    
    
    <h2><?php echo $is_edit ? "Form Edit Data Pasien" : "Formulir Tambah Data Pasien"; ?></h2>
    
    <!-- Menampilkan pesan sukses/gagal -->
    <?php echo $pesan; ?>

    <form method="POST" action="manajemen_pasien.php">
        <!-- Input tersembunyi untuk penanda mode edit -->
        <?php if($is_edit): ?>
            <input type="hidden" name="is_edit" value="1">
            <input type="hidden" name="id_lama" value="<?php echo $id_lama; ?>">
        <?php endif; ?>

        <label for="rekam_medis">Nomor Rekam Medis Dasar:</label><br>
        <input type="text" id="rekam_medis" name="rekam_medis" placeholder="Contoh: RM-001" value="<?php echo $rekam_medis; ?>" required><br><br>
        
        <label for="nama_pasien">Profil Pasien (Nama Lengkap):</label><br>
        <input type="text" id="nama_pasien" name="nama_pasien" placeholder="Masukkan nama lengkap pasien" value="<?php echo $nama_pasien; ?>" required><br><br>
        
        <label for="domisili">Domisili:</label><br>
        <textarea name="domisili" id="domisili" cols="50" rows="3" placeholder="Masukkan alamat lengkap domisili" required><?php echo $domisili; ?></textarea><br><br>
        
        <label for="kontak_darurat">Kontak Darurat:</label><br>
        <input type="text" id="kontak_darurat" name="kontak_darurat" placeholder="Nomor Telepon/HP Darurat" value="<?php echo $kontak_darurat; ?>" required><br><br>
        
        <input type="submit" name="submit" value="<?php echo $is_edit ? "Update Data" : "Simpan Data"; ?>">
        <?php if($is_edit): ?>
            <a href="manajemen_pasien.php"><button type="button">Batal Edit</button></a>
        <?php endif; ?>
    </form>

    <hr>

    <!-- [READ] Bagian Tabel Data Dinamis -->
    <h2>Direktori Pasien (Data dari Database)</h2>
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
            <?php
            // Mengambil data dari tabel pasien
            $sql_tampil = "SELECT * FROM pasien ORDER BY rekam_medis ASC";
            $result = $conn->query($sql_tampil);

            if ($result->num_rows > 0) {
                // Looping data dari database ke dalam baris tabel
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['rekam_medis'] . "</td>";
                    echo "<td>" . $row['nama_pasien'] . "</td>";
                    echo "<td>" . $row['domisili'] . "</td>";
                    echo "<td>" . $row['kontak_darurat'] . "</td>";
                    echo "<td>
                            <a href='manajemen_pasien.php?edit=" . $row['rekam_medis'] . "' class='btn-edit'>Edit</a> 
                            <a href='manajemen_pasien.php?hapus=" . $row['rekam_medis'] . "' class='btn-delete' onclick=\"return confirm('Apakah Anda yakin ingin menghapus data ini?');\">Hapus</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                // Jika tabel kosong
                echo "<tr><td colspan='5' style='text-align: center;'>Tidak ada data pasien ditemukan.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
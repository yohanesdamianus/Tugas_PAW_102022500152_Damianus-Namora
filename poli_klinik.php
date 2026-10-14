<?php
// Koneksi ke Database
$host = "localhost";
$user = "root";       
$pass = "";           
$db   = "klinik_db";  

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengecekan Ruang Poliklinik - Klinik Rawat Jalan</title>
    <!-- Pastikan link CSS sesuai dengan folder Anda -->
    <link rel="stylesheet" href="CSS/cssRS.css">   
    <style>
        .container { padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
        .badge { background-color: #e7f3fe; color: #31708f; padding: 5px 10px; border-radius: 5px; font-size: 14px;}
    </style>
</head>
<body>
    
    <div class="header">
        <h1>
            <img src="gambar/rumahSakit.jpeg" alt="Logo" height="60px" align="middle" class="logo">
            Sistem Informasi Klinik Rawat Jalan
        </h1> 
    </div>

    <!-- Menu navigasi yang sudah ditambahkan Ruang Poliklinik -->
    <div class="nav">
        <ul>
            <li><a href="beranda.php">Beranda</a></li>
            <li><a href="manajemen_pasien.php">Manajemen Pasien</a></li>
            <li><a href="dokter.html">Data Dokter</a></li>
            <li><a href="farmasi.html">Katalog Farmasi</a></li>
            <li><a href="poli_klinik.php">Ruang Poliklinik</a></li>
        </ul>
    </div>
    
    <div class="container">
        <h2>Katalog & Pengecekan Ruang Poliklinik</h2>
        <p>Berikut adalah data master ruangan poli, lokasi gedung, dan inventaris alat medis yang tersedia.</p>

        <table>
            <thead>
                <tr>
                    <th>Kode Ruangan</th>
                    <th>Nama Poli</th>
                    <th>Lokasi Gedung / Lantai</th>
                    <th>Inventaris Alat Medis</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Query untuk mengambil data ruangan
                $sql_tampil = "SELECT * FROM ruangan_poli ORDER BY kode_ruangan ASC";
                $result = $conn->query($sql_tampil);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td><strong>" . $row['kode_ruangan'] . "</strong></td>";
                        echo "<td><span class='badge'>" . $row['nama_poli'] . "</span></td>";
                        echo "<td>" . $row['lokasi_gedung'] . "</td>";
                        echo "<td>" . $row['inventaris_alat'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' style='text-align: center;'>Tidak ada data ruangan poliklinik ditemukan di database.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>
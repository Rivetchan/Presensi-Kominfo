<?php
session_start();
include'../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $role = $_POST['role'];
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $queryPembimbing = "SELECT MAX(PembimbingID) AS lastPembimbingID FROM pembimbing";
    $resultPembimbing = $kon->query($queryPembimbing);
    $rowPembimbing = $resultPembimbing->fetch_assoc();
    $lastPembimbingID = $rowPembimbing['lastPembimbingID'] + 1; // ID baru untuk pembimbing

    $querySiswa = "SELECT MAX(SiswaID) AS lastSiswaID FROM siswa";
    $resultSiswa = $kon->query($querySiswa);
    $rowSiswa = $resultSiswa->fetch_assoc();
    $lastSiswaID = $rowSiswa['lastSiswaID'] + 1; 

    if ($role === 'Pembimbing') {
        $query = "INSERT INTO pembimbing (PembimbingID, NamaPembimbing, Username, Password) VALUES (?, ?, ?, ?)";
        $stmt = $kon->prepare($query);
        $stmt->bind_param("isss", $lastPembimbingID, $nama, $username, $password);
        $stmt->execute();
        $stmt->close();
        echo "<script>alert('Pendaftaran Pembimbing berhasil!'); window.location.href='data_pendaftaran.php';</script>";
    } elseif ($role === 'Siswa') {
        $query = "INSERT INTO siswa (SiswaID, NamaSiswa, Username, Password) VALUES (?, ?, ?, ?)";
        $stmt = $kon->prepare($query);
        $stmt->bind_param("isss", $lastSiswaID, $nama, $username, $password);
        $stmt->execute();
        $stmt->close();
        echo "<script>alert('Pendaftaran Siswa berhasil!'); window.location.href='data_pendaftaran.php';</script>";
    } else {
        echo "<script>alert('Role tidak valid!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pendaftaran</title>
    <link rel="stylesheet" href="css/form.css">
</head>
<body>
    <div class="container">

        <a href="data_pendaftaran.php" class="btn-back-inline">Back</a>

        <h2>Form Pendaftaran</h2>

        <form action="form_pendaftaran.php" method="POST">
            <label for="role">Pilih Role:</label><br>
            <select name="role" id="role" required>
                <option value="Pembimbing">Pembimbing</option>
                <option value="Siswa">Siswa</option>
            </select><br><br>

            <label for="nama">Nama Panjang :</label><br>
            <input type="text" id="nama" name="nama" required><br><br>

            <label for="username">Username :</label><br>
            <input type="text" id="username" name="username" required><br><br>

            <label for="password">Password :</label><br>
            <input type="password" id="password" name="password" required><br><br>

            <button type="submit">Daftar</button>
        </form>
    </div>
</body>
</html>
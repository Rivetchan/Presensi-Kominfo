<?php
session_start();
require_once('../../config/koneksi.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['fotoSiswa']) && isset($_GET['SiswaID'])) {
    $siswaID = $_GET['SiswaID'];
    $foto = $_FILES['fotoSiswa'];

    $query = "SELECT FotoSiswa FROM siswa WHERE SiswaID = ?";
    $stmt = $kon->prepare($query);
    $stmt->bind_param("i", $siswaID);
    $stmt->execute();
    $result = $stmt->get_result();
    $siswa = $result->fetch_assoc();

    $oldFoto = $siswa['FotoSiswa'];
    $uploadDir = 'upload/';

    if ($oldFoto && file_exists($oldFoto)) {
        unlink($oldFoto);
    }

    if ($foto['error'] === UPLOAD_ERR_OK) {
        $uploadFile = $uploadDir . basename($foto['name']);

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($foto['tmp_name'], $uploadFile)) {
            $query = "UPDATE siswa SET FotoSiswa = ? WHERE SiswaID = ?";
            $stmt = $kon->prepare($query);
            $stmt->bind_param('si', $uploadFile, $siswaID);
            $stmt->execute();

            header("Location: profile.php?SiswaID=$siswaID");
            exit();
        }
    }
}
header("Location: ../../404.php");
exit();
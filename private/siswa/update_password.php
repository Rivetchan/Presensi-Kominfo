<?php
session_start();
require_once('../../config/koneksi.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['SiswaID'])) {
    $siswaID = $_GET['SiswaID'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    if ($newPassword === $confirmPassword) {
        $updateQuery = "UPDATE siswa SET Password = ? WHERE SiswaID = ?";
        $updateStmt = $kon->prepare($updateQuery);
        $updateStmt->bind_param('si', $newPassword, $siswaID);
        $updateStmt->execute();

        header("Location: profile.php?SiswaID=$siswaID");
        exit();
    } else {
        echo "Password baru dan konfirmasi tidak sama!";
    }
}
?>
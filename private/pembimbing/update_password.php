<?php
session_start();
require_once('../../config/koneksi.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['PembimbingID'])) {
    $pembimbingID = $_GET['PembimbingID'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    if ($newPassword === $confirmPassword) {
        $updateQuery = "UPDATE pembimbing SET Password = ? WHERE PembimbingID = ?";
        $updateStmt = $kon->prepare($updateQuery);
        $updateStmt->bind_param('si', $newPassword, $pembimbingID);
        $updateStmt->execute();

        header("Location: profile.php?PembimbingID=$pembimbingID");
        exit();
    } else {
        echo "Password baru dan konfirmasi tidak sama!";
    }
}
?>
<?php
session_start();
require_once('../../config/koneksi.php');

if (!isset($_GET['PembimbingID'])) {
    header("Location: ../../404.php");
    exit();
}

$pembimbingID = $_GET['PembimbingID'];

$query = "SELECT Username, NamaPembimbing, NoHP FROM pembimbing WHERE PembimbingID = ?";
$stmt = $kon->prepare($query);
$stmt->bind_param("i", $pembimbingID);
$stmt->execute();
$result = $stmt->get_result();
$pembimbing = $result->fetch_assoc();

if (!$pembimbing) {
    header("Location: ../../404.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['Username'];
    $namaPembimbing = $_POST['NamaPembimbing'];
    $noHP = $_POST['NoHP'];

    if (empty($username) || empty($namaPembimbing) || empty($noHP)) {
        die("Semua kolom harus diisi!");
    }

    $queryUpdate = "UPDATE pembimbing SET Username = ?, NamaPembimbing = ?, NoHP = ? WHERE PembimbingID = ?";
    $stmtUpdate = $kon->prepare($queryUpdate);
    $stmtUpdate->bind_param("sssi", $username, $namaPembimbing, $noHP, $pembimbingID);

    if ($stmtUpdate->execute()) {
        header("Location: profile.php?PembimbingID=" . $pembimbingID);
        exit();
    } else {
        die("Terjadi kesalahan saat memperbarui data: " . $stmtUpdate->error);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil Pembimbing</title>
    <link rel="stylesheet" href="css/edit.css">
</head>
<body>
    <div class="profile-container">
        <a href="profile.php?PembimbingID=<?php echo $pembimbingID; ?>" class="back-button">← Kembali</a>

        <h2>Edit Profil Pembimbing</h2>

        <form action="edit.php?PembimbingID=<?php echo $pembimbingID; ?>" method="post" class="edit-form">
            <div class="edit-right">
                <label for="Username">Username</label>
                <input type="text" name="Username" id="Username" value="<?php echo htmlspecialchars($pembimbing['Username']); ?>" required>

                <label for="NamaPembimbing">Nama Pembimbing</label>
                <input type="text" name="NamaPembimbing" id="NamaPembimbing" value="<?php echo htmlspecialchars($pembimbing['NamaPembimbing']); ?>" required>

                <label for="NoHP">No HP</label>
                <input type="text" name="NoHP" id="NoHP" value="<?php echo htmlspecialchars($pembimbing['NoHP']); ?>" required>

                <button type="submit" class="btn-submit">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</body>
</html>
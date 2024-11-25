<?php
session_start();
require_once('../../config/koneksi.php');

if (!isset($_GET['SiswaID'])) {
    header("Location: ../../404.php");
    exit();
}

$siswaID = $_GET['SiswaID'];

$query = "SELECT Username, NamaSiswa, AsalSekolah, WaktuPKL, NoHP FROM siswa WHERE SiswaID = ?";
$stmt = $kon->prepare($query);
$stmt->bind_param("i", $siswaID);
$stmt->execute();
$result = $stmt->get_result();
$siswa = $result->fetch_assoc();

if (!$siswa) {
    header("Location: ../../404.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['Username'];
    $namaSiswa = $_POST['NamaSiswa'];
    $asalSekolah = $_POST['AsalSekolah'];
    $waktuPKL = $_POST['WaktuPKL'];
    $noHP = $_POST['NoHP'];

    if (empty($username) || empty($namaSiswa) || empty($asalSekolah) || empty($waktuPKL) || empty($noHP)) {
        die("Semua kolom harus diisi!");
    }

    $queryUpdate = "UPDATE siswa SET Username = ?, NamaSiswa = ?, AsalSekolah = ?, WaktuPKL = ?, NoHP = ? WHERE SiswaID = ?";
    $stmtUpdate = $kon->prepare($queryUpdate);
    $stmtUpdate->bind_param("sssssi", $username, $namaSiswa, $asalSekolah, $waktuPKL, $noHP, $siswaID);

    if ($stmtUpdate->execute()) {
        header("Location: profile.php?SiswaID=" . $siswaID);
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
    <title>Edit Profil Siswa</title>
    <link rel="stylesheet" href="css/edit.css">
</head>
<body>
    <div class="profile-container">
        <a href="profile.php?SiswaID=<?php echo $siswaID; ?>" class="back-button">← Kembali</a>

        <h2>Edit Profil Siswa</h2>

        <form action="edit.php?SiswaID=<?php echo $siswaID; ?>" method="post" class="edit-form">
            <div class="edit-right">
                <label for="Username">Username</label>
                <input type="text" name="Username" id="Username" value="<?php echo htmlspecialchars($siswa['Username']); ?>" required>

                <label for="NamaSiswa">Nama Panjang</label>
                <input type="text" name="NamaSiswa" id="NamaSiswa" value="<?php echo htmlspecialchars($siswa['NamaSiswa']); ?>" required>

                <label for="AsalSekolah">Asal Sekolah</label>
                <input type="text" name="AsalSekolah" id="AsalSekolah" value="<?php echo htmlspecialchars($siswa['AsalSekolah']); ?>" required>

                <label for="WaktuPKL">Waktu PKL</label>
                <select name="WaktuPKL" id="WaktuPKL" required>
                    <option value="6 Bulan" <?php echo $siswa['WaktuPKL'] == '6 Bulan' ? 'selected' : ''; ?>>6 Bulan</option>
                    <option value="7 Bulan" <?php echo $siswa['WaktuPKL'] == '7 Bulan' ? 'selected' : ''; ?>>7 Bulan</option>
                    <option value="8 Bulan" <?php echo $siswa['WaktuPKL'] == '8 Bulan' ? 'selected' : ''; ?>>8 Bulan</option>
                    <option value="9 Bulan" <?php echo $siswa['WaktuPKL'] == '9 Bulan' ? 'selected' : ''; ?>>9 Bulan</option>
                    <option value="10 Bulan" <?php echo $siswa['WaktuPKL'] == '10 Bulan' ? 'selected' : ''; ?>>10 Bulan</option>
                    <option value="11 Bulan" <?php echo $siswa['WaktuPKL'] == '11 Bulan' ? 'selected' : ''; ?>>11 Bulan</option>
                    <option value="12 Bulan" <?php echo $siswa['WaktuPKL'] == '12 Bulan' ? 'selected' : ''; ?>>12 Bulan</option>
                </select>

                <label for="NoHP">No HP</label>
                <input type="text" name="NoHP" id="NoHP" value="<?php echo htmlspecialchars($siswa['NoHP']); ?>" required>

                <button type="submit" class="btn-submit">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</body>
</html>
<?php
session_start();
include '../../../config/koneksi.php';

if (isset($_GET['id']) && isset($_GET['type'])) {
    $id = $_GET['id'];  // ID yang diterima
    $type = $_GET['type'];

    if ($type == 'kabit') {
        $query = "SELECT KabitID, NamaKabit, Username, Password FROM kabit WHERE KabitID = ?";
    } else {
        $query = "SELECT SiswaID, NamaSiswa, Username, Password FROM siswa WHERE SiswaID = ?";
    }

    $stmt = $kon->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();

    if (!$data) {
        echo "<script>alert('Data tidak ditemukan!'); window.location.href='data_pendaftaran.php';</script>";
        exit();
    }
}

if (isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($type == 'kabit') {
        $updateQuery = "UPDATE kabit SET NamaKabit = ?, Username = ?, Password = ? WHERE KabitID = ?";
    } else {
        $updateQuery = "UPDATE siswa SET NamaSiswa = ?, Username = ?, Password = ? WHERE SiswaID = ?";
    }

    $stmt = $kon->prepare($updateQuery);
    $stmt->bind_param("sssi", $nama, $username, $password, $id);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Data berhasil diperbarui!'); window.location.href='../data_pendaftaran.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data</title>
    <link rel="stylesheet" href="../css/edit.css">
</head>
<body>
    <div class="container">
        <h2>Edit Data <?php echo ($type == 'kabit') ? 'Kabit' : 'Siswa'; ?></h2>

        <form action="edit.php?id=<?php echo $id; ?>&type=<?php echo $type; ?>" method="POST">
            <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" name="nama" id="nama" value="<?php echo htmlspecialchars($data[($type == 'kabit') ? 'NamaKabit' : 'NamaSiswa']); ?>" required>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($data['Username']); ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" value="<?php echo htmlspecialchars($data['Password']); ?>" required>
            </div>
            <div class="form-group">
                <button type="submit" name="submit" class="btn-daftar">Update</button>
                <a href="../data_pendaftaran.php" class="btn-back">Back</a>
            </div>
        </form>
    </div>
</body>
</html>
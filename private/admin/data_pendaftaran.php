<?php
session_start();
include '../../config/koneksi.php';

$queryPembimbing = "SELECT PembimbingID, NamaPembimbing, Username, Password FROM pembimbing";
$resultPembimbing = $kon->query($queryPembimbing);

$querySiswa = "SELECT SiswaID, NamaSiswa, Username, Password FROM siswa";
$resultSiswa = $kon->query($querySiswa);

if (isset($_GET['delete_pembimbing'])) {
    $pembimbingID = $_GET['delete_pembimbing'];
    $deleteQuery = "DELETE FROM pembimbing WHERE PembimbingID = ?";
    $stmt = $kon->prepare($deleteQuery);
    $stmt->bind_param("i", $pembimbingID);
    $stmt->execute();
    $stmt->close();
    echo "<script>alert('Data Pembimbing berhasil dihapus!'); window.location.href='data_pendaftaran.php';</script>";
}

if (isset($_GET['delete_siswa'])) {
    $siswaID = $_GET['delete_siswa'];
    $deleteQuery = "DELETE FROM siswa WHERE SiswaID = ?";
    $stmt = $kon->prepare($deleteQuery);
    $stmt->bind_param("i", $siswaID);
    $stmt->execute();
    $stmt->close();
    echo "<script>alert('Data Siswa berhasil dihapus!'); window.location.href='data_pendaftaran.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pendaftaran</title>
    <link rel="stylesheet" href="css/data.css">
</head>
<body>
    <div class="container">
        <h2>Data Pendaftaran</h2>

        <div class="button-container">
            <a href="../../index.php" class="btn-back">Back</a>
            <a href="form_pendaftaran.php" class="btn-daftar">Daftar</a>
        </div>

        <div class="tables-container">
            <div class="table-wrapper">
                <h3>Data Pembimbing</h3>
                <table border="1">
                    <thead>
                        <tr>
                            <th>PembimbingID</th>
                            <th>Nama Pembimbing</th>
                            <th>Username</th>
                            <th>Password</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($rowPembimbing = $resultPembimbing->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo $rowPembimbing['PembimbingID']; ?></td>
                                <td><?php echo $rowPembimbing['NamaPembimbing']; ?></td>
                                <td><?php echo $rowPembimbing['Username']; ?></td>
                                <td><?php echo $rowPembimbing['Password']; ?></td>
                                <td>
                                    <a href="controller/edit.php?id=<?php echo $rowPembimbing['PembimbingID']; ?>&type=pembimbing">Edit</a> | 
                                    <a href="?delete_pembimbing=<?php echo $rowPembimbing['PembimbingID']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <div class="table-wrapper">
                <h3>Data Siswa</h3>
                <table border="1">
                    <thead>
                        <tr>
                            <th>SiswaID</th>
                            <th>Nama Siswa</th>
                            <th>Username</th>
                            <th>Password</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($rowSiswa = $resultSiswa->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo $rowSiswa['SiswaID']; ?></td>
                                <td><?php echo $rowSiswa['NamaSiswa']; ?></td>
                                <td><?php echo $rowSiswa['Username']; ?></td>
                                <td><?php echo $rowSiswa['Password']; ?></td>
                                <td>
                                    <a href="controller/edit.php?id=<?php echo $rowSiswa['SiswaID']; ?>&type=siswa">Edit</a> | 
                                    <a href="?delete_siswa=<?php echo $rowSiswa['SiswaID']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
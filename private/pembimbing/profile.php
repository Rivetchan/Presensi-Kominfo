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

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Pembimbing</title>
    <link rel="stylesheet" href="css/profile.css">
</head>
<body>
    <div class="profile-container">
        <a href="../../index.php" class="back-button">← Back</a>

        <a href="javascript:void(0);" class="edit-password" onclick="openModal()">Edit Password</a>

        <div id="passwordModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <h2>Password Baru</h2>
                <form id="editPasswordForm" action="update_password.php?PembimbingID=<?php echo $pembimbingID; ?>" method="post">

                    <label for="newPassword">Password Baru</label>
                    <div class="password-input">
                        <input type="password" name="newPassword" id="newPassword" required>
                        <img src="css/logo/close.png" class="eye-icon" onclick="togglePasswordVisibility('newPassword')" alt="Eye Icon"> 
                    </div>

                    <label for="confirmPassword">Konfirmasi Password Baru</label>
                    <div class="password-input">
                        <input type="password" name="confirmPassword" id="confirmPassword" required>
                        <img src="css/logo/close.png" class="eye-icon" onclick="togglePasswordVisibility('confirmPassword')" alt="Eye Icon"> 
                    </div>

                    <button type="submit" class="btn-submit">Simpan</button>
                </form>
            </div>
        </div>

        <h2>Profile Pembimbing</h2>

        <div class="profile-details">
            <label>Username</label>
            <p><?php echo htmlspecialchars($pembimbing['Username']); ?></p>
            <label>Nama Pembimbing</label>
            <p><?php echo htmlspecialchars($pembimbing['NamaPembimbing']); ?></p>
            <label>No HP</label>
            <p><?php echo htmlspecialchars($pembimbing['NoHP']); ?></p>
        </div>
        <a href="../../logout.php" class="logout-button">Logout</a>
        <a href="edit.php?PembimbingID=<?php echo $pembimbingID; ?>" class="edit-button">Edit Profil</a>
    </div>

    <script>
        function openModal() {
            const modal = document.getElementById("passwordModal");
            modal.style.display = "flex";
        }

        function closeModal() {
            const modal = document.getElementById("passwordModal");
            modal.style.display = "none";
        }

        window.onload = function() {
            closeModal();
        };

        window.onclick = function(event) {
            const modal = document.getElementById("passwordModal");
            if (event.target === modal) {
                closeModal();
            }
        };

        function togglePasswordVisibility(passwordId) {
            const passwordField = document.getElementById(passwordId);
            const eyeIcon = passwordField.nextElementSibling;

            if (passwordField.type === "password") {
                passwordField.type = "text";
                eyeIcon.src = "css/logo/view.png"; 
            } else {
                passwordField.type = "password";
                eyeIcon.src = "css/logo/close.png"; 
            }
        }
    </script>

</body>
</html>
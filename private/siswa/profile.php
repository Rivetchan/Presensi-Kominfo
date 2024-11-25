<?php
session_start();
require_once('../../config/koneksi.php');

if (!isset($_GET['SiswaID'])) {
    header("Location: ../../404.php");
    exit();
}

$siswaID = $_GET['SiswaID'];

$query = "SELECT Username, NamaSiswa, AsalSekolah, WaktuPKL, NoHP, FotoSiswa, Password FROM siswa WHERE SiswaID = ?";
$stmt = $kon->prepare($query);
$stmt->bind_param("i", $siswaID);
$stmt->execute();
$result = $stmt->get_result();
$siswa = $result->fetch_assoc();

if (!$siswa) {
    header("Location: ../../404.php");
    exit();
}

$fotoSiswa = $siswa['FotoSiswa'] ?: 'css/logo/profile.png';
$siswaPassword = $siswa['Password']; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Siswa</title>
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
                <form id="editPasswordForm" action="update_password.php?SiswaID=<?php echo $siswaID; ?>" method="post">

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

        <h2>Profile Siswa</h2>
        <div class="profile-picture" style="background-image: url('<?php echo $fotoSiswa; ?>');" onclick="triggerFileInput()">
            <div class="upload-text">Upload</div>
        </div>
        <form id="uploadForm" action="upload_foto.php?SiswaID=<?php echo $siswaID; ?>" method="post" enctype="multipart/form-data">
            <input type="file" name="fotoSiswa" id="fileInput" accept="image/*" style="display: none;" onchange="previewImage(event)">
            <button type="submit" id="uploadButton" class="btn-upload" style="display: none;">Upload Foto</button>
            <button type="button" id="cancelButton" class="btn-cancel" style="display: none;" onclick="cancelUpload()">Cancel</button>
        </form>
        <div class="profile-details">
            <label>Username</label>
            <p><?php echo htmlspecialchars($siswa['Username']); ?></p>
            <label>Nama Panjang</label>
            <p><?php echo htmlspecialchars($siswa['NamaSiswa']); ?></p>
            <label>Asal Sekolah</label>
            <p><?php echo htmlspecialchars($siswa['AsalSekolah']); ?></p>
            <label>Waktu PKL</label>
            <p><?php echo htmlspecialchars($siswa['WaktuPKL']); ?></p>
            <label>No HP</label>
            <p><?php echo htmlspecialchars($siswa['NoHP']); ?></p>
        </div>

        <a href="../../logout.php" class="logout-button">Logout</a>
        <a href="edit.php?SiswaID=<?php echo $siswaID; ?>" class="edit-button">Edit Profil</a>

    </div>

    <script>
        const defaultProfilePicture = "<?php echo $fotoSiswa; ?>";

        function triggerFileInput() {
            const fileInput = document.getElementById('fileInput');
            fileInput.click();
        }

        function previewImage(event) {
            const fileInput = event.target;
            const uploadButton = document.getElementById('uploadButton');
            const cancelButton = document.getElementById('cancelButton');
            const profilePicture = document.querySelector('.profile-picture');

            if (fileInput.files && fileInput.files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    profilePicture.style.backgroundImage = `url(${e.target.result})`;
                };

                reader.readAsDataURL(fileInput.files[0]);

                uploadButton.style.display = 'inline-block';
                cancelButton.style.display = 'inline-block';
            }
        }

        function cancelUpload() {
            const profilePicture = document.querySelector('.profile-picture');
            const uploadButton = document.getElementById('uploadButton');
            const cancelButton = document.getElementById('cancelButton');
            const fileInput = document.getElementById('fileInput');

            profilePicture.style.backgroundImage = `url(${defaultProfilePicture})`;

            uploadButton.style.display = 'none';
            cancelButton.style.display = 'none';

            fileInput.value = '';

            window.location.reload(); 
        }

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
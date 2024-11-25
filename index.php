<?php
session_start();
require_once('config/koneksi.php');

if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['UserID'];

$queryAdmin = "SELECT Role FROM admin WHERE AdminID = ?";
$querySiswa = "SELECT Role FROM siswa WHERE SiswaID = ?";
$queryPembimbing = "SELECT Role FROM pembimbing WHERE PembimbingID = ?";

if ($stmt = $kon->prepare($queryAdmin)) {
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['Role'] = $row['Role'];
    } else {
        if ($stmt = $kon->prepare($querySiswa)) {
            $stmt->bind_param("i", $userID);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $_SESSION['Role'] = $row['Role'];
            } else {
                if ($stmt = $kon->prepare($queryPembimbing)) {
                    $stmt->bind_param("i", $userID);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $_SESSION['Role'] = $row['Role'];
                    } else {
                        header("Location: login.php");
                        exit();
                    }
                }
            }
        }
    }
}

$role = $_SESSION['Role'];

if (!isset($_SESSION['notification_shown'])) {
    $_SESSION['notification_shown'] = true;
    $showNotification = true;
} else {
    $showNotification = false;
}

$defaultProfilePicture = 'private/siswa/css/logo/profile.png';
$userProfilePicture = 'private/siswa/upload/' . $_SESSION['UserID'];
$validExtensions = ['png', 'jpeg', 'jpg', 'gif'];
$profilePictureFound = false;

foreach ($validExtensions as $ext) {
    if (file_exists($userProfilePicture . '.' . $ext)) {
        $userProfilePicture .= '.' . $ext;
        $profilePictureFound = true;
        break;
    }
}

if (!$profilePictureFound) {
    $userProfilePicture = $defaultProfilePicture;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <div class="navbar">
        <div class="logo-container">
            <img src="css/image/Humas.png" alt="Logo">
            <h2>Presensi PKL di Kominfo</h2>
        </div>
        <div class="menu">
            <ul>
                <?php if ($role === 'Admin'): ?>
                    <li><a href="private/admin/form_pendaftaran.php">Form Pendaftaran</a></li>
                    <li><a href="private/admin/data_pendaftaran.php">Data Pendaftaran</a></li>
                    <li><a href="private/data_admin.php">Halaman Admin</a></li>
                    <li><a href="logout.php">Log Out</a></li>
                <?php elseif ($role === 'Pembimbing' || $role === 'Siswa'): ?>
                <?php endif; ?>
            </ul>

            <?php if ($role !== 'Admin'): ?>
            <div class="profile-info">
                <a href="<?php echo ($role === 'Siswa') ? 'private/siswa/profile.php?SiswaID=' . $_SESSION['UserID'] : 
                           (($role === 'Pembimbing') ? 'private/pembimbing/profile.php?PembimbingID=' . $_SESSION['UserID'] : '#'); ?>">
                    <div class="profile-picture">
                        <img src="<?php echo $userProfilePicture; ?>" alt="Profile Picture">
                    </div>
                    <span class="profile-name"><?php echo htmlspecialchars($_SESSION['Username']); ?></span>
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($showNotification): ?>
        <div class="notification" id="welcomeNotification">
            <p>Selamat Datang, <?php echo htmlspecialchars($_SESSION['Username']); ?>!</p>
            <button class="close-btn" onclick="closeNotification()">×</button>
        </div>
    <?php endif; ?>

    <script>
        function closeNotification() {
            const notification = document.getElementById('welcomeNotification');
            notification.style.opacity = '0';
            setTimeout(() => {
                notification.style.display = 'none';
            }, 3000);
        }

        setTimeout(closeNotification, 3000);
    </script>
</body>
</html>

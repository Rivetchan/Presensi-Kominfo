<?php
session_start();
include_once("config/koneksi.php");

$error_message = "";

if ($kon->connect_error) {
    die("Connection failed: " . $kon->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Username = $_POST['Username'];
    $Password = $_POST['Password'];

    // ADMIN
    $sqlAdmin = "SELECT AdminID, Username FROM admin WHERE Username = ? AND Password = ?";
    $stmtAdmin = $kon->prepare($sqlAdmin);
    $stmtAdmin->bind_param("ss", $Username, $Password);
    $stmtAdmin->execute();
    $resultAdmin = $stmtAdmin->get_result();

    if ($resultAdmin->num_rows == 1) {
        $row = $resultAdmin->fetch_assoc();

        $_SESSION['UserID'] = $row['AdminID'];
        $_SESSION['Username'] = $row['Username'];
        $_SESSION['Role'] = 'Admin';

        header("Location: index.php");
        exit();
    }

    // PEMBIMBING
    $sqlPembimbing = "SELECT PembimbingID, Username FROM pembimbing WHERE Username = ? AND Password = ?";
    $stmtPembimbing = $kon->prepare($sqlPembimbing);
    $stmtPembimbing->bind_param("ss", $Username, $Password);
    $stmtPembimbing->execute();
    $resultPembimbing = $stmtPembimbing->get_result();

    if ($resultPembimbing->num_rows == 1) {
        $row = $resultPembimbing->fetch_assoc();

        $_SESSION['UserID'] = $row['PembimbingID'];
        $_SESSION['Username'] = $row['Username'];
        $_SESSION['Role'] = 'Pembimbing';

        header("Location: index.php");
        exit();
    }

    // SISWA
    $sqlSiswa = "SELECT SiswaID, Username FROM siswa WHERE Username = ? AND Password = ?";
    $stmtSiswa = $kon->prepare($sqlSiswa);
    $stmtSiswa->bind_param("ss", $Username, $Password);
    $stmtSiswa->execute();
    $resultSiswa = $stmtSiswa->get_result();

    if ($resultSiswa->num_rows == 1) {
        $row = $resultSiswa->fetch_assoc();

        $_SESSION['UserID'] = $row['SiswaID'];
        $_SESSION['Username'] = $row['Username'];
        $_SESSION['Role'] = 'Siswa';

        header("Location: index.php");
        exit();
    }

    $error_message = "Failed Login, invalid Username or Password";

    $stmtAdmin->close();
    $stmtPembimbing->close();
    $stmtSiswa->close();
}

$kon->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <div class="logo-container">
            <img src="css/image/Humas.png" alt="School Logo" class="school-logo">
        </div>
        <form action="login.php" method="POST">
            <label for="Username">Username :</label>
            <input type="text" id="Username" name="Username" required>

            <div class="password-wrapper">
                <label for="Password">Password :</label>
                <input type="password" id="Password" name="Password" required>
                <img src="css/image/logo/view.png" alt="Show/Hide Password" id="eyeIcon" class="eye-icon" onclick="togglePassword()">
            </div>

            <div class="remember-me">
                <input type="checkbox" id="rememberMe" name="rememberMe">
                <label for="rememberMe">Remember me</label>
            </div>

            <button type="submit">Login</button>
        </form>
        <p id="errorMessage" class="error-message"><?php echo $error_message; ?></p>
    </div>

    <script>
        function togglePassword() {
            var passwordInput = document.getElementById("Password");
            var eyeIcon = document.getElementById("eyeIcon");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.src = "css/image/logo/close.png";
            } else {
                passwordInput.type = "password";
                eyeIcon.src = "css/image/logo/view.png";
            }
        }
    </script>
</body>
</html>
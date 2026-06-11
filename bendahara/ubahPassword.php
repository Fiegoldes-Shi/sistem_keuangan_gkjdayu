<?php
$message = ""; // Pesan untuk user

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_SESSION["id_user"]; // Menggunakan id_user dari session
    $old_password = $_POST["old_password"] ?? "";
    $new_password = $_POST["new_password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";

    // Validasi server-side: semua field wajib diisi
    if (
        empty($old_password) ||
        empty($new_password) ||
        empty($confirm_password)
    ) {
        $message = "Semua field wajib diisi.";
    } elseif (strlen($new_password) < 3) {
        $message = "Password baru minimal 3 karakter.";
    } else {
        $sql = "SELECT password FROM user WHERE id_user = $username";
        $view = new cView();
        $array = $view->vViewData($sql);
        $password = $array[0]["password"];

        if (md5($old_password) !== $password) {
            $message = "Password lama salah!";
        } elseif ($new_password !== $confirm_password) {
            $message = "Password baru dan konfirmasi password tidak sama!";
        } else {
            $new_hashed_password = md5($new_password);
            $update_sql = "UPDATE user SET password = ? WHERE id_user = ?";
            $update_stmt = $GLOBALS["conn"]->prepare($update_sql);
            $update_stmt->bind_param("ss", $new_hashed_password, $username);
            if ($update_stmt->execute()) {
                $message = "Password berhasil diubah!";
            } else {
                $message = "Gagal mengubah password. Silakan coba lagi.";
            }
        }
    }
}
?>

<style>
    .password-wrapper {
        position: relative;
    }

    .password-wrapper input {
        padding-right: 40px; /* Memberikan ruang untuk ikon di kanan */
    }

    .password-wrapper .btn-link {
        position: absolute;
        right: 10px; /* Menempatkan ikon di sebelah kanan input */
        top: 70%;
        transform: translateY(-50%);
        z-index: 10;
    }
</style>
<script>
    // Function to toggle password visibility
    function togglePassword(id) {
        const passwordField = document.getElementById(id);
        const icon = document.getElementById(id + '_icon'); // ID untuk ikon
        const type = passwordField.type === "password" ? "text" : "password";
        passwordField.type = type;
        
        // Mengubah ikon mata berdasarkan status password
        if (type === "password") {
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        } else {
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
    }
</script>

<div class="container mt-5">
    <div class="card mx-auto" style="max-width: 500px;">
        <div class="card-body">
            <h4 class="text-center mb-4">Ubah Password</h4>
            <form method="POST">
                <div class="mb-3 password-wrapper">
                    <label for="old_password" class="form-label">Password Lama</label>
                    <input type="password" class="form-control" id="old_password" name="old_password" required>
                    <button type="button" class="btn btn-link" onclick="togglePassword('old_password')">
                        <i id="old_password_icon" class="fas fa-eye-slash"></i>
                    </button>
                </div>
                <div class="mb-3 password-wrapper">
                    <label for="new_password" class="form-label">Password Baru</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" minlength="3" required>
                    <button type="button" class="btn btn-link" onclick="togglePassword('new_password')">
                        <i id="new_password_icon" class="fas fa-eye-slash"></i>
                    </button>
                </div>
                <div class="mb-3 password-wrapper">
                    <label for="confirm_password" class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    <button type="button" class="btn btn-link" onclick="togglePassword('confirm_password')">
                        <i id="confirm_password_icon" class="fas fa-eye-slash"></i>
                    </button>
                </div>
                <button type="submit" class="btn btn-primary w-100">Ubah Password</button>
            </form>

            <!-- Menampilkan pesan hasil -->
            <?php if ($message): ?>
                <div class="mt-3 text-center alert <?= strpos(
                    $message,
                    "berhasil",
                ) !== false
                    ? "alert-success"
                    : "alert-danger" ?>">
                    <?= $message ?>
                    <br><br>
                    <a href="0">Kembali ke Beranda</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>


<?php
session_start();

// DULU: require_once "koneksi.php";
require_once __DIR__ . "/admin/koneksi.php";   // <-- pakai path ke folder admin

$error = "";



if (isset($_POST["btnLogin"])) {
    $tuser = $_POST["tuser"];
    $tpass = $_POST["tpass"];

    $sql = "SELECT * FROM users 
            WHERE username='$tuser'
            AND password = md5('$tpass')";
    $hasil = $koneksi->query($sql);
    $jml = $hasil->num_rows;

    if ($jml > 0) {
        $data = $hasil->fetch_assoc();

        $_SESSION['isLogin'] = true;
        $_SESSION['user']    = $data['username'];
        $_SESSION['level']   = $data['level'];

        if ($data['level'] == "admin") {
            header("location: admin/index.php");
            exit;
        } elseif ($data['level'] == "dosen") {
            header("location: admin/dosen.php");
            exit;
        } elseif ($data['level'] == "mhs") {
            header("location: admin/mahasiswa.php");
            exit;
        }

    } else {
        $error = "Username atau Password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Login Sistem</title>

  <link rel="stylesheet" href="assets/css/adminlte.min.css">
  <link rel="stylesheet" href="assets/css/all.min.css">
</head>

<body class="login-page">

<div class="login-box">
  <div class="login-logo">
    <a><b>Siakad</b> UinSaizu</a>
  </div>

  <div class="card">
    <div class="card-body login-card-body">

      <p class="login-box-msg">Silakan Login</p>

      <?php if ($error != ""): ?>
        <div class="alert alert-danger"><?= $error ?></div>
      <?php endif; ?>

      <form action="" method="post">
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="tuser" placeholder="Username" required>
          <div class="input-group-text"><span class="fas fa-user"></span></div>
        </div>

        <div class="input-group mb-3">
          <input type="password" class="form-control" name="tpass" placeholder="Password" required>
          <div class="input-group-text"><span class="fas fa-lock"></span></div>
        </div>

        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember">Remember Me</label>
            </div>
          </div>
          <div class="col-4">
            <button type="submit" name="btnLogin" class="btn btn-primary btn-block">Login</button>
          </div>
        </div>
      </form>

    </div>
  </div>

</div>

</body>
</html>

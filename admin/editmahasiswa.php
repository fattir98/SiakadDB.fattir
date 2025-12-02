<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

require_once __DIR__ . '/koneksi.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: ./?p=mahasiswa'); exit; }

// Ambil data lama
$stmt = $koneksi->prepare("SELECT id, nim, nama, jenis_kelamin, alamat, prodi FROM mahasiswa WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$mhs = $stmt->get_result()->fetch_assoc();
if (!$mhs) { header('Location: ./?p=mahasiswa'); exit; }

// Prefill
$nim    = $_POST['nim'] ?? $mhs['nim'];
$nama   = $_POST['nama'] ?? $mhs['nama'];
$jk     = $_POST['jk'] ?? $mhs['jenis_kelamin'];   // NAME radio: jk
$alamat = $_POST['alamat'] ?? $mhs['alamat'];
$prodi  = $_POST['prodi'] ?? (string)$mhs['prodi'];
$message = '';

if (isset($_POST['update'])) {
  // Cek NIM duplikat selain dirinya
  $cek = $koneksi->prepare("SELECT 1 FROM mahasiswa WHERE nim=? AND id<>?");
  $cek->bind_param("si", $nim, $id);
  $cek->execute(); $cek->store_result();

  if ($cek->num_rows > 0) {
    $message = "NIM sudah dipakai mahasiswa lain.";
  } else {
    $upd = $koneksi->prepare("UPDATE mahasiswa SET nim=?, nama=?, jenis_kelamin=?, alamat=?, prodi=? WHERE id=?");
    $prodiInt = (int)$prodi;
    $upd->bind_param("ssssii", $nim, $nama, $jk, $alamat, $prodiInt, $id);
    $upd->execute();

    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
          <script>
            Swal.fire({icon:'success',title:'Tersimpan',timer:1200,showConfirmButton:false})
              .then(()=>location.href='./?p=mahasiswa');
          </script>";
    exit;
  }
}

function e($s){ return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }
?>
<div class="card">
  <div class="card-header"><h3>Edit Mahasiswa</h3></div>
  <div class="card-body">
    <?php if ($message): ?><div class="alert alert-danger"><?= e($message) ?></div><?php endif; ?>
    <form method="post">
      <table class="table">
        <tr>
          <td><label for="nim">NIM</label></td>
          <td><input type="text" class="form-control" id="nim" name="nim" required value="<?= e($nim) ?>"></td>
        </tr>
        <tr>
          <td><label for="nama">Nama Lengkap</label></td>
          <td><input type="text" class="form-control" id="nama" name="nama" required value="<?= e($nama) ?>"></td>
        </tr>
        <tr>
          <td><label>Jenis Kelamin</label></td>
          <td>
            <input class="form-check-input" type="radio" name="jk" id="perempuan" value="perempuan" <?= ($jk==='perempuan'?'checked':'') ?> required>
            <label for="perempuan">Perempuan</label>
            <input class="form-check-input ms-3" type="radio" name="jk" id="laki" value="laki-laki" <?= ($jk==='laki-laki'?'checked':'') ?> required>
            <label for="laki">Laki-laki</label>
          </td>
        </tr>
        <tr>
          <td><label for="alamat">Alamat</label></td>
          <td><textarea class="form-control" id="alamat" name="alamat" required><?= e($alamat) ?></textarea></td>
        </tr>
        <tr>
          <td><label for="prodi">Prodi</label></td>
          <td>
            <select class="form-control" id="prodi" name="prodi" required>
              <option value="">-- Pilih --</option>
              <option value="1" <?= ($prodi==='1'?'selected':'') ?>>Informatika</option>
              <option value="2" <?= ($prodi==='2'?'selected':'') ?>>Arsitektur</option>
              <option value="3" <?= ($prodi==='3'?'selected':'') ?>>Ilmu Lingkungan</option>
            </select>
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <button type="submit" name="update" class="btn btn-success">Simpan Perubahan</button>
            <a href="./?p=mahasiswa" class="btn btn-secondary">Batal</a>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>

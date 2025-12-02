<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

require_once __DIR__ . '/koneksi.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: ./?p=mahasiswa'); exit; }

$stmt = $koneksi->prepare("SELECT id, nim, nama, jenis_kelamin, alamat, prodi, tanggal FROM mahasiswa WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$mhs = $stmt->get_result()->fetch_assoc();
if (!$mhs) { header('Location: ./?p=mahasiswa'); exit; }

function e($s){ return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }
function jk_label($jk){
  if ($jk === 'laki-laki') return 'Laki-laki';
  if ($jk === 'perempuan') return 'Perempuan';
  return '-';
}
function prodi_label($prodi){
  if ($prodi == 1) return 'Informatika';
  if ($prodi == 2) return 'Arsitektur';
  if ($prodi == 3) return 'Ilmu Lingkungan';
  return '-';
}
?>

<div class="content-wrapper p-4">
  <section class="content-header mb-3">
    <h3>Detail Mahasiswa</h3>
  </section>

  <section class="content">
    <div class="card shadow-sm">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Detail: <?= e($mhs['nama']) ?></h5>
        <a href="./?p=mahasiswa" class="btn btn-secondary btn-sm">Kembali</a>
      </div>

      <div class="card-body">
        <table class="table table-bordered">
          <tr><th width="30%">ID</th><td><?= e($mhs['id']) ?></td></tr>
          <tr><th>NIM</th><td><?= e($mhs['nim']) ?></td></tr>
          <tr><th>Nama Lengkap</th><td><?= e($mhs['nama']) ?></td></tr>
          <tr><th>Jenis Kelamin</th><td><?= e(jk_label($mhs['jenis_kelamin'])) ?></td></tr>
          <tr><th>Alamat</th><td><?= nl2br(e($mhs['alamat'])) ?></td></tr>
          <tr><th>Program Studi</th><td><?= e(prodi_label($mhs['prodi'])) ?></td></tr>
          <tr><th>Tanggal Input</th><td><?= e($mhs['tanggal']) ?></td></tr>
        </table>

        <div class="mt-3 d-flex gap-2">
          <a href="./?p=editmhs&id=<?= $mhs['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
          <a href="./?p=hapusmhs&id=<?= $mhs['id'] ?>" class="btn btn-danger btn-sm"
             onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
        </div>
      </div>
    </div>
  </section>
</div>

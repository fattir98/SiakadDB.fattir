<?php
require_once __DIR__ . '/koneksi.php';   // koneksi ada di folder admin

$p = $_GET['p'] ?? 'dashboard';

switch ($p) {
  case 'mahasiswa':
    require __DIR__ . '/mahasiswa.php';          // ← TANPA /pages
    break;

  case 'tambah-mahasiswa':
    require_once "tambahmahasiswa.php";    // ← TANPA /pages
    break;

 case 'editmhs':
  require __DIR__ . '/editmahasiswa.php';  // ← pakai __DIR__
  break;

  case 'detailmhs':
  require __DIR__ . '/detailmahasiswa.php';
  break;

  case 'hapusmhs':
    $id = (int)($_GET['id'] ?? 0);
    if ($id) { $koneksi->query("DELETE FROM mahasiswa WHERE id=$id"); }
    header('Location: ./?p=mahasiswa'); exit;

    case 'dosen':
  require __DIR__ . '/dosen.php';
  break;

case 'tambah-dosen':
  require __DIR__ . '/tambahdosen.php';
  break;

case 'editdsn':
  require __DIR__ . '/editdosen.php';
  break;

case 'detaildsn':
  require __DIR__ . '/detaildosen.php';
  break;

case 'hapusdsn':
  $id = (int)($_GET['id'] ?? 0);
  if ($id) { $koneksi->query("DELETE FROM dosen WHERE id=$id"); }
  header('Location: ./?p=dosen'); exit;


  default:
    echo "<h3>Selamat Datang di Dashboard</h3>";
    echo "<p>Pilih menu di sidebar untuk mulai.</p>";
}


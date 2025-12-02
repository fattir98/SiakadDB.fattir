<?php
require_once __DIR__.'/koneksi.php';
$id=(int)($_GET['id']??0); if(!$id){ header('Location: ./?p=dosen'); exit; }
$d=$koneksi->query("SELECT id,nidn,nama,jenis_kelamin,alamat,prodi,tanggal FROM dosen WHERE id=$id")->fetch_assoc();
if(!$d){ header('Location: ./?p=dosen'); exit; }
function e($s){ return htmlspecialchars($s??'',ENT_QUOTES,'UTF-8'); }
function jk($v){ return $v==='laki-laki'?'Laki-laki':($v==='perempuan'?'Perempuan':'-'); }
function prodi($v){ if($v==1)return'Informatika'; if($v==2)return'Arsitektur'; if($v==3)return'Ilmu Lingkungan'; return'-'; }
?>
<div class="content-wrapper p-4">
  <section class="content-header mb-3"><h3>Detail Dosen</h3></section>
  <section class="content">
    <div class="card">
      <div class="card-header d-flex justify-content-between">
        <h5 class="mb-0"><?= e($d['nama']) ?></h5>
        <div>
          <a href="./?p=editdsn&id=<?= (int)$d['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
          <a href="./?p=dosen" class="btn btn-secondary btn-sm">Kembali</a>
        </div>
      </div>
      <div class="card-body">
        <table class="table table-bordered">
          <tr><th style="width:30%">ID</th><td><?= e($d['id']) ?></td></tr>
          <tr><th>NIDN</th><td><?= e($d['nidn']) ?></td></tr>
          <tr><th>Nama</th><td><?= e($d['nama']) ?></td></tr>
          <tr><th>Jenis Kelamin</th><td><?= e(jk($d['jenis_kelamin'])) ?></td></tr>
          <tr><th>Alamat</th><td><?= nl2br(e($d['alamat'])) ?></td></tr>
          <tr><th>Prodi</th><td><?= e(prodi($d['prodi'])) ?></td></tr>
          <tr><th>Tanggal Input</th><td><?= e($d['tanggal']) ?></td></tr>
        </table>
      </div>
    </div>
  </section>
</div>

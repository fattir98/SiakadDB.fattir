<?php
require_once __DIR__.'/koneksi.php';
$id=(int)($_GET['id']??0); if(!$id){ header('Location: ./?p=dosen'); exit; }
$d=$koneksi->query("SELECT * FROM dosen WHERE id=$id")->fetch_assoc(); if(!$d){ header('Location: ./?p=dosen'); exit; }

$nidn=$_POST['nidn']??$d['nidn']; $nama=$_POST['nama']??$d['nama'];
$jk=$_POST['jk']??$d['jenis_kelamin']; $alamat=$_POST['alamat']??$d['alamat']; $prodi=$_POST['prodi']??(string)$d['prodi']; $msg='';

if(isset($_POST['update'])){
  $cek=$koneksi->query("SELECT 1 FROM dosen WHERE nidn='$nidn' AND id<>$id");
  if($cek->num_rows>0){ $msg='NIDN sudah dipakai dosen lain.'; }
  else{
    $koneksi->query("UPDATE dosen SET nidn='$nidn', nama='$nama', jenis_kelamin='$jk', alamat='$alamat', prodi='$prodi' WHERE id=$id");
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script><script>
      Swal.fire({icon:'success',title:'Tersimpan',timer:1200,showConfirmButton:false})
      .then(()=>location.href='./?p=dosen');
    </script>"; exit;
  }
}
function e($s){ return htmlspecialchars($s??'',ENT_QUOTES,'UTF-8'); }
?>
<div class="card">
  <div class="card-header"><h3>Edit Dosen</h3></div>
  <div class="card-body">
    <?php if($msg): ?><div class="alert alert-danger"><?=e($msg)?></div><?php endif; ?>
    <form method="post">
      <table class="table">
        <tr><td>NIDN</td><td><input class="form-control" name="nidn" required value="<?=e($nidn)?>"></td></tr>
        <tr><td>Nama</td><td><input class="form-control" name="nama" required value="<?=e($nama)?>"></td></tr>
        <tr>
          <td>Jenis Kelamin</td>
          <td>
            <input class="form-check-input" type="radio" name="jk" id="p" value="perempuan" <?=($jk==='perempuan'?'checked':'')?> required><label for="p">Perempuan</label>
            <input class="form-check-input ms-3" type="radio" name="jk" id="l" value="laki-laki" <?=($jk==='laki-laki'?'checked':'')?> required><label for="l">Laki-laki</label>
          </td>
        </tr>
        <tr><td>Alamat</td><td><textarea class="form-control" name="alamat" required><?=e($alamat)?></textarea></td></tr>
        <tr>
          <td>Prodi</td>
          <td>
            <select class="form-control" name="prodi" required>
              <option value="">-- Pilih --</option>
              <option value="1" <?=($prodi==='1'?'selected':'')?>>Informatika</option>
              <option value="2" <?=($prodi==='2'?'selected':'')?>>Arsitektur</option>
              <option value="3" <?=($prodi==='3'?'selected':'')?>>Ilmu Lingkungan</option>
            </select>
          </td>
        </tr>
        <tr><td colspan="2"><button class="btn btn-success" name="update">Simpan Perubahan</button> <a href="./?p=dosen" class="btn btn-secondary">Batal</a></td></tr>
      </table>
    </form>
  </div>
</div>

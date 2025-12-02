<?php
require_once __DIR__.'/koneksi.php';
$nidn=$_POST['nidn']??''; $nama=$_POST['nama']??''; $jk=$_POST['jk']??''; $alamat=$_POST['alamat']??''; $prodi=$_POST['prodi']??'';
$msg=''; $ok=false;

if (isset($_POST['simpan'])) {
  $cek=$koneksi->query("SELECT 1 FROM dosen WHERE nidn='$nidn'");
  if($cek->num_rows>0){ $msg='NIDN sudah digunakan!'; }
  else{
    $ok=$koneksi->query("INSERT INTO dosen (nidn,nama,jenis_kelamin,alamat,prodi,tanggal)
                         VALUES ('$nidn','$nama','$jk','$alamat','$prodi',NOW())");
    if(!$ok){ $msg='MySQL: '.$koneksi->error; }
  }
  echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script><script>
    Swal.fire({icon:'".($ok?'success':'error')."',title:'".($ok?'Berhasil!':'Gagal!')."',text:'".($ok?'Data dosen tersimpan':addslashes($msg))."',timer:1500,showConfirmButton:false})
    .then(()=>{ ".($ok?"location.href='./?p=dosen'":"")."; });
  </script>";
}
function e($s){ return htmlspecialchars($s??'',ENT_QUOTES,'UTF-8'); }
?>
<div class="card">
  <div class="card-header"><h3>Tambah Dosen</h3></div>
  <div class="card-body">
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
        <tr><td colspan="2"><button class="btn btn-success" name="simpan">Simpan</button> <a href="./?p=dosen" class="btn btn-secondary">Batal</a></td></tr>
      </table>
    </form>
  </div>
</div>

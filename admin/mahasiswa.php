<?php
require_once __DIR__ . '/koneksi.php';

/* =========================
   Inisialisasi & Pencarian
   ========================= */
$keyword  = $_POST['keyword']  ?? '';
$category = $_POST['category'] ?? 'nim';

// SQL default (tanpa filter)
$sql = "SELECT * FROM mahasiswa ORDER BY id ASC";

// Jika tombol "Cari" ditekan
if (isset($_POST['cari'])) {
  if ($category === 'prodi') {
    // Mapping kata → kode prodi (sesuai skema kamu)
    $mapProdi = [
      'informatika' => '1',
      'arsitektur'  => '2',
    ];
    $keyLower = strtolower(trim($keyword));
    $prodi    = $mapProdi[$keyLower] ?? $keyword; // kalau user ketik "1"/"2" langsung, tetap dipakai

    $prodiEsc = $koneksi->real_escape_string($prodi);
    $sql = "SELECT * FROM mahasiswa WHERE prodi LIKE '%{$prodiEsc}%' ORDER BY id ASC";
  } else {
    // Pencarian umum untuk nim/nama/jenis_kelamin
    $catAllowed = ['nim', 'nama', 'jenis_kelamin'];
    $cat        = in_array($category, $catAllowed, true) ? $category : 'nim';

    $kw = $koneksi->real_escape_string($keyword);
    $sql = "SELECT * FROM mahasiswa WHERE {$cat} LIKE '%{$kw}%' ORDER BY id ASC";
  }
}

// Eksekusi query final
$result = $koneksi->query($sql);

/* =========================
   Helper Label
   ========================= */
function jk_label($jk) {
  $jk = strtolower((string)$jk);
  if ($jk === 'laki-laki' || $jk === 'l') return 'Laki-laki';
  if ($jk === 'perempuan' || $jk === 'p') return 'Perempuan';
  return '-';
}
function prodi_label($prodi) {
  // '1' = Informatika, '2' = Arsitektur (sesuai skema kamu)
  if ((string)$prodi === '1') return 'Informatika';
  if ((string)$prodi === '2') return 'Arsitektur';
  // fallback: tampilkan apa adanya jika bukan 1/2
  return (string)$prodi;
}
?>

<div class="content-wrapper p-4">
  <section class="content-header mb-3">
    <h3>Data Mahasiswa</h3>
  </section>

  <section class="content">
    <div class="card shadow-sm">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title mb-0">Daftar Mahasiswa</h4>
        <!-- Samakan route dengan yang kamu pakai di project -->
        
      </div>

      <div class="card-body">
        <!-- Toolbar Pencarian -->
        <form method="post" action="#" class="row g-2 align-items-center mb-3">
          <div class="col-12 col-md-5">
            <input
              type="text"
              name="keyword"
              class="form-control"
              placeholder="Masukkan kata kunci"
              value="<?= htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8') ?>"
            >
          </div>
          <div class="col-6 col-md-3">
            <select name="category" class="form-select">
              <option value="nim"            <?= $category==='nim' ? 'selected' : '' ?>>NIM</option>
              <option value="nama"           <?= $category==='nama' ? 'selected' : '' ?>>Nama</option>
              <option value="prodi"          <?= $category==='prodi' ? 'selected' : '' ?>>Prodi</option>
              <option value="jenis_kelamin"  <?= $category==='jenis_kelamin' ? 'selected' : '' ?>>Jenis Kelamin</option>
            </select>
          </div>
          <div class="col-6 col-md-2 d-grid">
            <button type="submit" name="cari" value="1" class="btn btn-outline-secondary">Cari</button>
          </div>
          <div class="col-12 col-md-2 d-grid d-md-flex justify-content-md-end">
            <!-- Tombol tambah juga di toolbar biar rapi di breakpoint kecil -->
            <a href="./?p=tambah-mahasiswa" class="btn btn-primary w-100">+ Mahasiswa</a>
          </div>
        </form>

        <!-- Tabel Data -->
        <div class="table-responsive">
          <table class="table table-bordered table-striped align-middle mb-0">
            <thead class="table-light">
              <tr class="text-center">
                <th style="width:60px;">No</th>
                <th>NIM</th>
                <th>Nama Mahasiswa</th>
                <th style="width:140px;">Jenis Kelamin</th>
                <th style="width:140px;">Prodi</th>
                <th style="width:200px;">Opsi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if ($result && $result->num_rows > 0) {
                $no = 1;
                while ($row = $result->fetch_assoc()) {
                  $id   = (int)($row['id'] ?? 0);
                  $nim  = htmlspecialchars($row['nim']  ?? '', ENT_QUOTES, 'UTF-8');
                  $nama = htmlspecialchars($row['nama'] ?? '', ENT_QUOTES, 'UTF-8');
                  $jk   = jk_label($row['jenis_kelamin'] ?? '');
                  $prd  = prodi_label($row['prodi'] ?? '');

                  echo '
                    <tr>
                      <td class="text-center">'.$no.'</td>
                      <td>'.$nim.'</td>
                      <td>'.$nama.'</td>
                      <td>'.$jk.'</td>
                      <td>'.$prd.'</td>
                      <td class="text-center">
                        <a href="./?p=detailmhs&id='.$id.'" class="btn btn-info btn-sm me-1">Detail</a>
                        <a href="./?p=editmhs&id='.$id.'"   class="btn btn-warning btn-sm me-1">Edit</a>
                        <a href="./?p=hapusmhs&id='.$id.'"  class="btn btn-danger btn-sm"
                           onclick="return confirm(\'Yakin hapus data ini?\')">Hapus</a>
                      </td>
                    </tr>
                  ';
                  $no++;
                }
              } else {
                echo '<tr><td colspan="6" class="text-center text-muted py-4">Belum ada data</td></tr>';
              }
              ?>
            </tbody>
          </table>
        </div>

      </div><!-- /.card-body -->
    </div><!-- /.card -->
  </section>
</div>

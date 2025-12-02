<?php
require_once __DIR__ . '/koneksi.php';

/* =========================
   Inisialisasi & Pencarian
   ========================= */
$keyword  = $_POST['keyword']  ?? '';
$category = $_POST['category'] ?? 'nidn';

// SQL default (tanpa filter)
$sql = "SELECT * FROM dosen ORDER BY id ASC";

// Jika tombol "Cari" ditekan
if (isset($_POST['cari'])) {
  if ($category === 'prodi') {
    // Mapping kata → kode prodi (sesuai skema kamu)
    $mapProdi = [
      'informatika'       => '1',
      'arsitektur'        => '2',
      'ilmu lingkungan'   => '3',
    ];
    $keyLower = strtolower(trim($keyword));
    $prodi    = $mapProdi[$keyLower] ?? $keyword; // kalau user ketik "1"/"2"/"3" langsung, tetap dipakai

    $prodiEsc = $koneksi->real_escape_string($prodi);
    $sql = "SELECT * FROM dosen WHERE prodi LIKE '%{$prodiEsc}%' ORDER BY id ASC";
  } else {
    // Pencarian umum untuk nidn/nama/jenis_kelamin
    $catAllowed = ['nidn', 'nama', 'jenis_kelamin'];
    $cat        = in_array($category, $catAllowed, true) ? $category : 'nidn';

    $kw  = $koneksi->real_escape_string($keyword);
    $sql = "SELECT * FROM dosen WHERE {$cat} LIKE '%{$kw}%' ORDER BY id ASC";
  }
}

// Eksekusi query final
$r = $koneksi->query($sql);

/* =========================
   Helper Label
   ========================= */
function jk_label_dosen($v){
  $v = strtolower((string)$v);
  if ($v === 'laki-laki' || $v === 'l') return 'Laki-laki';
  if ($v === 'perempuan' || $v === 'p') return 'Perempuan';
  return '-';
}

function prodi_label_dosen($v){
  if ((string)$v === '1') return 'Informatika';
  if ((string)$v === '2') return 'Arsitektur';
  if ((string)$v === '3') return 'Ilmu Lingkungan';
  return '-';
}
?>

<div class="content-wrapper p-4">
  <section class="content-header mb-3">
    <h3>Data Dosen</h3>
  </section>

  <section class="content">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Daftar Dosen</h4>
        <!-- tombol + Dosen di header DIHAPUS sesuai permintaan -->
      </div>

      <div class="card-body">
        <!-- Toolbar Pencarian (disamakan dengan mahasiswa) -->
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
              <option value="nidn"           <?= $category==='nidn' ? 'selected' : '' ?>>NIDN</option>
              <option value="nama"           <?= $category==='nama' ? 'selected' : '' ?>>Nama</option>
              <option value="prodi"          <?= $category==='prodi' ? 'selected' : '' ?>>Prodi</option>
              <option value="jenis_kelamin"  <?= $category==='jenis_kelamin' ? 'selected' : '' ?>>Jenis Kelamin</option>
            </select>
          </div>
          <div class="col-6 col-md-2 d-grid">
            <button type="submit" name="cari" value="1" class="btn btn-outline-secondary">Cari</button>
          </div>
          <div class="col-12 col-md-2 d-grid d-md-flex justify-content-md-end">
            <a href="./?p=tambah-dosen" class="btn btn-primary w-100">+ Dosen</a>
          </div>
        </form>

        <!-- Tabel Data Dosen -->
        <div class="table-responsive">
          <table class="table table-bordered table-striped">
            <thead class="table-light">
              <tr class="text-center">
                <th style="width:60px;">No</th>
                <th>NIDN</th>
                <th>Nama</th>
                <th style="width:140px;">Jenis Kelamin</th>
                <th style="width:140px;">Prodi</th>
                <th style="width:200px;">Opsi</th>
              </tr>
            </thead>
            <tbody>
            <?php
            if ($r && $r->num_rows) {
              $no = 1;
              while ($row = $r->fetch_assoc()) {
                $id   = (int)($row['id'] ?? 0);
                $nidn = htmlspecialchars($row['nidn'] ?? '', ENT_QUOTES, 'UTF-8');
                $nama = htmlspecialchars($row['nama'] ?? '', ENT_QUOTES, 'UTF-8');
                $jk   = jk_label_dosen($row['jenis_kelamin'] ?? '');
                $prd  = prodi_label_dosen($row['prodi'] ?? '');
            ?>
              <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td><?= $nidn ?></td>
                <td><?= $nama ?></td>
                <td><?= $jk ?></td>
                <td><?= $prd ?></td>
                <td class="text-center">
                  <a href="./?p=detaildsn&id=<?= $id ?>" class="btn btn-info btn-sm me-1">Detail</a>
                  <a href="./?p=editdsn&id=<?= $id ?>"   class="btn btn-warning btn-sm me-1">Edit</a>
                  <a href="./?p=hapusdsn&id=<?= $id ?>"  class="btn btn-danger btn-sm"
                     onclick="return confirm('Yakin hapus data ini?')">Hapus</a>
                </td>
              </tr>
            <?php
              }
            } else {
              echo '<tr><td colspan="6" class="text-center text-muted">Belum ada data</td></tr>';
            }
            ?>
            </tbody>
          </table>
        </div>
      </div><!-- /.card-body -->
    </div><!-- /.card -->
  </section>
</div>

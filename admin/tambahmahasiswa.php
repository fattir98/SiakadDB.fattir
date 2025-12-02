    <div class="card">
      <div class="card-header">
        <h3>Tambah Mahasiswa</h3>
        <?php
        if ($_POST['simpan']) {
          $nim = $_POST['nim'];
          $nama = $_POST['nama'];
          $jk = $_POST['jk'];
          $alamat = $_POST['alamat'];
          $prodi = $_POST['prodi'];
          $date = date('Y-m-d H:i:s');

          $cek = $koneksi->query("SELECT nim FROM mahasiswa WHERE nim='$nim'");
          if ($cek->num_rows > 0) {
            $message = "Nim sudah digunakan, silakan masukkan nim lain!";
          } else {
            $insert = $koneksi->query("INSERT INTO mahasiswa (nim, nama, jenis_kelamin, alamat, prodi, tanggal) 
                                    VALUES ('$nim', '$nama', '$jk', '$alamat', '$prodi', '$date')");
          }
          if ($insert) {
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                                    <script>
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Berhasil!',
                                            text: 'Data mahasiswa berhasil disimpan!',
                                            showConfirmButton: false,
                                            timer: 1500
                                        }).then(() => {
                                            window.location.href = './?page=mahasiswa';
                                        });
                                    </script>";
          } else {
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                                    <script>
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Gagal!',
                                            text: 'Gagal menambahkan data! $message',
                                        });
                                    </script>";
          }
        }
        ?>
      </div>
      <div class="card-body">
        <form action="" method="post">
          <table class="table">
            <tr>
              <td><label for="nim">NIM</label></td>
              <td><input type="text" class="form-control" name="nim" id="nim" required
                  placeholder="Masukkan Nim" value="<?= $nim ?>"></td>
            </tr>
            <tr>
              <td><label for="nama">Nama Lengkap</label></td>
              <td><input type="text" class="form-control" name="nama" id="nama" required
                  placeholder="Masukkan Nama Lengkap" value="<?= $nama ?>"></td>
            </tr>
            <tr>
              <td><label for="jk">Jenis Kelamin</label></td>
              <td>
                <input class="form-check-input" type="radio" name="jk" id="jkp" value="perempuan" required <?php if ($jk == 'perempuan') {
                                                                                                              echo 'checked';
                                                                                                            } ?>><label for="jkp">Perempuan</label>
                <input class="form-check-input" type="radio" name="jk" id="jkl" value="laki-laki" required <?php if ($jk == 'laki-laki') {
                                                                                                              echo 'checked';
                                                                                                            } ?>><label for="jkl">Laki-Laki</label>
              </td>
            </tr>
            <tr>
              <td><label for="alamat">Alamat</label></td>
              <td><textarea name="alamat" class="form-control" id="alamat"
                  required><?= $alamat ?></textarea>
              </td>
            </tr>
            <tr>
              <td><label for="prodi">Program Studi</label></td>
              <td>
                <select name="prodi" id="prodi" class="form-control">
                  <option value="">--- Pilih Program Studi ---</option>
                  <option value="1" <?php if ($prodi == 1) {
                                      echo 'selected';
                                    } ?>>Informatika</option>
                  <option value="2" <?php if ($prodi == 2) {
                                      echo 'selected';
                                    } ?>>Arsitektur</option>
                  <option value="3" <?php if ($prodi == 3) {
                                      echo 'selected';
                                    } ?>>Ilmu Lingkungan</option>
                </select>
              </td>
            </tr>
            <tr>
              <td colspan="2"><input class="btn btn-success" type="submit" name="simpan"
                  value="Simpan"></td>
            </tr>
          </table>
        </form>
      </div>
    </div>
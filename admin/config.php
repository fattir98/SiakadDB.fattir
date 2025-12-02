<a href="?p=add-mhs" class="btn btn-success">+ Mahasiswa</a>

<table class="table">
  <tr>
    <th>No</th><th>NIM</th><th>Nama</th><th>Prodi</th><th>Gender</th><th>Ops</th>
  </tr>

  <?php  
  include 'config.php';
  $no = 1;
  $data = mysqli_query($conn, "SELECT * FROM mahasiswa");
  while($d = mysqli_fetch_array($data)){
  ?>
  <tr>
    <td><?= $no++; ?></td>
    <td><?= $d['nim']; ?></td>
    <td><?= $d['nama']; ?></td>
    <td><?= $d['prodi']; ?></td>
    
      <a class="btn btn-primary">Detail</a>
      <a class="btn btn-warning">Edit</a>
      <a class="btn btn-danger">Delete</a>
    </td>
  </tr>
  <?php } ?>
</table>

<?php
//proteksi agar file tidak dapat diakses langsung
if(!defined('MY_APP')) {
    die('Akses langsung tidak diperbolehkan!');
}

$sql = 'SELECT * FROM kategori ORDER BY id_kategori DESC';

$result = $mysqli->query($sql);
if(!$result) {
  die("QUERY Error: " . $mysqli->error);
}
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Kategori</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Daftar Kategori</li>
    </ol>
    <div class="card mb-4">
        
        <div class="card-body">
           <a href="index.php?hal=tambah-kategori" class="btn btn-primary mb-3">Tambah Kategori</a>
        <table class="table table-striped">
            <!-- ini untuk heading table -->
            <thead>
            <tr>
                <!-- penamaan row sesuai urutan dan nama harus sama dengan column -->
            <th scope="col">No</th>
            <th scope="col">Nama Kategori</th>
            <th scope="col">Action</th>
            </tr>
            </thead>
            <!-- ini isi buku -->
             <tbody>
                <!-- disini akan diulang -->
                 <?php $no = 1 ?>
                 <?php while ($row = $result->fetch_assoc()) : ?>
                <!-- karena yang akan kita ulangi adalah daftar buku -->
                <tr>
                  <td><?php echo $no ?></td>
                  <td><?php echo $row['nama_kategori'] ?></td>
                  <td>
                    <a href="index.php?hal=ubah-kategori&id=<?php echo $row['id_kategori'] ?>"  class="btn btn-warning btn-sm">Ubah</a>                  
                  </td>
                </tr>
                <?php $no++ ?>
                <?php endwhile ?>
                    
            </tbody>
        </table>
        </div>
    </div>
</div>

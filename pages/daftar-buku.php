<?php //58:18 eleraning
//proteksi agar file tidak dapat diakses langsung
if(!defined('MY_APP')) {
    die('Akses langsung tidak diperbolehkan!');
}

//ini query buku
$sql = 'SELECT * FROM buku ORDER BY id_buku DESC';

$result = $mysqli->query($sql);
if(!$result) {
  die("QUERY Error: " . $mysqli->error);
}

//kita butuh query untuk kategori, karena 1 buku bisa banyak kategori
$kategori_per_buku = []; //deklarasi array
$sql_kategori = "SELECT bk.id_buku, kb.nama_kategori FROM buku_kategori bk JOIN kategori kb ON bk.id_kategori = kb.id_kategori";

$result_kategori = $mysqli->query($sql_kategori);
if($result_kategori){
  while($row = $result_kategori->fetch_assoc()) {
    $kategori_per_buku[$row['id_buku']][] = $row['nama_kategori'];
  }
}
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Buku</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Daftar Buku</li>
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
            <th scope="col">Judul</th>
            <th scope="col">Kategori</th>
            <th scope="col">Penulis</th>
            <th scope="col">Penerbit</th>
            <th scope="col">Tahun</th>
            <th scope="col">Stok</th>
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
                  <td>

                    <!-- disini ada kondisi jika cover tidak ada -->
                    <div class="d-flex align-items-center">
                       <?php if(!empty($row['cover_buku'])) : ?>
                        <img src="uploads/buku/<?php echo $row['cover_buku'] ?>" alt="Cover Buku" width="50" height="70" style="object-fit: cover;border-radius: 5px; margin-right: 10px" />
                        <?php else : ?>
                          <!-- jika tidak ada cover, tampilkan cover kosong -->
                           <div style="width: 50px; height: 70px; backgprund: #ddd; border-radius: 5px; margin-right: 10px; display: flex; align-items: center; justift-content: center; color: #ddd; ">No <br></div>
                        <?php endif ?>
                        <div>
                          <?php echo $row['judul'] ?>
                        </div>
                      </div>
                  </td>
                  <td>
                    <?php if(isset($kategori_per_buku[$row['id_buku']])){
                      echo implode(', ', $kategori_per_buku[$row['id_buku']]);
                    } else {
                      echo '<em>Tidak ada kategori</em>';
                    }
                    ?>
                  </td>
                  
                  <td><?php echo $row['penulis']?></td>
                  <td><?php echo $row['penerbit']?></td>
                  <td><?php echo $row['tahun_terbit']?></td>
                  <td><?php echo $row['stok']?></td>
                  
                  <td>
                    <a href="index.php?hal=ubah-buku&id=<?php echo $row['id_buku'] ?>"  class="btn btn-warning btn-sm">Ubah</a>                  
                  </td>
                </tr>
                <?php $no++ ?>
                <?php endwhile;
                 $mysqli->close();
                  ?>
                
                    
            </tbody>
        </table>
        </div>
    </div>
</div>

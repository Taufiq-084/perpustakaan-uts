<?php //58:18 eleraning
//proteksi agar file tidak dapat diakses langsung
if(!defined('MY_APP')) {
    die('Akses langsung tidak diperbolehkan!');
}

//ini query buku
$sql = 'SELECT * FROM anggota ORDER BY id_anggota DESC';

$result = $mysqli->query($sql);
if(!$result) {
  die("QUERY Error: " . $mysqli->error);
}



?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Anggota</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Daftar Anggota</li>
    </ol>
    <div class="card mb-4">
        
        <div class="card-body">
           <a href="index.php?hal=tambah-anggota" class="btn btn-primary mb-3">Tambah Anggota</a>
        <table class="table table-striped">
            <!-- ini untuk heading table -->
            <thead>
            <tr>
                <!-- penamaan row sesuai urutan dan nama harus sama dengan column -->
            <th scope="col">No</th>
            <th scope="col">Nama</th>
            <th scope="col">Email</th>
            <th scope="col">Alamat</th>
            <th scope="col">No Telepon</th>
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
                       <?php if(!empty($row['foto_profil'])) : ?>
                        <img src="uploads/users/<?php echo $row['foto_profil'] ?>" alt="Cover Buku" width="60" height="80" style="object-fit: cover;border-radius: 5px; margin-right: 10px" />
                        <?php else : ?>
                          <!-- jika tidak ada cover, tampilkan cover kosong -->
                           <div style="width: 50px; height: 80px; backgprund: #ddd; border-radius: 5px; margin-right: 10px; display: flex; align-items: center; justift-content: center; color: #ddd; ">No <br></div>
                        <?php endif ?>
                        <div>
                          <?php echo $row['nama_lengkap'] ?>
                        </div>
                      </div>
                  </td>
                  <td><?php echo $row['email']?></td>
                  <td><?php echo $row['alamat']?></td>
                  <td><?php echo $row['no_telepon']?></td>
                  
                  <td>
                    <a href="index.php?hal=ubah-password&id_anggota=<?php echo $row['id_anggota'] ?>"  class="btn btn-primary btn-sm"><span class="fas fa-key me-1"></span>Ubah</a>                  
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

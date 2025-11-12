<?php
//proteksi agar file tidak dapat diakses langsung
if(!defined('MY_APP')) {
    die('Akses langsung tidak diperbolehkan!');
}

if (isset($_GET['id_anggota']) && !empty($_GET['id_anggota'])) {
    $id_anggota = $_GET['id_anggota'];
    $sql = "SELECT * FROM anggota WHERE id_anggota = ?";
    if($stmt = $mysqli->prepare($sql)) {
      $stmt->bind_param("i", $id_anggota);
      if ($stmt->execute()) {
        $result = $stmt->get_result();
        if($result->num_rows == 1) {
          $anggota = $result->fetch_assoc();
        } else {
          echo "Data Anggota tidak ditemukan";
          exit();
        }
        $stmt->close();
      }
    } else {
      echo "Query gagal";
      exit();
    }
} else{
  echo "anggota tidak ditemukan";
  exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  //jika ada method post jalankan blok ini
  $password = md5($_POST['password']);
  //query
  $sql = "UPDATE anggota SET password = ? WHERE id_anggota = ?";
  if ($stmt = $mysqli->prepare($sql)) {
      $stmt->bind_param("si", $password, $id_anggota);
    if($stmt->execute()) {
      $pesan = "Password User <b>". $anggota['nama_lengkap'] ." </b>berhasil di ubah";
      
    } else {
      $pesan_error = "Terjadi Kesalahan saat menyimpan data" . $stmt->error;
    }
    $stmt->close();
  }
  $mysqli->close();
}
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Anggota</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Ubah Password</li>
    </ol>
    <?php if(!empty($pesan)) : ?>
      <div class="alert alert-success" role="alert">
          <?php echo $pesan ?>
      </div>
      <?php endif ?>
    
      <?php if(!empty($pesan_error)) : ?>
      <div class="alert alert-danger" role="alert">
          <?php echo $pesan_error ?>
      </div>
      <?php endif ?>
    <div class="card mb-4">
        
        <div class="card-body">
          
            <form method="post">
            <div class="mb-3">
                <label for="nama_lengkap" class="form-label">Nama Anggota</label>
                <input type="text" class="form-control" id="nama_lengkap" value="<?php echo $anggota['nama_lengkap'] ?>" disabled readonly>
            </div>
            <div class="mb-3">
                <label for="Email" class="form-label">Email</label>
                <input type="text" class="form-control" id="Email" value="<?php echo $anggota['email'] ?>" disabled readonly>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Masukan Password Baru</label>
                <input type="password" class="form-control" name="password" id="password"  required>
            </div>
        
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="index.php?hal=daftar-kategori" class="btn btn-danger">Kembali</a>
        </form>
        </div>
    </div>
</div>

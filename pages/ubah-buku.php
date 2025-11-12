<?php //1:20:45 eleraning buku 6
//proteksi agar file tidak dapat diakses langsung
if(!defined('MY_APP')) {
    die('Akses langsung tidak diperbolehkan!');
}

if(isset($_GET['id']) && !empty($_GET['id'])) {
  $id = $_GET['id'];
  $sql = "SELECT * FROM buku WHERE id_buku = ?";
  if($stmt = $mysqli->prepare($sql)){
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
      $result = $stmt->get_result();
      if($result->num_rows == 1) {
        $buku = $result->fetch_assoc();
      } else {
        echo "Data buku tidak ditemukan";
        exit();
      }
    } else {
      echo "Error";
      exit();
    }
    $stmt->close();
  }
} else {
  echo "ID buku tidak boleh kosong";
  exit();
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
  //jika ada method post jalankan blok ini
  $judul = $_POST['judul_buku'];
  $penulis = $_POST['penulis'];
  $penerbit = $_POST['penerbit'];
  $tahun_terbit = $_POST['tahun_terbit'];
  $stok = $_POST['stok'];

  //proses uploud cover
  $cover_name = $buku['cover_buku'];
  if(!empty($_FILES['cover']['name'])){
    $target_dir = "uploads/buku/"; //buat directory baru
    $file_name = time() . "_" . basename($_FILES['cover']['name']);//menambahkan time agar tidak ada yang bentrok ketika nama file sama
    $target_file = $target_dir . $file_name;

    //proses upload
    if(move_uploaded_file($_FILES['cover']['tmp_name'], $target_file)){
      $cover_name = $file_name;
      //tambahkan hapus cover lama
      if(!empty($buku['cover_buku']) && file_exists($target_dir . $buku['cover_buku'])) {
        unlink($target_dir . $buku['cover_buku']);
      }
    }
  }
  
  //prosesa masuk database
  $sql = "UPDATE buku SET judul = ?, penulis = ?, penerbit = ?, tahun_terbit = ?, stok = ?, cover_buku = ? WHERE id_buku = ?";
  if($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("sssiisi", $judul, $penulis, $penerbit, $tahun_terbit, $stok, $cover_name, $id);
    //kalau ini berhasil di execute
    if ($stmt->execute()) {
      // hapus dulu relasi kategori lama
      $mysqli->query("DELETE FROM buku_kategori WHERE  id_buku = $id");
      if(!empty($_POST['kategori'])) {
        $stmt_kat = $mysqli->prepare("INSERT INTO buku_kategori (id_buku, id_kategori) VALUES (?,?)");
        foreach ($_POST['kategori'] as $id_kategori) {
          $stmt_kat->bind_param("ii", $id, $id_kategori);
          $stmt_kat->execute();
        }
        $stmt_kat->close();
      }
      $pesan = "Data buku berhasil diperbaharui";
      //refresh data buku agar [erubahan dapat langsung ditampilkan
      $result_buku = $mysqli->query("SELECT * FROM buku WHERE id_buku = $id");
      $buku = $result_buku->fetch_assoc();
    } else {
      $pesan_error = "Gagal memperbaharui buku";
    }
    $stmt->close();
 } else {
  $pesan_error = "Kesalahan dalam query update";
 }
}
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Buku</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Tambah Buku</li>
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
          
            <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="judul_buku" class="form-label">Judul Buku</label>
                <input type="text" name="judul_buku" class="form-control" id="judul_buku"  value="<?php echo $buku['judul']?>"required>
            </div>
            <!-- kategori disini -->
            <div class="mb-3">
                <label for="kategori" class="form-label">Pilih Kategori</label><br>
                <?php
                $sql = "SELECT * FROM kategori ORDER BY nama_kategori ASC";
                $result_kategori = $mysqli->query($sql);
                
                //ambil kategori yang sudah dimiliki buku ini
                $kategori_buku = [];
                $sql_kategori = "SELECT id_kategori FROM buku_kategori where id_buku = ?";
                if($stmt_kategori = $mysqli->prepare($sql_kategori)) {
                  $stmt_kategori->bind_param("i", $id);
                  $stmt_kategori->execute();
                  $result_buku_kategori = $stmt_kategori->get_result();
                  while ($row_kategori = $result_buku_kategori->fetch_assoc()) {
                    $kategori_buku[] = $row_kategori['id_kategori'];
                  }
                  $stmt_kategori->close();
                }
                ?> 
                <?php while ($kat = $result_kategori->fetch_assoc()) : ?>
                <label class="me-3">
                 <input type="checkbox" name='kategori[]' value="<?php echo $kat['id_kategori'] ?>" <?php
                 echo in_array($kat['id_kategori'], $kategori_buku) ? 'checked' : '';?>>
                 <?php echo $kat['nama_kategori'] ?>
                   
                 </label>
                 <?php endwhile ?>
                </div>
            <div class="mb-3">
                <label for="penulis" class="form-label">Penulis</label>
                <input type="text" name="penulis" class="form-control" id="penulis" value="<?php echo $buku['penulis'] ?>"required>
            </div>
            <div class="mb-3">
                <label for="penerbit" class="form-label">Penerbit</label>
                <input type="text" name="penerbit" class="form-control" id="penerbit" value="<?php echo $buku['penerbit'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
                <input type="number" name="tahun_terbit" class="form-control" id="tahun_terbit" value="<?php echo $buku['tahun_terbit'] ?>"required>
            </div>
            <div class="mb-3">
                <label for="stok" class="form-label">Stok</label>
                <input type="number" name="stok" class="form-control" id="stok" value="<?php echo $buku['stok'] ?>" required>
            </div>
            <!-- ini untuk uploud cover -->
            <!-- tampilakn cover -->
            <div class="mb-3">
              <img src="uploads/buku/<?php echo $buku['cover_buku']?>" widht="100" height="140">
                </div>
                <div class="mb-4">
                <label for="cover" class="form-label">Uploud Cover</label>
                <input type="file" name="cover" class="form-control" id="cover" >
            </div>
            
        
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="index.php?hal=daftar-buku" class="btn btn-danger">Kembali</a>
        </form>
        </div>
    </div>
</div>

<?php
//proteksi agar file tidak dapat diakses langsung
if(!defined('MY_APP')) {
    die('Akses langsung tidak diperbolehkan!');
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
  //jika ada method post jalankan blok ini
  $judul = $_POST['judul_buku'];
  $penulis = $_POST['penulis'];
  $penerbit = $_POST['penerbit'];
  $tahun_terbit = $_POST['tahun_terbit'];
  $stok = $_POST['stok'];

  //proses uploud cover
  $cover_name = null;
  if(!empty($_FILES['cover']['name'])){
    $target_dir = "uploads/buku/"; //buat directory baru
    $file_name = time() . "_" . basename($_FILES['cover']['name']);//menambahkan time agar tidak ada yang bentrok ketika nama file sama
    $target_file = $target_dir . $file_name;

    //proses upload
    if(move_uploaded_file($_FILES['cover']['tmp_name'], $target_file)){
      $cover_name = $file_name;
    }
  }
  
  //prosesa masuk database
  $sql = "INSERT INTO buku (judul, penulis, penerbit, tahun_terbit, stok, cover_buku ) VALUES (?, ?, ?, ?, ?, ?)";
  if($stmt = $mysqli->prepare($sql)){
    $stmt->bind_param("sssiis", $judul, $penulis, $penerbit, $tahun_terbit, $stok, $cover_name);
    if($stmt->execute()) {
        $id_buku = $stmt->insert_id;// ini untuk mengambil id_buku (auto_incerment) dari hasil input
        //ini proses kategori
        if(!empty($_POST['kategori'])){
            //kita pakai perulangan, karena yang dikirimkan adalah format array kategori[];
            foreach ($_POST['kategori'] as $id_kategori) {
                $mysqli->query("INSERT INTO buku_kategori(id_buku, id_kategori) VALUES ($id_buku, $id_kategori)");
            }
        }
        $pesan = "Buku Berhasil di Tambahkan";
    } else {
        $pesan_error = "Gagal Menambahkan Buku";
    }
    $stmt->close();
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
                <input type="text" name="judul_buku" class="form-control" id="judul_buku" required>
            </div>
            <!-- kategori disini -->
            <div class="mb-3">
                <label for="kategori" class="form-label">Pilih Kategori</label><br>
                <!-- disini kita menggunakan checkbox karena 1 buku bisa memiliki lebih dari 1 kategori -->
                <!-- praktenya bisa menggunakan select --> 
                <label class="me-3">
                  <!-- akan ada perulangan disini -->
                   <?php // query kategori 
                    $sql_kategori = "SELECT * FROM kategori ORDER BY nama_kategori ASC";
                    $result_kategori = $mysqli->query($sql_kategori);
                    ?>

                    <?php while($kat = $result_kategori->fetch_assoc()) : ?>
                 <input type="checkbox" name="kategori[]" value="<?php echo $kat['id_kategori']?>"><?php echo $kat ['nama_kategori']?></input>
                 </label>
                      <?php endwhile;
                      $mysqli->close(); ?>
                </div>
            <div class="mb-3">
                <label for="penulis" class="form-label">Penulis</label>
                <input type="text" name="penulis" class="form-control" id="penulis" required>
            </div>
            <div class="mb-3">
                <label for="penerbit" class="form-label">Penerbit</label>
                <input type="text" name="penerbit" class="form-control" id="penerbit" required>
            </div>
            <div class="mb-3">
                <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
                <input type="number" name="tahun_terbit" class="form-control" id="tahun_terbit" required>
            </div>
            <div class="mb-3">
                <label for="stok" class="form-label">Stok</label>
                <input type="number" name="stok" class="form-control" id="stok" required>
            </div>
            <!-- ini untuk uploud cover -->
            <div class="mb-3">
                <label for="cover" class="form-label">Uploud Cover</label>
                <input type="file" name="cover" class="form-control" id="cover" >
            </div>
            
        
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="index.php?hal=daftar-buku" class="btn btn-danger">Kembali</a>
        </form>
        </div>
    </div>
</div>

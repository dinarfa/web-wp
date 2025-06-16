<?php
include 'koneksi.php';

// Ambil bobot dari form POST
$bobotInput = $_POST['bobot'];

// 1. Normalisasi Bobot Kriteria
$totalBobot = array_sum($bobotInput);
$bobot = [];
foreach ($bobotInput as $id_kriteria => $b) {
    $bobot[$id_kriteria] = $b / $totalBobot;
}

// 2. Ambil jenis kriteria (benefit / cost) dari tabel kriteria
$jenis_kriteria = [];
$q_kriteria = mysqli_query($conn, "SELECT id_kriteria, jenis FROM kriteria");
while ($row = mysqli_fetch_assoc($q_kriteria)) {
    $jenis_kriteria[$row['id_kriteria']] = $row['jenis'];
}

// 3. Ambil semua laptop beserta nilai kriterianya
$alternatif = [];
$q_laptop = mysqli_query($conn, "SELECT * FROM laptop");
while ($l = mysqli_fetch_assoc($q_laptop)) {
    $id_laptop = $l['id_laptop'];
    $nama_laptop = $l['nama_laptop'];

    // Ambil semua nilai tiap kriteria untuk laptop ini dari tabel nilai_laptop
    $nilai = [];
    $q_nilai = mysqli_query($conn, "SELECT id_kriteria, skor FROM nilai_laptop WHERE id_laptop = $id_laptop");
    while ($n = mysqli_fetch_assoc($q_nilai)) {
        $nilai[$n['id_kriteria']] = $n['skor'];
    }

    // Simpan ke array alternatif
    $alternatif[] = [
        'id' => $id_laptop,
        'nama' => $nama_laptop,
        'nilai' => $nilai
    ];
}

// 4. Hitung Skor WP untuk setiap alternatif
$hasil = [];
foreach ($alternatif as $alt) {
    $log_skor = 0;
    foreach ($alt['nilai'] as $id_kriteria => $v) {
        if (isset($bobot[$id_kriteria]) && isset($jenis_kriteria[$id_kriteria])) {
            $w = $jenis_kriteria[$id_kriteria] == 'cost' ? -$bobot[$id_kriteria] : $bobot[$id_kriteria];
            $log_skor += $w * log($v);
        }
    }
    $skor = exp($log_skor);
    $hasil[] = [
        'nama' => $alt['nama'],
        'skor' => $skor
    ];
}

// 5. Normalisasi Skor Total
$totalSkor = array_sum(array_column($hasil, 'skor'));
foreach ($hasil as $i => $h) {
    $hasil[$i]['skor_normal'] = $h['skor'] / $totalSkor;
}

// 6. Urutkan Berdasarkan Skor Tertinggi
usort($hasil, function ($a, $b) {
    return $b['skor_normal'] <=> $a['skor_normal'];
});
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hasil Rekomendasi - SPK Laptop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <style>
        /* html, body {
  height: 100%;
  margin: 0;
} */


body {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

main {
  flex: 1;
}
        table{
  width:100%;
  table-layout: fixed;
}
.tbl-header{
  background-color: rgba(255,255,255,0.3);
 }
.tbl-content{
  height:300px;
  overflow-x:auto;
  margin-top: 0px;
  border: 1px solid rgba(255,255,255,0.3);
}
th{
  padding: 20px 15px;
  text-align: center;
  font-weight: 500;
  font-size: 15px;
  color: #fff;
  text-transform: uppercase;
  white-space: normal;            /* boleh wrapping */
  overflow-wrap: break-word;      /* pecah kata panjang */
  word-break: keep-all;           /* jaga kata biar gak pecah huruf */
  min-width: 100px;  
}
td{
  padding: 15px;
  text-align: center;
  vertical-align:middle;
  font-weight: 300;
  font-size: 15px;
  color: #fff;
  border-bottom: solid 1px rgba(255,255,255,0.1);
}
@import url(https://fonts.googleapis.com/css?family=Roboto:400,500,300,700);
body{
  background: -webkit-linear-gradient(left,rgba(150, 93, 233, 1) 10.8%, rgba(99, 88, 238, 1) 94.3%);
  background: linear-gradient(
    82.3deg,
    rgba(150, 93, 233, 1) 10.8%,
    rgba(99, 88, 238, 1) 94.3%
  );
  font-family: 'Roboto', sans-serif;
}
section{
  margin: 50px;
}


/* follow me template */
.made-with-love {
  margin-top: 40px;
  padding: 10px;
  clear: left;
  text-align: center;
  font-size: 10px;
  font-family: arial;
  color: #fff;
}
.made-with-love i {
  font-style: normal;
  color: #F50057;
  font-size: 14px;
  position: relative;
  top: 2px;
}
.made-with-love a {
  color: #fff;
  text-decoration: none;
}
.made-with-love a:hover {
  text-decoration: underline;
}


/* for custom scrollbar for webkit browser*/

::-webkit-scrollbar {
    width: 6px;
} 
::-webkit-scrollbar-track {
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3); 
} 
::-webkit-scrollbar-thumb {
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3); 
}


    </style>
</head>
<body>
  <!-- Navbar -->
  <header class="fixed-top">
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top px-4" id="mainNavbar">
      <a class="navbar-brand" href="index.html">Evaleon.id</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link" href="index.html">Beranda</a></li>
            <li class="nav-item"><a class="nav-link" href="tentang.html">Tentang</a></li>
            <li class="nav-item"><a class="nav-link" href="#fitur">Fitur</a></li>
            <li class="nav-item"><a class="nav-link active" href="#rekomendasi">Rekomendasi</a></li>
        </ul>
      </div>
    </nav>
  </header>

  <main>


  <div class="container mt-5 pt-5 text-white">
    <div class="text-center my-5">
      <h1 class="fw-bold">Hasil Rekomendasi Laptop</h1>
      <p class="text-light">Berikut adalah hasil perhitungan berdasarkan metode <strong>Weighted Product</strong></p>
    </div>

<div class="d-flex justify-content-center">
  <div class="row g-3 mb-4 justify-content-center">
    <?php
    $icons = ['fa-crown text-warning', 'fa-star text-info', 'fa-medal text-light'];

    // Simpan semua spesifikasi ke array berdasarkan nama_laptop
    $spesifikasi_map = [];
    $sql = mysqli_query($conn, "
      SELECT 
        l.nama_laptop,
        s.prosesor,
        s.ram,
        s.penyimpanan,  
        s.baterai,
        s.harga
      FROM laptop l
      JOIN spesifikasi_laptop s ON l.id_laptop = s.id_laptop
    ");
    while ($row = mysqli_fetch_assoc($sql)) {
      $spesifikasi_map[$row['nama_laptop']] = $row;
    }

    for ($i = 0; $i < 3 && $i < count($hasil); $i++) {
      $h = $hasil[$i];
      $nama = $h['nama'];
      $spes = $spesifikasi_map[$nama];

      echo '<div class="col-md-4 col-sm-6 mb-3">
              <div class="card h-100 text-white bg-dark border-light shadow-sm">
                <div class="card-body d-flex flex-column justify-content-between" style="min-height: 300px; font-size: 0.9rem;">
                  <div>
                    <h6 class="card-title mb-2"><i class="fas ' . $icons[$i] . '"></i> Rekomendasi #' . ($i + 1) . '</h6>
                    <h5 class="fw-semibold">' . $nama . '</h5>
                    <p class="mb-1">Prosesor: ' . $spes['prosesor'] . '</p>
                    <p class="mb-1">RAM: ' . $spes['ram'] . '</p>
                    <p class="mb-1">Penyimpanan: ' . $spes['penyimpanan'] . '</p>
                    <p class="mb-1">Baterai: ' . $spes['baterai'] . '</p>
                    <p class="mb-2">Harga: Rp ' . $spes['harga'] . '</p>
                  </div>
                  <div>
                    <span class="badge bg-success">Skor: ' . number_format($h['skor_normal'], 3) . '</span>
                  </div>
                </div>
              </div>
            </div>';
    }
    ?>
  </div>
</div>


<!-- Tabel Hasil Ranking -->
<section> <!-- Tambah margin bawah -->
  <div class="tbl-header table-responsive">
    <table cellpadding="0" cellspacing="0" border="0">
      <thead>
        <tr>
          <th>Peringkat</th>
          <th>Nama Laptop</th>
          <th>Skor Akhir</th>
        </tr>
      </thead>
    </table>
  </div>

  <!-- Tambahkan margin bawah langsung ke tbl-content -->
  <div class="tbl-content">
    <table class="text-center" cellpadding="0" cellspacing="0" border="0">
      <tbody>
        <?php
        $rank = 1;
        foreach ($hasil as $h) {
          echo '<tr>
                  <td>' . $rank . '</td>
                  <td>' . $h['nama'] . '</td>
                  <td>' . number_format($h['skor_normal'], 3) . '</td>
                </tr>';
          $rank++;
        }
        ?>
      </tbody>
    </table>
  </div>
</section>

<!-- Tombol ulangi -->
<div class="d-grid justify-content-center mt-4">
  <form action="rekomendasi.php" method="post">
    <button type="submit" class="button text-white">
      <span class="button-content">Ulangi Pemilihan</span>
    </button>
  </form>
</div>
  </main>

  <!-- Footer -->
  <footer class="bg-dark text-white text-center py-4 mt-auto w-100">
    <div class="container-fluid">
      <p class="mb-2">© 2025 Evaleon.id. Dibuat dengan hati yang tulus menggunakan metode WP.</p>
      <div class="d-flex justify-content-center gap-3">
        <a href="#" class="text-white"><i class="fab fa-facebook"></i></a>
        <a href="#" class="text-white"><i class="fab fa-twitter"></i></a>
        <a href="https://www.instagram.com/dinar_fz?igsh=MTdubXM1ZzV0czV3bw==" class="text-white"><i class="fab fa-instagram"></i></a>
        <a href="#" class="text-white"><i class="fab fa-linkedin"></i></a>
      </div>
    </div>
  </footer>

  <script>
  window.addEventListener("scroll", function () {
    const navbar = document.getElementById("mainNavbar");
    if (window.scrollY > 10) {
      navbar.classList.add("scrolled");
    } else {
      navbar.classList.remove("scrolled");
    }
  });
</script>

</body>
</html>

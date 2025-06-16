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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
</head>
<body>
  <!-- Navbar -->
  <header class="fixed-top">
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top px-4" id="mainNavbar">
      <a class="navbar-brand" href="#">Evaleon.id</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link" href="#">Beranda</a></li>
            <li class="nav-item"><a class="nav-link" href="#tentang">Tentang</a></li>
            <li class="nav-item"><a class="nav-link" href="#fitur">Fitur</a></li>
            <li class="nav-item"><a class="nav-link active" href="#rekomendasi">Rekomendasi</a></li>
        </ul>
      </div>
    </nav>
  </header>

  <div class="container mt-5 pt-5 text-white">
    <div class="text-center my-5">
      <h1 class="fw-bold">Hasil Rekomendasi Laptop</h1>
      <p class="text-light">Berikut adalah hasil perhitungan berdasarkan metode <strong>Weighted Product</strong></p>
    </div>

    <!-- Top 3 Card -->
    <div class="row g-4 mb-5">
      <?php
      $icons = ['fa-crown text-warning', 'fa-star text-info', 'fa-medal text-light'];
      for ($i = 0; $i < 3 && $i < count($hasil); $i++) {
        $h = $hasil[$i];
        echo '<div class="col-md-4">
                <div class="card h-100 text-white bg-dark border-light shadow-lg">
                  <div class="card-body">
                    <h5 class="card-title"><i class="fas ' . $icons[$i] . '"></i> Rekomendasi #' . ($i + 1) . '</h5>
                    <h4 class="fw-bold">' . $h['nama'] . '</h4>
                    <p class="card-text">Spesifikasi tidak tersedia</p>
                    <span class="badge bg-success">Skor: ' . number_format($h['skor_normal'], 3) . '</span>
                  </div>
                </div>
              </div>';
      }
      ?>
    </div>
    
    <!-- Tabel Hasil Ranking -->
    <div class="p-4 rounded shadow mb-5" style="backdrop-filter: blur(20px); background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255,255,255,0.3);">
      <h5 class="text-white mb-3"><i class="fas fa-chart-line"></i> Ranking Alternatif Laptop</h5>
      <div class="table-responsive text-white">
        <table class="table table-striped table-hover text-white text-center">
          <thead style="background: rgba(255,255,255,0.2);">
            <tr>
              <th>Peringkat</th>
              <th>Nama Laptop</th>
              <th>Skor Akhir</th>
            </tr>
          </thead>
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
    </div>

    <div class="text-center mb-5">
      <a href="rekomendasi.html" class="btn btn-outline-light">🔁 Ulangi Pemilihan</a>
    </div>
  </div>

  <footer class="text-center mt-5">
    <div class="container">
      <p>© 2025 Evaleon.id. Dibuat dengan ❤️ menggunakan metode WP.</p>
    </div>
  </footer>
</body>
</html>

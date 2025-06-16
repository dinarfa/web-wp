<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Rekomendasi Laptop - SPK</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
  <style>
    /* From Uiverse.io by Madflows */ 
.button {
  position: relative;
  overflow: hidden;
  height: 3rem;
  padding: 0 2rem;
  border-radius: 1.5rem;
  background: #3d3a4e;
  background-size: 400%;
  color: #fff;
  border: none;
  cursor: pointer;
}

.button:hover::before {
  transform: scaleX(1);
}

.button-content {
  position: relative;
  z-index: 1;
}

.button::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  transform: scaleX(0);
  transform-origin: 0 50%;
  width: 100%;
  height: inherit;
  border-radius: inherit;
  background: linear-gradient(
    82.3deg,
    rgba(150, 93, 233, 1) 10.8%,
    rgba(99, 88, 238, 1) 94.3%
  );
  transition: all 0.475s;
}

h1{
  font-size: 30px;
  color: #fff;
  text-transform: uppercase;
  font-weight: 300;
  text-align: center;
  margin-bottom: 15px;
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
  /* word-break: keep-all;           jaga kata biar gak pecah huruf */
  min-width: 100px;  
    max-width: 200px;
  text-overflow: ellipsis;
      hyphens: auto;
      word-break: break-word;
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
/* RESPONSIVE FONT SIZE */
/* RESPONSIVE - mengecilkan font dan padding di layar kecil */
@media (max-width: 768px) {
  th, td {
      max-width: 200px;
  text-overflow: ellipsis;
    font-size: 12px;
    padding: 8px;
    hyphens: auto;
      white-space: normal;           /* izinkan wrapping */
  word-break: normal;            /* JANGAN pecah per huruf */
  min-width: 100px;
        word-break: break-word;
  }

  .tbl-content {
    max-height: 200px;
  }
}

/* @media (max-width: 480px) {
  th, td {
    font-size: 10px;
    padding: 6px;
  }

  .tbl-content {
    max-height: 180px;
  }
} */
/* demo styles */

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
<body class="d-flex flex-column min-vh-100 bg-dark text-white">
  

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

  <!-- Konten Utama -->
  <main class="container mt-5 pt-5 flex-grow-1">
    <div class="text-center my-5">
      <h1 class="fw-bold">Sistem Pendukung Keputusan</h1>
      <p class="text-light">Pemilihan Laptop Terbaik dengan Metode <strong>Weighted Product</strong></p>
    </div>

    <!-- Data Alternatif -->
<section>
  <div class="tbl-header table-responsive">
    <table cellpadding="0" cellspacing="0" border="0">
      <thead>
        <tr>
            <th>Nama Laptop</th>
            <th>Processor</th>
            <th>RAM</th>
            <th>Penyimpanan</th>
            <th>Baterai</th>
            <th>Harga</th>
        </tr>
      </thead>
    </table>
  </div>
  <div class="tbl-content">
    <div style="min-width: 400px; overflow-x: auto;">
    <table class="text-center"  cellpadding="0" cellspacing="0" border="0">
      <tbody>
            <?php
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

            while ($data = mysqli_fetch_assoc($sql)) {
              echo "<tr>
                      <td>{$data['nama_laptop']}</td>
                      <td>{$data['prosesor']}</td>
                      <td>{$data['ram']}</td>
                      <td>{$data['penyimpanan']}</td>
                      <td>{$data['baterai']}</td>
                      <td>{$data['harga']}</td>
                    </tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
      </div>
    </div>
</section>

    <!-- Form Input Bobot -->
    <div class="p-4 rounded shadow mb-5" style="backdrop-filter: blur(20px); background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255,255,255,0.3);">
      <form action="proses.php" method="post">
        <h5 class="mb-4"><i class="fas fa-sliders-h"></i> Masukkan Bobot Kriteria (1 - 5)
      <small class="d-block text-white mt-1" style="font-size: 0.9rem;">
    Nilai 1 berarti kurang penting, dan 5 berarti sangat penting.
  </small>
      </h5>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label"><i class="fas fa-microchip"></i> Processor</label>
            <input type="number" name="bobot[1]" min="1" max="5" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label"><i class="fas fa-memory"></i> RAM</label>
            <input type="number" name="bobot[2]" min="1" max="5" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label"><i class="fas fa-hdd"></i> Penyimpanan</label>
            <input type="number" name="bobot[3]" min="1" max="5" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label"><i class="fas fa-battery-three-quarters"></i> Daya Tahan Baterai</label>
            <input type="number" name="bobot[4]" min="1" max="5" class="form-control" required>
          </div>
          <div class="col-md-12">
            <label class="form-label"><i class="fas fa-tags"></i> Harga</label>
            <input type="number" name="bobot[5]" min="1" max="5" class="form-control" required>
          </div>
        </div>
        <div class="d-grid mt-4">
         <button class="button">
          <span class="button-content">Proses Hitung</span>
        </button>
        </div>
      </form>
    </div>
  </main>

  <!-- Footer -->
  <footer class="bg-dark text-white text-center py-4 mt-auto w-100">
    <div class="container">
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

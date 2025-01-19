<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Beranda - Sistem Informasi Keuangan</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="<?=base_url()?>assets/img/favicon.png" rel="icon">
  <link href="<?=base_url()?>assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
   <!-- FullCalendar CSS -->
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@3.3.1/dist/fullcalendar.min.css" rel="stylesheet">

  <!-- FullCalendar JS -->
  <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@3.3.1/dist/fullcalendar.min.js"></script>

  <link href="<?=base_url()?>assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?=base_url()?>assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="<?=base_url()?>assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="<?=base_url()?>assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="<?=base_url()?>assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="<?=base_url()?>assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="<?=base_url()?>assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="<?=base_url()?>assets/css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: NiceAdmin
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Updated: Apr 20 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="index.html" class="logo d-flex align-items-center">
        <img src="<?=base_url()?>assets/img/logo.png" alt="">
        <span class="d-none d-lg-block">KeuanganKu</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="<?=base_url()?>assets/img/profile-img.jpg" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2">Selamat datang <?=$this->fungsi->user_login()->nama?></span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6><?=$this->fungsi->user_login()->nama?></h6>
              <?php
              $user_level = $this->fungsi->user_login()->level;
              ?>
              <span>Sebagai 
                  <?php 
                  if ($user_level == 1) {
                      echo "Admin";
                  } elseif ($user_level == 2) {
                      echo "Bendahara Pengeluaran";
                  } elseif ($user_level == 3) {
                      echo "Bendahara Penerimaan";
                  } else {
                      echo "Pengguna";
                  }
                  ?>
              </span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li>
              <a class="dropdown-item d-flex align-items-center" href="<?=site_url('auth/logout')?>">
                <i class="bi bi-box-arrow-right"></i>
                <span>Keluar</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link collapsed" 
          href="<?php 
                    if ($this->session->userdata('level') == 1) {
                        echo site_url('dashboard1'); // Admin
                    } elseif ($this->session->userdata('level') == 2) {
                        echo site_url('dashboard2'); // Bendahara Pengeluaran
                    } elseif ($this->session->userdata('level') == 3) {
                        echo site_url('dashboard3'); // Bendahara Penerimaan
                    } else {
                        echo site_url('auth/login'); // Default jika level tidak dikenali
                    }
                  ?>">
            <i class="bi bi-grid"></i>
          <span>Beranda</span>
        </a>
      </li>


      <li class="nav-heading">Transaksi</li>
      <?php if($this->session->userdata('level') == 3 || $this->session->userdata('level') == 1) { ?>
      <li class="nav-item">
        <a class="nav-link collapsed" href="<?=site_url('Pendapatan')?>">
          <i class="bi bi-receipt"></i>
          <span>Penerimaan</span>
        </a>
      </li><!-- End Pendapatan Page Nav -->
      <?php } ?>
      <?php if($this->session->userdata('level') == 2 || $this->session->userdata('level') == 1) { ?>
        <li class="nav-item">
          <a class="nav-link collapsed" data-bs-toggle="collapse" href="#pengeluaranDropdown" role="button" aria-expanded="false" aria-controls="pengeluaranDropdown">
            <i class="bi bi-calculator"></i>
            <span>Kas Pengeluaran</span>
          </a>
          <div id="pengeluaranDropdown" class="collapse">
            <ul class="list-unstyled ps-3 mt-1">
              <li>
                <a class="nav-link collapsed" href="<?=site_url('Penarikan')?>">
                  <span>Penarikan</span>
                </a>
              </li>
              <li>
                <a class="nav-link collapsed" href="<?=site_url('Pengeluaran')?>">
                  <span>Pengeluaran</span>
                </a>
              </li>
            </ul>
          </div>
        </li><!-- End Pengeluaran Page Nav -->  
      <?php } ?>
      

      <li class="nav-heading">Pelaporan</li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="<?=site_url('Lap_transaksi')?>">
          <i class="bi bi-journals"></i>
          <span>Laporan Transaksi</span>
        </a>
      </li><!-- End Laporan Transaksi Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="<?=site_url('Lap_pajak')?>">
          <i class="bi bi-wallet"></i>
          <span>Laporan Pajak</span>
        </a>
      </li><!-- End Laporan Pajak Page Nav --> 

      <?php if($this->session->userdata('level') == 1) { ?>
      <li class="nav-heading">Manajemen Pengguna</li>
      
      <li class="nav-item">
        <a class="nav-link collapsed" href="<?=site_url('Pengguna')?>">
          <i class="bi bi-people"></i>
          <span>Pengguna</span>
        </a>
      </li><!-- End Pengguna Page Nav --> 
    <?php } ?>
    </ul>

  </aside><!-- End Sidebar-->

  <main id="main" class="main">

    <?php echo $contents ?>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>NiceAdmin</span></strong>. All Rights Reserved
    </div>
    <div class="credits">
      <!-- All the links in the footer should remain intact. -->
      <!-- You can delete the links only if you purchased the pro version. -->
      <!-- Licensing information: https://bootstrapmade.com/license/ -->
      <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
      Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="<?=base_url()?>assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="<?=base_url()?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?=base_url()?>assets/vendor/chart.js/chart.umd.js"></script>
  <script src="<?=base_url()?>assets/vendor/echarts/echarts.min.js"></script>
  <script src="<?=base_url()?>assets/vendor/quill/quill.js"></script>
  <script src="<?=base_url()?>assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="<?=base_url()?>assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="<?=base_url()?>assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="<?=base_url()?>assets/js/main.js"></script>

</body>

</html>
<div class="pagetitle">
      <h1>Manajemen Pengguna</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item">Data Pengguna</li>
          <li class="breadcrumb-item">Tambah Pengguna</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
<section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Form Tambah Pengguna</h5>
              <div style="display: flex; justify-content: flex-end;">              	
              </div>
              <?php if (validation_errors()): ?>
                  <div class="alert alert-danger">
                      <?= validation_errors() ?>
                  </div>
              <?php endif; ?>
             <form class="row g-3" action="" method="post">
                <div class="col-md-12">
                  <input type="text" name="f_nama" value="<?=set_value('f_nama')?>" class="form-control" placeholder="Nama Lengkap" required>
                </div>
                <div class="col-md-4">
                  <input type="text" name="f_NIP" value="<?=set_value('f_NIP')?>" class="form-control" placeholder="NIP" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                </div>
                <div class="col-md-4">
                  <input type="password" name="f_sandi" class="form-control" placeholder="Sandi" required>
                </div>
                <div class="col-md-4">
                  <input type="password" name="f_konfirmasi" class="form-control" placeholder="Konfirmasi Sandi" required>
                </div>
                <div class="col-md-8">
                  <input type="email" name="f_email" value="<?=set_value('f_email')?>" class="form-control" placeholder="Email" required>
                </div>
                <div class="col-md-4">
                  <select id="inputState" name="f_level" class="form-select" required>
                    <option value="" disabled selected hidden>Pilih Level</option>
                    <option value="1">Admin</option>
                    <option value="2">Bendahara Pengeluaran</option>
                    <option value="3">Bendahara Penerimaan</option>
                  </select>
                </div>
                <div class="text-center">
                  <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-send"></i>  Submit</button>
                  <button type="reset" class="btn btn-secondary btn-sm"><i class="bi bi-x-octagon"></i>  Reset</button>
                  <a href="<?=site_url('Pengguna')?>" class="btn btn-success btn-sm">
                  <i class="bi bi-arrow-counterclockwise"></i>  Kembali
                  </a>
                </div>
            </div>
          </div>
       </div>
    </div>
</section>

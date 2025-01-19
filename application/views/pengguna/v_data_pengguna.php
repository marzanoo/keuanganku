<div class="pagetitle">
  <h1>Manajemen Pengguna</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="#">Home</a></li>
      <li class="breadcrumb-item">Data Pengguna</li>
    </ol>
  </nav>
</div><!-- End Page Title -->
<section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Data Tabel</h5>
        				<p style="text-align: justify;">
        				  Data Pengguna adalah fitur untuk menampilkan informasi lengkap tentang semua pengguna yang terdaftar di sistem. Pada menu ini, anda dapat melihat daftar pengguna beserta NIP, Nama, Email, dan Level serta dapat dengan mudah memantau dan mengelola pengguna sesuai kebutuhan.
        				</p>
              <div>
        				<a href="<?=site_url('Pengguna/tambah')?>" class="btn btn-success btn-sm">
        					<i class="bi bi-person-plus"></i>  Tambah Data
        				</a>              	
              </div>
              <!-- Table with stripped rows -->
              <table class="table datatable">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>NIP</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Level</th>
                    <th style="text-align: center;">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                	<?php $no = 1;
                	foreach($row->result() as $key => $data) { ?>
                  <tr>
                    <td><?=$no++?>.</td>
                    <td><?=$data->NIP?></td>
                    <td><?=$data->nama?></td>
                    <td><?=$data->email?></td>
                    <td>
                        <?= ($data->level == 1) ? "Admin" : 
                            (($data->level == 2) ? "Bendahara Pengeluaran" : 
                            (($data->level == 3) ? "Bendahara Penerimaan" : "Pengguna")) ?>
                    </td>

                    <td style="text-align: center;">
                        <div style="display: flex; justify-content: center; gap: 5px;">
                            <button onclick="window.location.href='<?=site_url('Pengguna/ubah/'.$data->NIP)?>'" class="btn btn-primary btn-sm">
                                <i class="bi bi-pencil"></i> Ubah
                            </button>
                            <form action="<?=site_url('Pengguna/hapus')?>" method="post">
                                <input type="hidden" name="f_NIP" value="<?=$data->NIP?>">
                                <button onclick="return confirm('Apakah yakin ingin menghapus?')" class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                  </tr>
                  	<?php
              		} ?>
                </tbody>
              </table>
              <!-- End Table with stripped rows -->
            </div>
          </div>
       </div>
    </div>
</section>

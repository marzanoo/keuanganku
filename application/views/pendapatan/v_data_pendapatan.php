<style>
    /* Gaya untuk modal latar belakang */
    .modal-background {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5); /* Transparan hitam */
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }

    /* Gaya untuk modal konten */
    .modal-content {
        background-color: #fff;
        border-radius: 8px;
        max-width: 600px;
        width: 90%;
        padding: 20px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        animation: fadeIn 0.3s ease-in-out;
    }

    /* Header modal */
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .modal-header h3 {
        margin: 0;
        font-size: 1.5rem;
    }

    /* Tombol tutup modal */
    .modal-header button {
        background: none;
        border: none;
        font-size: 1.2rem;
        cursor: pointer;
    }

    /* Animasi muncul modal */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
</style>
<div class="pagetitle">
<h1>Manajemen Pendapatan</h1>
<nav>
    <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="Dashboard">Home</a></li>
    <li class="breadcrumb-item">Data Pendapatan</li>
    </ol>
</nav>
</div><!-- End Page Title -->
<section class="section dashboard">
    <div class="row">
        <!-- Kolom untuk Sumber Dana -->
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Sumber Dana</h5>
                    <!-- Looping melalui sumber dana dan menampilkan progress bar dengan persentase dan nominal -->
                    <?php foreach ($persentase_sumber as $sumber => $data): ?>
                        <p><?= ucfirst($sumber) ?>: Rp <?= number_format($data['nominal'], 2) ?></p>
                        <div class="progress mb-3">
                            <div class="progress-bar" role="progressbar" 
                                style="width: <?= $data['persen'] ?>%" 
                                aria-valuenow="<?= $data['persen'] ?>" aria-valuemin="0" aria-valuemax="100">
                                <?= number_format($data['persen'], 2) ?>%
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Pendapatan Card -->
        <div class="col-xxl-6 col-md-6">
            <div class="card info-card sales-card">                
                <div class="card-body">
                    <h5 class="card-title">Penerimaan</h5>

                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-receipt"></i>
                        </div>
                        <div class="ps-3">
                            <h6>Rp <?= number_format($total ?? 0, 2)?></h6>
                        </div>
                    </div>
                    <button class="btn btn-primary mt-4" onclick="openTransferModal()">Pindah Dana Ke Rekening Pengeluaran</button>
                </div>
            </div>
        </div>
</section>
<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Data Pendapatan</h5>
                    <button class="btn btn-primary" onclick="openModal()">Tambah Data</button>
                    <table class="table datatable mt-4">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Jumlah</th>
                                <th>Sumber</th> <!-- Kolom untuk Sumber -->                                
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="pendapatanTableBody">
                            <?php $index = 1;
                                foreach ($pendapatan as $row): ?>
                                
                                <tr>
                                    <td><?= $index++ ?>.</td>
                                    <td><?= $row->tanggal ?></td>
                                    <td><?= number_format($row->jumlah, 2) ?></td>
                                    <td><?= $row->sumber ?></td>
                                    <td style="text-align: center;">
                                        <div style="display: flex; justify-content: center; gap: 5px;">
                                            <button class="btn btn-primary btn-sm" 
                                                onclick="openModalEdit('<?= $row->pendapatan_id ?>', '<?= $row->tanggal ?>', <?= $row->jumlah ?>, '<?= $row->sumber ?>')">
                                                <i class="bi bi-pencil"></i> Ubah
                                            </button>
                                            <form action="<?= base_url('pendapatan/delete/' . $row->pendapatan_id); ?>" method="post" onsubmit="return confirm('Apakah yakin ingin menghapus?')">
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Modal Transfer Dana -->
<div class="modal-background" id="transferModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Pindahkan Dana</h3>
            <button onclick="closeTransferModal()">×</button>
        </div>
        <div class="modal-body">
            <form id="transferForm" method="post" action="<?= site_url('pendapatan/transfer_dana') ?>">
                <div class="form-group">
                    <label for="jumlah">Jumlah:</label>
                    <input type="number" name="f_jumlah" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success mt-3">Pindahkan Dana</button>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Data Pendapatan -->
<div class="modal-background" id="dataModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Tambah Data Pendapatan</h3>
            <button onclick="closeModal()">×</button>
        </div>
        <div class="modal-body">
            <form id="addForm" method="post" action="<?= site_url('pendapatan/add') ?>">
                <div class="form-group">
                    <label for="tanggal">Tanggal:</label>
                    <input type="date" name="f_tanggal" class="form-control" required value="<?= date('Y-m-d') ?>">
                </div>            
                <div class="form-group">
                    <label for="jumlah">Jumlah:</label>
                    <input type="number" name="f_jumlah" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="sumber">Sumber:</label>
                    <input type="text" name="f_sumber" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success mt-3">Simpan</button>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Data Pendapatan -->
<div class="modal-background" id="editModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Ubah Data Pendapatan</h3>
            <button onclick="closeModalEdit()">×</button>
        </div>
        <div class="modal-body">
            <form method="post" action="#">
                <div class="form-group">
                    <label for="tanggal">Tanggal:</label>
                    <input type="date" name="f_tanggal" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="jumlah">Jumlah:</label>
                    <input type="number" name="f_jumlah" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="sumber">Sumber:</label>
                    <input type="text" name="f_sumber" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success mt-3">Ubah</button>
            </form>
        </div>
    </div>
</div>


<script>
    // Fungsi untuk membuka modal
    function openTransferModal() {
        document.getElementById("transferModal").style.display = "flex";
    }

    function closeTransferModal() {
        document.getElementById("transferModal").style.display = "none";
    }

    function openModal() {
        document.getElementById("dataModal").style.display = "flex";
    }

    function closeModal() {
        document.getElementById("dataModal").style.display = "none";
    }

    function openModalEdit(pendapatan_id, tanggal, jumlah, sumber) {
        document.getElementById('editModal').style.display = 'flex'; // Tampilkan modal
        document.querySelector('#editModal [name="f_tanggal"]').value = tanggal;
        document.querySelector('#editModal [name="f_jumlah"]').value = jumlah;
        document.querySelector('#editModal [name="f_sumber"]').value = sumber;
        document.querySelector('#editModal form').action = `<?= site_url('pendapatan/edit/') ?>${pendapatan_id}`; // Set form action
    }

    function closeModalEdit() {
        document.getElementById("editModal").style.display = "none";
    }
</script>
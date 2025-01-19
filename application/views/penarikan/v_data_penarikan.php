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
    <h1>Manajemen Pengeluaran</h1>
    <nav>
        <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="Dashboard">Home</a></li>
        <li class="breadcrumb-item">Data Penarikan</li>
        </ol>
    </nav>
</div><!-- End Page Title -->
<section class="section">   
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Jenis Dana</h5>
                    <!-- Progress bar untuk jenis dana rekening -->
                    <p>Rekening: Rp <?= number_format($total_dana_rek_pengeluaran_rekening ?? 0, 2, ',', '.') ?></p>
                    <div class="progress mb-3">
                        <div class="progress-bar bg-info" role="progressbar" 
                            style="width: <?= $persentase_rekening ?? 0 ?>%" 
                            aria-valuenow="<?= $persentase_rekening ?? 0 ?>" aria-valuemin="0" aria-valuemax="100">
                            <?= number_format($persentase_rekening ?? 0, 2, ',', '.') ?>%
                        </div>
                    </div>

                    <!-- Informasi Dana Cash -->
                    <p>Cash: Rp <?= number_format($total_dana_rek_pengeluaran_cash ?? 0, 2, ',', '.') ?></p>
                    <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" 
                            style="width: <?= $persentase_cash ?? 0 ?>%" 
                            aria-valuenow="<?= $persentase_cash ?? 0 ?>" aria-valuemin="0" aria-valuemax="100">
                            <?= number_format($persentase_cash ?? 0, 2, ',', '.') ?>%
                        </div>
                    </div>
                    
                    <!-- Tombol Pindah Dana -->
                    <button class="btn btn-primary mt-4" onclick="openTransferModal()">Pindah Dana</button>
                    <!-- Total Dana Keseluruhan -->
                     <h5 class="card-title">Total Dana: Rp <?= number_format($saldo_penarikan ?? 0, 2, ',', '.') ?></h5>                                       
                </div>
            </div>   
        </div>
    </div>
</section>
<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Data Penarikan</h5>                    
                    <table class="table datatable mt-4">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Jumlah</th>
                                <th class="text-center">Aksi</th>                        
                            </tr>
                        </thead>
                        <tbody id="pengeluaranTableBody">                                
                            <?php $index = 1;
                            foreach ($rek_pengeluaran as $row):
                                if ($row->jenis == "Rekening"): ?>
                                    <tr>
                                        <td><?= $index++ ?>.</td>
                                        <td><?= $row->tanggal ?></td>                            
                                        <td><?= number_format($row->jumlah2 ?? 0, 2, ',', '.') ?></td>                                                            
                                        <td style="text-align: center;">
                                            <div style="display: flex; justify-content: center; gap: 5px;">
                                                <button class="btn btn-primary btn-sm" 
                                                    onclick="openModalEdit('<?= $row->id ?>', '<?= $row->tanggal ?>', <?= $row->jumlah ?>)">
                                                    <i class="bi bi-pencil"></i> Ubah
                                                </button>                                           
                                                <form action="<?= base_url('penarikan/delete/' . $row->id); ?>" method="post" onsubmit="return confirm('Apakah yakin ingin menghapus?')">
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="bi bi-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif;
                            endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="modal-background" id="editModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Ubah Data Penarikan</h3>
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
                    <input type="text" id="jumlah" name="f_jumlah" class="form-control" required oninput="formatRupiah(this)" onblur="removeNonNumeric(this)">
                </div>
                <button type="submit" class="btn btn-success mt-3">Ubah</button>
            </form>
        </div>
    </div>
</div>
<!-- Modal Transfer Dana -->
<div class="modal-background" id="transferModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Pindahkan Dana</h3>
            <button onclick="closeTransferModal()">×</button>
        </div>
        <div class="modal-body">
            <form id="transferForm" method="post" action="<?= site_url('penarikan/transfer_dana') ?>">
                <div class="form-group mt-2 mb-2">
                    <label for="jenis_transfer">Jenis Transfer:</label>
                    <select name="f_jenis_transfer" class="form-control" required>
                        <option value="rekening_ke_cash">Rekening ke Cash</option>
                    </select>
                </div>
                <div class="form-group mt-2 mb-2">
                    <label for="f_jumlah">Jumlah Transfer:</label>
                    <input type="text" id="jumlah" name="f_jumlah" class="form-control" required oninput="formatRupiah(this)" onblur="removeNonNumeric(this)">
                </div>
                <button type="submit" class="btn btn-success mt-3">Pindahkan Dana</button>
            </form>
        </div>
    </div>
</div>
<!-- Modal Tambah Data -->
<div class="modal-background" id="dataModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Tambah Data Pengeluaran</h3>
            <button onclick="closeModal()">×</button>
        </div>
        <div class="modal-body">
            <form id="addForm" method="post" action="<?= site_url('pengeluaran/add') ?>">
                <div class="form-group mt-2 mb-2">
                    <label for="tanggal">Tanggal:</label>
                    <input type="date" name="f_tanggal" class="form-control" required>
                </div>
                <div class="form-group mt-2 mb-2">
                    <label for="akun">Akun:</label>
                    <select name="f_akun" class="form-control" required>
                        <option value="" hidden selected disabled>Pilih Akun</option>
                        <?php foreach ($akun as $row): ?>
                            <option value="<?= $row->akun_id ?>"><?= $row->nama_akun ?></option> <!-- Asumsikan 'id' dan 'nama_akun' adalah nama kolom di tabel akun -->
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group mt-2 mb-2">
                    <label for="pajak">Pajak:</label>
                    <select name="f_pajak" class="form-control" required>
                        <option value="" hidden selected disabled>Pilih Akun</option>
                        <?php foreach ($pajak as $row): ?>
                            <option value="<?= $row->pajak_id ?>"><?= $row->nama_pajak ?> - <?= $row->persentase * 100 ?>%</option> <!-- Asumsikan 'id' dan 'nama_akun' adalah nama kolom di tabel akun -->
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group mt-2 mb-2">
                    <label for="output">Program & Output:</label>
                    <select name="f_output" class="form-control" required>
                        <option value="" hidden selected disabled>Pilih Akun</option>
                        <?php foreach ($output as $row): ?>
                            <option value="<?= $row->kode ?>"><?= $row->program ?> - <?= $row->kode ?> - <?= $row->keterangan ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group mt-2 mb-2">
                    <label for="jenis">Jenis Dana:</label>
                    <select name="f_jenis" class="form-control">
                        <option value="" hidden selected disabled>Pilih Jenis Dana</option>
                        <?php foreach ($jenis_values as $jenis): ?>
                            <option value="<?= $jenis ?>"><?= $jenis ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group mt-2 mb-2">
                    <label for="jumlah">Jumlah:</label>
                    <input type="number" name="f_jumlah" class="form-control" required>
                </div>
                <div class="form-group mt-2 mb-2">
                    <label for="deskripsi">Deskripsi:</label>
                    <input type="text" class="form-control" name="f_deskripsi">
                </div>
                <button type="submit" class="btn btn-success mt-3">Simpan</button>
            </form>
        </div>
    </div>
</div>
<script>
    function formatRupiah(element) {
        // Ambil nilai asli tanpa format
        let value = element.value.replace(/[^\d]/g, "");

        // Format menjadi Rupiah
        let formattedValue = new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
            minimumFractionDigits: 0
        }).format(value);

        // Tampilkan nilai terformat di input
        element.value = formattedValue;
    }

    function removeNonNumeric(element) {
        // Kembalikan nilai ke angka saja saat blur agar bisa dikirim ke database
        element.value = element.value.replace(/[^\d]/g, "");
    }
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
    function openModalEdit(id, tanggal, jumlah) {
        document.getElementById('editModal').style.display = 'flex'; // Tampilkan modal
        document.querySelector('#editModal [name="f_tanggal"]').value = tanggal;
        document.querySelector('#editModal [name="f_jumlah"]').value = jumlah;
        document.querySelector('#editModal form').action = `<?= site_url('penarikan/edit/') ?>${id}`; // Set form action
    }

    function closeModalEdit() {
        document.getElementById("editModal").style.display = "none";
    }
</script>
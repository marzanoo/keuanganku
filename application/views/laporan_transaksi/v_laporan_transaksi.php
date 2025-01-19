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
<section class="section">
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Cetak Laporan Transaksi</h4>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Detail Transaksi</h4>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="section">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Laporan Transaksi</h5>
                    <table class="table datatables">
                        <thead>
                            <tr>
                                <th>Laporan</th>
                                <th class="text-center">Cash</th>
                                <th class="text-center">Rekening</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Laporan Pengeluaran</td>
                                <td class="text-center"><button class="btn btn-primary" onclick="openPengeluaranModalCash()">Export Excel</button></td>
                                <td class="text-center"><button class="btn btn-primary" onclick="openPengeluaranModalRekening()">Export Excel</button></td>
                            </tr>                
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="modal-background" id="pengeluaranModalCash">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Export Data Pengeluaran Cash</h3>
            <button onclick="closePengeluaranModalCash()">×</button>
        </div>
        <div class="modal-body">
            <form id="pengeluaranForm" method="get" action="<?= site_url('lap_transaksi/export_excel_pengeluaran_cash') ?>">
                <!-- Input Manual Tanggal -->
                <div class="form-group mt-2 mb-2">
                    <label for="start_date">Tanggal Mulai:</label>
                    <input type="date" name="start_date" id="start_date" class="form-control">
                </div>
                <div class="form-group mt-2 mb-2">
                    <label for="end_date">Tanggal Selesai:</label>
                    <input type="date" name="end_date" id="end_date" class="form-control">
                </div>

                <!-- Filter Output -->
                <div class="form-group mt-2 mb-2">
                    <label for="output">Filter Output:</label>
                    <select name="output" id="output" class="form-control">
                        <option value="">Semua</option>
                        <?php foreach ($outputs as $row): ?>
                            <option value="<?= $row->kode ?>"><?= $row->kode ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Tombol Submit -->
                <button type="submit" class="btn btn-success mt-3">Export</button>
            </form>
        </div>
    </div>
</div>
<div class="modal-background" id="pengeluaranModalRekening">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Export Data Pengeluaran Rekening</h3>
            <button onclick="closePengeluaranModalRekening()">×</button>
        </div>
        <div class="modal-body">
            <form id="pengeluaranForm" method="get" action="<?= site_url('lap_transaksi/export_excel_pengeluaran_rekening') ?>">
                <!-- Input Manual Tanggal -->
                <div class="form-group mt-2 mb-2">
                    <label for="start_date">Tanggal Mulai:</label>
                    <input type="date" name="start_date" id="start_date" class="form-control">
                </div>
                <div class="form-group mt-2 mb-2">
                    <label for="end_date">Tanggal Selesai:</label>
                    <input type="date" name="end_date" id="end_date" class="form-control">
                </div>

                <!-- Filter Output -->
                <div class="form-group mt-2 mb-2">
                    <label for="output">Filter Output:</label>
                    <select name="output" id="output" class="form-control">
                        <option value="">Semua</option>
                        <?php foreach ($outputs as $row): ?>
                            <option value="<?= $row->kode ?>"><?= $row->kode ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Tombol Submit -->
                <button type="submit" class="btn btn-success mt-3">Export</button>
            </form>
        </div>
    </div>
</div>

<script>
    function openPengeluaranModalCash() {
        document.getElementById("pengeluaranModalCash").style.display = "flex";
    }

    function closePengeluaranModalCash() {
        document.getElementById("pengeluaranModalCash").style.display = "none";
    }
    function openPengeluaranModalRekening() {
        document.getElementById("pengeluaranModalRekening").style.display = "flex";
    }

    function closePengeluaranModalRekening() {
        document.getElementById("pengeluaranModalRekening").style.display = "none";
    }
</script>
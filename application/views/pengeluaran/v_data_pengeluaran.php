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
        max-width: 800px;
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
        <li class="breadcrumb-item">Data Pengeluaran</li>
        </ol>
    </nav>
</div><!-- End Page Title -->
<section class="section">   
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">                    
                    <h5 class="card-title">Info Dana</h5>
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
                    <!-- Progress bar untuk jenis dana rekening -->
                    
                    <!-- Tombol Pindah Dana -->                
                    <!-- Total Dana Keseluruhan -->
                     <h5 class="card-title">Total Dana: Rp <?= number_format($total_dana_rek_pengeluaran_cash ?? 0, 2, ',', '.') ?></h5>                                       
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
                    <h5 class="card-title">Data Pengeluaran</h5>
                    <button class="btn btn-primary" onclick="openModal()">Tambah Data</button>
                    <table class="table datatable mt-4">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Akun</th>                                
                                <th>Program</th>
                                <th>Output</th>
                                <th>Deskripsi</th>
                                <th>Netto</th>                                
                                <th>Pajak</th>
                                <th>Nilai Pajak</th>
                                <th>Jumlah</th>
                                <th>Keterangan</th>
                                <th class="text-center">Aksi</th>                        
                            </tr>
                        </thead>
                        <tbody id="pengeluaranTableBody">                                
                            <?php $index = 1;
                                foreach ($pengeluaran as $row): ?>
                                <tr>
                                    <td><?= $index++ ?>.</td>
                                    <td><?= $row->tanggal ?></td>
                                    <td><?= $row->akun_id ?></td>                                    
                                    <td><?= $row->program ?></td>
                                    <td><?= $row->kode ?></td>
                                    <td><?= $row->deskripsi ?></td>
                                    <td><?= number_format($row->total ?? 0, 2, ',', '.') ?></td>                       
                                    <td><?= $row->nama_pajak ?></td>
                                    <td><?= $row->persentase * 100 ?>%</td>
                                    <td><?= number_format($row->jumlah ?? 0, 2, ',', '.') ?></td> 
                                    <td><?= $row->keterangan?></td>
                                    <td style="text-align: center;">
                                        <div style="display: flex; justify-content: center; gap: 5px;">
                                            <button class="btn btn-primary btn-sm"
                                                onclick="openModalEdit('<?= $row->pengeluaran_id ?>', '<?= $row->tanggal ?>', '<?= $row->akun_id ?>', '<?= $row->pajak_id ?>', '<?= $row->program ?>', '<?= $row->output ?>', '<?= $row->jumlah ?>',
                                                '<?= $row->jenis ?>', '<?= $row->nama_perusahaan ?>', '<?= $row->npwp ?>', '<?= $row->no_rek ?>', '<?= $row->nama_bank ?>', '<?= $row->alamat ?>', '<?= $row->uraian ?>', '<?= $row->deskripsi ?>', '<?= $row->keterangan ?>',
                                                '<?= $row->total?>')">
                                                <i class="bi bi-pencil"></i> Ubah
                                            </button>                                            
                                            <form action="<?= base_url('pengeluaran/delete/' . $row->pengeluaran_id); ?>" method="post" onsubmit="return confirm('Apakah yakin ingin menghapus?')">
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
                                if ($row->jenis == "Cash"): ?>
                                    <tr>
                                        <td><?= $index++ ?>.</td>
                                        <td><?= $row->tanggal ?></td>                            
                                        <td><?= number_format($row->jumlah2 ?? 0, 2, ',', '.') ?></td>                                                            
                                        <td style="text-align: center;">
                                            <div style="display: flex; justify-content: center; gap: 5px;">
                                                <button class="btn btn-primary btn-sm" 
                                                    onclick="openModalRekEdit('<?= $row->id ?>', '<?= $row->tanggal ?>', <?= $row->jumlah ?>)">
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
<div class="modal-background" id="editRekModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Ubah Data Penarikan</h3>
            <button onclick="closeModalRekEdit()">×</button>
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
            <form id="transferForm" method="post" action="<?= site_url('pengeluaran/transfer_dana') ?>">
                <div class="form-group mt-2 mb-2">
                    <label for="jenis_transfer">Jenis Transfer:</label>
                    <select name="f_jenis_transfer" class="form-control" required>
                        <option value="cash_ke_rekening">Cash ke Rekening</option>
                        <option value="rekening_ke_cash">Rekening ke Cash</option>
                    </select>
                </div>
                <div class="form-group mt-2 mb-2">
                    <label for="f_jumlah">Jumlah Transfer:</label>
                    <input type="number" name="f_jumlah" class="form-control" required>
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
                <div class="row">
                    <div class="col-6">
                        <div class="form-group mt-2 mb-2">
                            <label for="tanggal">Tanggal:</label>
                            <input type="date" name="f_tanggal" class="form-control" required value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="form-group mt-2 mb-2">
                            <label for="akun">Akun:</label>
                            <select name="f_akun" class="form-control" required>
                                <option value="" hidden selected disabled>Pilih Akun</option>
                                <?php foreach ($akun as $row): ?>
                                    <option value="<?= $row->akun_id ?>"><?= $row->akun_id ?> - <?= $row->nama_akun ?></option> <!-- Asumsikan 'id' dan 'nama_akun' adalah nama kolom di tabel akun -->
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group mt-2 mb-2">
                            <label for="pajak">Pajak:</label>
                            <select name="f_pajak" class="form-control" required>
                                <option value="" hidden disabled>Pilih Pajak</option>
                                <?php foreach ($pajak as $row): ?>
                                    <option value="<?= $row->pajak_id ?>" <?= $row->nama_pajak === 'Non Pajak' ? 'selected' : '' ?>>
                                        <?= $row->nama_pajak ?> - <?= $row->persentase * 100 ?>%
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group mt-2 mb-2">
                            <label for="program">Program:</label>
                            <select name="f_program" id="program" class="form-control" required>
                                <option value="" hidden selected disabled>Pilih Program</option>
                                <?php foreach ($program as $row): ?>
                                    <option value="<?= $row->id ?>"><?= $row->program ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group mt-2 mb-2">
                            <label for="output">Output:</label>
                            <select name="f_output" id="output" class="form-control" required>
                                <option value="" hidden selected disabled>Pilih Output</option>
                                <!-- Output akan diisi berdasarkan pilihan program -->
                            </select>
                        </div>
                        <div class="form-group mt-2 mb-2">
                            <label for="jumlah">Jumlah:</label>
                           <input type="text" id="jumlah" name="f_jumlah" class="form-control" required oninput="formatRupiah(this)" onblur="removeNonNumeric(this)">
                        </div>
                        <div class="form-group mt-2 mb-2">
                            <label for="deskripsi">Deskripsi:</label>
                            <input type="text" class="form-control" name="f_deskripsi">
                        </div>
                        <div class="form-group mt-2 mb-2">
                            <label for="keterangan">Keterangan:</label>
                            <select name="f_keterangan" class="form-control" required>
                                <option value="" hidden selected disabled>Pilih Keterangan</option>
                                <?php foreach ($keterangan_values as $keterangan): ?>
                                    <option value="<?= $keterangan ?>"><?= $keterangan ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success mt-3">Simpan</button>
                    </div>
                    <div class="col-6">
                        <div class="form-group mt-2 mb-2">
                            <label for="jenis">Jenis Dana:</label>
                            <select name="f_jenis" class="form-control" id="jenisDana" required>
                                <option value="" hidden selected disabled>Pilih Jenis Dana</option>
                                <?php foreach ($jenis_values as $jenis): ?>
                                    <option value="<?= $jenis ?>"><?= $jenis ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>                
                        <div class="form-group mt-2 mb-2" id="vendorField" style="display: none;">
                            <label for="nama_perusahaan">Nama Perusahaan:</label>
                            <input type="text" name="f_nama_perusahaan" class="form-control">
                            <label for="npwp" class="mt-2">NPWP:</label>
                            <input type="text" name="f_npwp" class="form-control">
                            <label for="no_rek" class="mt-2">No Rekening:</label>
                            <input type="text" name="f_no_rek" class="form-control">
                            <label for="nama_bank" class="mt-2">Nama Bank:</label>
                            <input type="text" name="f_nama_bank" class="form-control">
                            <label for="alamat" class="mt-2">Alamat:</label>
                            <input type="text" name="f_alamat" class="form-control">
                            <label for="uraian" class="mt-2">Uraian:</label>
                            <input type="text" name="f_uraian" class="form-control">
                        </div>
                    </div>
                </div>                                                
            </form>
        </div>
    </div>
</div>
<!-- Modal Ubah Data -->
<div class="modal-background" id="editModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Ubah Data Pengeluaran</h3>
            <button onclick="closeModalEdit()">×</button>
        </div>
        <div class="modal-body">
            <form method="post" action="#">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group mt-2 mb-2">
                            <label for="tanggal">Tanggal:</label>
                            <input type="date" name="f_tanggal" class="form-control" required>
                        </div>
                        <div class="form-group mt-2 mb-2">
                            <label for="akun">Akun:</label>
                            <select name="f_akun" class="form-control" required>                            
                                <?php foreach ($akun as $row): ?>
                                    <option value="<?= $row->akun_id ?>"><?= $row->akun_id ?> - <?= $row->nama_akun ?></option> <!-- Asumsikan 'id' dan 'nama_akun' adalah nama kolom di tabel akun -->
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group mt-2 mb-2">
                            <label for="pajak">Pajak:</label>
                            <select name="f_pajak" class="form-control" required>
                                <?php foreach ($pajak as $row): ?>
                                    <option value="<?= $row->pajak_id ?>">
                                        <?= $row->nama_pajak ?> - <?= $row->persentase * 100 ?>%
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group mt-2 mb-2">
                            <label for="program">Program:</label>
                            <select name="f_program" id="program2" class="form-control" required>
                                <?php foreach ($program as $row): ?>
                                    <option value="<?= $row->id ?>"><?= $row->program ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group mt-2 mb-2">
                            <label for="output">Output:</label>
                            <select name="f_output" id="output2" class="form-control" required>
                                <option value="" hidden selected disabled>Pilih Output</option>
                                <!-- Output akan diisi berdasarkan pilihan program -->
                            </select>
                        </div>
                        <div class="form-group mt-2 mb-2">
                            <label for="jumlah">Jumlah:</label>
                            <input type="text" name="f_jumlah" class="form-control" required>
                        </div>
                        <div class="form-group mt-2 mb-2">
                            <label for="deskripsi">Deskripsi:</label>
                            <input type="text" class="form-control" name="f_deskripsi">
                        </div>
                        <div class="form-group mt-2 mb-2">
                            <label for="keterangan">Keterangan:</label>
                            <select name="f_keterangan" class="form-control">
                                <?php foreach ($keterangan_values as $keterangan): ?>
                                    <option value="<?= $keterangan ?>"><?= $keterangan ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success mt-3">Simpan</button>
                    </div>
                    <div class="col-6">
                        <div class="form-group mt-2 mb-2">
                            <label for="jenis">Jenis Dana:</label>
                            <select name="f_jenis" class="form-control" id="jenisDana2">
                                <?php foreach ($jenis_values as $jenis): ?>
                                    <option value="<?= $jenis ?>"><?= $jenis ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>                
                        <div class="form-group mt-2 mb-2" id="vendorField2" style="display: none;">
                            <label for="nama_perusahaan">Nama Perusahaan:</label>
                            <input type="text" name="f_nama_perusahaan" class="form-control">
                            <label for="npwp" class="mt-2">NPWP:</label>
                            <input type="text" name="f_npwp" class="form-control">
                            <label for="no_rek" class="mt-2">No Rekening:</label>
                            <input type="text" name="f_no_rek" class="form-control">
                            <label for="nama_bank" class="mt-2">Nama Bank:</label>
                            <input type="text" name="f_nama_bank" class="form-control">
                            <label for="alamat" class="mt-2">Alamat:</label>
                            <input type="text" name="f_alamat" class="form-control">
                            <label for="uraian" class="mt-2">Uraian:</label>
                            <input type="text" name="f_uraian" class="form-control">
                        </div>
                    </div>
                </div>                                                
            </form>
        </div>
    </div>
</div>
<script>
    // function formatNumber(input) {
    //     let value = input.value.replace(/\D/g, ""); // Hanya angka
    //     value = new Intl.NumberFormat('id-ID').format(value); // Format angka sesuai ID (Indonesia)
    //     input.value = value;
    // }

    // document.addEventListener("DOMContentLoaded", function() {
    //     const inputJumlah = document.querySelector('input[name="f_jumlah"]');

    //     // Event untuk memformat input
    //     inputJumlah.addEventListener('input', function() {
    //         this.value = formatRibuan(this.value); // Format angka saat pengguna mengetik
    //     });
    // });
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
    function openModalEdit(
        pengeluaran_id, tanggal, akun_id, pajak_id, program, output, jumlah,
        jenis, nama_perusahaan, npwp, no_rek, nama_bank, alamat, uraian, deskripsi, keterangan
    ) {
        // Tampilkan modal edit
        document.getElementById('editModal').style.display = 'flex';

        // Isi nilai form
        document.querySelector('#editModal [name="f_tanggal"]').value = tanggal;
        document.querySelector('#editModal [name="f_akun"]').value = akun_id;
        document.querySelector('#editModal [name="f_pajak"]').value = pajak_id;
        document.querySelector('#editModal [name="f_program"]').value = program;
        document.querySelector('#editModal [name="f_output"]').value = output;
        document.querySelector('#editModal [name="f_jumlah"]').value = jumlah;
        document.querySelector('#editModal [name="f_deskripsi"]').value = deskripsi;
        document.querySelector('#editModal [name="f_keterangan"]').value = keterangan;
        document.querySelector('#editModal [name="f_jenis"]').value = jenis;
        document.querySelector('#editModal [name="f_nama_perusahaan"]').value = nama_perusahaan;
        document.querySelector('#editModal [name="f_npwp"]').value = npwp;
        document.querySelector('#editModal [name="f_no_rek"]').value = no_rek;
        document.querySelector('#editModal [name="f_nama_bank"]').value = nama_bank;
        document.querySelector('#editModal [name="f_alamat"]').value = alamat;
        document.querySelector('#editModal [name="f_uraian"]').value = uraian;
        console.log("Tanggal:", document.querySelector('#editModal [name="f_tanggal"]').value);
        console.log("Program:", document.querySelector('#editModal [name="f_program"]').value);
        console.log("Output:", document.querySelector('#editModal [name="f_output"]').value);

        // Set action form dengan pengeluaran_id
        const form = document.querySelector('#editModal form');
        form.action = `<?= site_url('pengeluaran/edit/') ?>${pengeluaran_id}`;
    }


    function closeModalEdit() {
        document.getElementById("editModal").style.display = "none";
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
    function openModalRekEdit(id, tanggal, jumlah) {
        document.getElementById('editRekModal').style.display = 'flex'; // Tampilkan modal
        document.querySelector('#editRekModal [name="f_tanggal"]').value = tanggal;
        document.querySelector('#editRekModal [name="f_jumlah"]').value = jumlah;
        document.querySelector('#editRekModal form').action = `<?= site_url('pengeluaran/edit_rek_pengeluaran/') ?>${id}`; // Set form action
    }

    function closeModalRekEdit() {
        document.getElementById("editRekModal").style.display = "none";
    }
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#program').change(function() {
            var programKode = $(this).val();
            
            // Kosongkan dropdown output setiap kali pilihan program berubah
            $('#output').empty();
            $('#output').append('<option value="" hidden selected disabled>Pilih Output</option>');
            
            if (programKode) {
                $.ajax({
                    url: 'Pengeluaran/get_output_by_program',                
                    type: 'POST',
                    data: { program: programKode },
                    dataType: 'json',
                    success: function(data) {
                        // Masukkan data output yang sesuai ke dalam dropdown
                        $.each(data, function(index, item) {
                            $('#output').append('<option value="' + item.kode + '">' + item.kode + ' - ' + item.keterangan + '</option>');
                        });
                    }
                });
            }
        });
    });
    $(document).ready(function() {
        $('#program2').change(function() {
            var programKode = $(this).val();
            
            // Kosongkan dropdown output setiap kali pilihan program berubah
            $('#output2').empty();
            $('#output2').append('<option value="" hidden selected disabled>Pilih Output</option>');
            
            if (programKode) {
                $.ajax({
                    url: 'Pengeluaran/get_output_by_program',                
                    type: 'POST',
                    data: { program: programKode },
                    dataType: 'json',
                    success: function(data) {
                        // Masukkan data output yang sesuai ke dalam dropdown
                        $.each(data, function(index, item) {
                            $('#output2').append('<option value="' + item.kode + '">' + item.kode + ' - ' + item.keterangan + '</option>');
                        });
                    }
                });
            }
        });
    });
    document.getElementById('jenisDana').addEventListener('change', function () {
        const vendorField = document.getElementById('vendorField');
        if (this.value === 'Rekening') {
            vendorField.style.display = 'block'; // Tampilkan jika jenis dana "Rekening Baru"
        } else {
            vendorField.style.display = 'none'; // Sembunyikan jika jenis dana bukan "Rekening Baru"
        }
    });
    document.getElementById('jenisDana2').addEventListener('change', function () {
        const vendorField2 = document.getElementById('vendorField2');
        if (this.value === 'Rekening') {
            vendorField2.style.display = 'block'; // Tampilkan jika jenis dana "Rekening Baru"
        } else {
            vendorField2.style.display = 'none'; // Sembunyikan jika jenis dana bukan "Rekening Baru"
        }
    });
</script>

<?php defined('BASEPATH') OR exit('No direct script access allowed');

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Pengeluaran extends CI_Controller {

    public function __construct() {
        parent::__construct();
        check_not_login();
        check_bendahara_pengeluaran();
        $this->load->model('m_data_pengeluaran');
        $this->load->library('session');
    }

    public function index() {
        // Mendapatkan data pengeluaran dari model
        $data['pengeluaran'] = $this->m_data_pengeluaran->get_pengeluaran();
        $data['akun'] = $this->db->get_where('m_akun', ['tipe' => 'Pengeluaran'])->result();
        $data['pajak'] = $this->db->get('m_pajak')->result();
        $data['program'] = $this->db->get('m_program')->result();
        $data['output'] = $this->db->get('m_output')->result();
        $data['jenis_values'] = $this->m_data_pengeluaran->get_jenis_values();
        $data['keterangan_values'] = $this->m_data_pengeluaran->get_keterangan_values();
        $data['rek_pengeluaran'] = $this->m_data_pengeluaran->get_rek_pengeluaran();


        $data['total_dana_rek_pengeluaran_cash'] = $this->m_data_pengeluaran->get_dana_total_pengeluaran_cash();
        $data['total_dana_rek_pengeluaran_rekening'] = $this->m_data_pengeluaran->get_dana_total_pengeluaran_rekening();

        // Total keseluruhan dana
        $total_dana = $data['total_dana_rek_pengeluaran_cash'] + $data['total_dana_rek_pengeluaran_rekening'];

        // Menghitung persentase
        $data['persentase_cash'] = $total_dana > 0 ? ($data['total_dana_rek_pengeluaran_cash'] / $total_dana) * 100 : 0;
        $data['persentase_rekening'] = $total_dana > 0 ? ($data['total_dana_rek_pengeluaran_rekening'] / $total_dana) * 100 : 0;

        // Memuat view dengan template
        $this->template->load('template', 'pengeluaran/v_data_pengeluaran', $data);
    }

    public function get_output_by_program() {
        $programKode = $this->input->post('program');
        $this->load->model('m_data_pengeluaran'); // Sesuaikan dengan nama model Anda

        $output = $this->m_data_pengeluaran->get_output_by_program($programKode);

        echo json_encode($output);
    }

    public function transfer_dana() {
        // Input jenis transfer dan jumlah dana
        $jenis_transfer = $this->input->post('f_jenis_transfer'); // arah transfer: 'cash_ke_rekening' atau 'rekening_ke_cash'
        $jumlah = $this->input->post('f_jumlah');
    
        // Tentukan jenis dana asal dan tujuan berdasarkan pilihan transfer
        if ($jenis_transfer == 'cash_ke_rekening') {
            $jenis_asal = 'Cash';
            $jenis_tujuan = 'Rekening';
        } else {
            $jenis_asal = 'Rekening';
            $jenis_tujuan = 'Cash';
        }
    
        // Ambil saldo dana asal
        $saldo_asal = $this->m_data_pengeluaran->get_saldo_rek_pengeluaran_by_jenis($jenis_asal);
    
        // Periksa apakah saldo asal mencukupi
        if ($saldo_asal < $jumlah) {
            $this->session->set_flashdata('error', 'Saldo ' . $jenis_asal . ' tidak mencukupi.');
            redirect('pengeluaran');
            return;
        }
    
        // Kurangi saldo dana asal di `rek_pengeluaran`
        $this->m_data_pengeluaran->update_saldo_rek_pengeluaran($jenis_asal, -$jumlah);
    
        // Periksa apakah saldo dana tujuan sudah ada di `rek_pengeluaran`
        $saldo_tujuan = $this->m_data_pengeluaran->get_saldo_rek_pengeluaran_by_jenis($jenis_tujuan);
    
        // Jika saldo tujuan belum ada, tambahkan baris baru untuk saldo tujuan
        if ($saldo_tujuan === null) {
            $this->m_data_pengeluaran->insert_saldo_rek_pengeluaran($jenis_tujuan, $jumlah);
        } else {
            // Jika saldo tujuan sudah ada, tambahkan jumlahnya
            $this->m_data_pengeluaran->update_saldo_rek_pengeluaran($jenis_tujuan, $jumlah);
        }
    
        $this->session->set_flashdata('success', 'Transfer dari ' . $jenis_asal . ' ke ' . $jenis_tujuan . ' berhasil.');
        redirect('pengeluaran');
    }

    public function delete($pengeluaran_id) {
        $this->m_data_pengeluaran->delete_pengeluaran($pengeluaran_id);

        $this->session->set_flashdata('seccess', 'Data pengeluarah berhasi dihapus.');
        redirect('pengeluaran');
    }
    
    public function edit($pengeluaran_id) {
        // Ambil data dari form
        $tanggal = $this->input->post('f_tanggal');
        $akun_id = $this->input->post('f_akun');
        $pajak_id = $this->input->post('f_pajak');
        $program = $this->input->post('f_program');
        $output = $this->input->post('f_output');
        $jenis = $this->input->post('f_jenis');
        $jumlah = $this->input->post('f_jumlah');
        $deskripsi = $this->input->post('f_deskripsi');
        $keterangan = $this->input->post('f_keterangan');
        $nama_perusahaan = $this->input->post('f_nama_perusahaan');
        $npwp = $this->input->post('f_npwp');
        $no_rek = $this->input->post('f_no_rek');
        $nama_bank = $this->input->post('f_nama_bank');
        $alamat = $this->input->post('f_alamat');
        $uraian = $this->input->post('f_uraian');
    
        // Ambil data pengeluaran lama
        $pengeluaran_lama = $this->m_data_pengeluaran->get_pengeluaran_by_id($pengeluaran_id);
        if (!$pengeluaran_lama) {
            $this->session->set_flashdata('error', 'Data pengeluaran tidak ditemukan!');
            redirect('pengeluaran');
            return;
        }
    
        // Hitung total dengan pajak baru
        $pajak = $this->db->select('persentase')
                          ->get_where('m_pajak', ['pajak_id' => $pajak_id])
                          ->row();
        $persentase_pajak = $pajak ? $pajak->persentase : 0;
        $total_baru = $jumlah - ($jumlah * $persentase_pajak);
    
        // Update saldo berdasarkan perubahan jumlah
        // $selisih_jumlah = $jumlah - $pengeluaran_lama->jumlah;
        // $this->m_data_pengeluaran->update_jenis_rek_pengeluaran($jenis, -$selisih_jumlah);
    
        // Siapkan data untuk diupdate
        $data_update = array(
            'tanggal' => $tanggal,
            'akun_id' => $akun_id,
            'pajak_id' => $pajak_id,
            'program' => $program,
            'output' => $output,
            'jumlah' => $jumlah,
            'jenis' => $jenis,
            'nama_perusahaan' => $nama_perusahaan,
            'npwp' => $npwp,
            'no_rek' => $no_rek,
            'nama_bank' => $nama_bank,
            'alamat' => $alamat,
            'uraian' => $uraian,
            'deskripsi' => $deskripsi,
            'keterangan' => $keterangan,
            'total' => $total_baru
        );
    
        // Update data pengeluaran di database
        $this->m_data_pengeluaran->update_pengeluaran($pengeluaran_id, $data_update);
    
        $this->session->set_flashdata('success', 'Data pengeluaran berhasil diperbarui.');
        redirect('pengeluaran');
    }
    
    public function add() {
        // Ambil data dari form
        $tanggal = $this->input->post('f_tanggal');
        $akun_id = $this->input->post('f_akun');
        $pajak_id = $this->input->post('f_pajak');
        $program = $this->input->post('f_program');
        $output = $this->input->post('f_output');
        $jenis = $this->input->post('f_jenis');
        $jumlah = $this->input->post('f_jumlah');
        $deskripsi = $this->input->post('f_deskripsi');
        $keterangan = $this->input->post('f_keterangan');
        $nama_perusahaan = $this->input->post('f_nama_perusahaan');
        $npwp = $this->input->post('f_npwp');
        $no_rek = $this->input->post('f_no_rek');
        $nama_bank = $this->input->post('f_nama_bank');
        $alamat = $this->input->post('f_alamat');
        $uraian = $this->input->post('f_uraian');
        
        // Ambil persentase pajak berdasarkan pajak_id
        $pajak = $this->db->select('persentase')
                          ->get_where('m_pajak', ['pajak_id' => $pajak_id])
                          ->row();
        
        // Hitung total dengan pajak
        $persentase_pajak = $pajak ? $pajak->persentase : 0;
        $total = $jumlah - ($jumlah * $persentase_pajak);
    
        // Cek saldo jenis dana dari t_rek_pendapatan
        $saldo = $this->db->select('jumlah')
                          ->get_where('t_rek_pengeluaran', ['jenis' => $jenis])
                          ->row();
    
        if (!$saldo || $saldo->jumlah < $jumlah) {
            // Jika saldo tidak cukup, tampilkan pesan kesalahan
            $this->session->set_flashdata('error', 'Saldo tidak mencukupi untuk melakukan pengeluaran!');
            redirect('pengeluaran'); // Redirect kembali ke halaman form
            return;
        }
    
        // Siapkan data untuk disimpan
        $data = array(
            'tanggal' => $tanggal,
            'akun_id' => $akun_id,
            'pajak_id' => $pajak_id,
            'program' => $program,
            'output' => $output,
            'jumlah' => $jumlah,
            'jenis' => $jenis,
            'nama_perusahaan' => $nama_perusahaan,
            'npwp' => $npwp,
            'no_rek' => $no_rek,
            'nama_bank' => $nama_bank,
            'alamat' => $alamat,
            'uraian' => $uraian,
            'deskripsi' => $deskripsi,
            'keterangan' => $keterangan,
            'total' => $total
        );
    
        // Simpan data ke tabel pengeluaran
        $this->m_data_pengeluaran->insert_pengeluaran($data);
        redirect('pengeluaran');
    }

    public function edit_rek_pengeluaran($id) {
        $data = array(
            'tanggal' => $this->input->post('f_tanggal'),
            'jumlah' => $this->input->post('f_jumlah'),
            'jumlah2' => $this->input->post('f_jumlah')
        );
        $this->m_data_pengeluaran->update_rek_pengeluaran($id, $data);

        $this->session->set_flashdata('success', 'Data pendapatan berhasil diperbarui.');
        redirect('pengeluaran');
    }

    public function delete_rek_pengeluaran($id) {
        $this->m_data_pengeluaran->delete_rek_pengeluaran($id);

        $this->session->set_flashdata('success', 'Data pendapatan berhasi dihapus.');
        redirect('pengeluaran');
    }
    
}

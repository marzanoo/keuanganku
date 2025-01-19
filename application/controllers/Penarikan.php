<?php defined('BASEPATH') OR exit('No direct script access allowed');

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Penarikan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        check_not_login();
        check_bendahara_pengeluaran();
        $this->load->model('m_data_penarikan');
        $this->load->library('session');
    }

    public function index() {
        // Mendapatkan data pengeluaran dari model
        $data['rek_pengeluaran'] = $this->m_data_penarikan->get_rek_pengeluaran();
        $data['total_dana_rek_pengeluaran_cash'] = $this->m_data_penarikan->get_dana_total_pengeluaran_cash();
        $data['total_dana_rek_pengeluaran_rekening'] = $this->m_data_penarikan->get_dana_total_pengeluaran_rekening();
        $data['saldo_penarikan'] = $data['total_dana_rek_pengeluaran_cash'] + $data['total_dana_rek_pengeluaran_rekening'];
        // Total keseluruhan dana
        $total_dana = $data['total_dana_rek_pengeluaran_cash'] + $data['total_dana_rek_pengeluaran_rekening'];

        // Menghitung persentase
        $data['persentase_cash'] = $total_dana > 0 ? ($data['total_dana_rek_pengeluaran_cash'] / $total_dana) * 100 : 0;
        $data['persentase_rekening'] = $total_dana > 0 ? ($data['total_dana_rek_pengeluaran_rekening'] / $total_dana) * 100 : 0;
        // Memuat view dengan template
        $this->template->load('template', 'penarikan/v_data_penarikan', $data);
    }

    public function transfer_dana() {
        // Input jenis transfer dan jumlah dana
        $jenis_transfer = $this->input->post('f_jenis_transfer'); // arah transfer: 'cash_ke_rekening' atau 'rekening_ke_cash'
        $jumlah = $this->input->post('f_jumlah');
        $jumlah2 = $this->input->post('f_jumlah');    
        $tanggal = date('Y-m-d');     
        $deskripsi = "Penarikan Dana";
    
        // Tentukan jenis dana asal dan tujuan berdasarkan pilihan transfer
        if ($jenis_transfer == 'cash_ke_rekening') {
            $jenis_asal = 'Cash';
            $jenis_tujuan = 'Rekening';
        } else {
            $jenis_asal = 'Rekening';
            $jenis_tujuan = 'Cash';
        }
    
        // Ambil saldo dana asal dan tujuan
        $saldo_asal = $this->m_data_penarikan->get_saldo_rek_pengeluaran_by_jenis($jenis_asal);
        $saldo_tujuan = $this->m_data_penarikan->get_saldo_rek_pengeluaran_by_jenis($jenis_tujuan);
    
        // Periksa apakah saldo asal mencukupi
        if ($saldo_asal < $jumlah) {
            $this->session->set_flashdata('error', 'Saldo ' . $jenis_asal . ' tidak mencukupi.');
            redirect('penarikan');
            return;
        }
    
        // Lakukan perhitungan saldo baru (tanpa mengubah database)
        $saldo_asal_baru = $saldo_asal - $jumlah;
        $saldo_tujuan_baru = $saldo_tujuan + $jumlah2;
    
        // Catat transaksi transfer dana (tanpa mengubah saldo di database)
        $this->m_data_penarikan->insert_saldo_rek_pengeluaran($jenis_tujuan, $jumlah, $jumlah2, $tanggal, $deskripsi);
    
        // Berikan notifikasi keberhasilan beserta informasi saldo setelah transfer
        $this->session->set_flashdata('success', 
            'Transfer dari ' . $jenis_asal . ' ke ' . $jenis_tujuan . ' berhasil.<br>' .
            'Saldo ' . $jenis_asal . ' setelah transfer: ' . number_format($saldo_asal_baru, 2) . '<br>' .
            'Saldo ' . $jenis_tujuan . ' setelah transfer: ' . number_format($saldo_tujuan_baru, 2)
        );
        redirect('penarikan');
    }    

    public function edit($id) {
        $data = array(
            'tanggal' => $this->input->post('f_tanggal'),
            'jumlah' => $this->input->post('f_jumlah'),
            'jumlah2' => $this->input->post('f_jumlah')
        );
        $this->m_data_penarikan->update_rek_pengeluaran($id, $data);

        $this->session->set_flashdata('success', 'Data pendapatan berhasil diperbarui.');
        redirect('penarikan');
    }

    public function delete($id) {
        $this->m_data_penarikan->delete_rek_pengeluaran($id);

        $this->session->set_flashdata('success', 'Data pendapatan berhasi dihapus.');
        redirect('penarikan');
    }
    
}

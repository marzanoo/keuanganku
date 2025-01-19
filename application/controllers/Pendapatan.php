<?php defined('BASEPATH') OR exit('No direct script access allowed');

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Pendapatan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        check_not_login();
        check_bendahara_penerimaan();
        $this->load->model('m_data_pendapatan');
        $this->load->library('session');
    }

    public function index() {
        // Mengambil data pendapatan dan akun
        $data['pendapatan'] = $this->m_data_pendapatan->get_pendapatan();
        
        // Menghitung total seluruh jumlah pendapatan
        $this->db->select_sum('jumlah2');
        $total = $this->db->get('t_pendapatan')->row()->jumlah2;

        // Menghitung persentase berdasarkan sumber dana
        $this->db->select('sumber, SUM(jumlah2) as total_dana');
        $this->db->group_by('sumber');
        $sumber_dana = $this->db->get('t_pendapatan')->result();
        
        $data['persentase_sumber'] = [];
        foreach ($sumber_dana as $sumber) {
            $data['persentase_sumber'][$sumber->sumber] = [
                'persen' => $total > 0 ? ($sumber->total_dana / $total) * 100 : 0,
                'nominal' => $sumber->total_dana
            ];
        }

        // Menghitung total dana dari pendapatan berdasarkan jenis (cash dan rekening)


        // Menghitung saldo keseluruhan dan menyimpan ke session
        $data['total'] = $this->m_data_pendapatan->get_dana_penerimaan();
        // Load view dengan data yang telah diproses
        $this->template->load('template', 'pendapatan/v_data_pendapatan', $data);
    }
    
    public function add() {
        $data = array(
            'tanggal' => $this->input->post('f_tanggal'),
            'jumlah' => $this->input->post('f_jumlah'),
            'jumlah2' => $this->input->post('f_jumlah'),
            'sumber' => $this->input->post('f_sumber'),
        );

        $this->m_data_pendapatan->insert_pendapatan($data);
        redirect('pendapatan');
    }

    public function edit($pendapatan_id) {
        $data = array(
            'tanggal' => $this->input->post('f_tanggal'),
            'jumlah' => $this->input->post('f_jumlah'),
            'sumber' => $this->input->post('f_sumber')
        );
        $this->m_data_pendapatan->update_pendapatan($pendapatan_id, $data);

        $this->session->set_flashdata('success', 'Data pendapatan berhasil diperbarui.');
        redirect('pendapatan');
    }

    public function delete($pendapatan_id) {
        $this->m_data_pendapatan->delete_pendapatan($pendapatan_id);

        $this->session->set_flashdata('success', 'Data pendapatan berhasi dihapus.');
        redirect('pendapatan');
    }

    public function transfer_dana() {
        $jumlah = $this->input->post('f_jumlah');
        $jumlah2 = $this->input->post('f_jumlah');
        $tanggal = date('Y-m-d');
        $deskripsi = "Penarikan Dana";
        $jenis_tujuan = 'Rekening'; // Jenis tujuan, misalnya 'rekening'
    
    
        // Kurangi saldo di `t_pendapatan` berdasarkan sumber
        $this->m_data_pendapatan->insert_saldo_rek_pengeluaran($jenis_tujuan, $jumlah, $jumlah2, $tanggal, $deskripsi);
    
        
        $this->session->set_flashdata('success', 'Dana berhasil dipindahkan.');
        redirect('pendapatan');
    }
    
}

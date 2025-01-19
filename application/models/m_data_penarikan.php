<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class m_data_penarikan extends CI_Model {

    public function __construct() {
        parent::__construct(); // Memanggil konstruktor dari CI_Model
        $this->load->database(); // Memastikan database di-load
    }
    
    // Mendapatkan data pendapatan
    public function get_rek_pengeluaran() {
        return $this->db->order_by('tanggal', 'DESC')->get('t_rek_pengeluaran')->result();
    }
    
    public function update_saldo_rek_pengeluaran($jenis, $jumlah) {
        $this->db->set('jumlah', 'jumlah + ' . $jumlah, FALSE);
        $this->db->where('jenis', $jenis);
        $this->db->update('t_rek_pengeluaran');
    }

    public function get_saldo_rek_pengeluaran_by_jenis($jenis) {
        $this->db->select_sum('jumlah');
        $this->db->from('t_rek_pengeluaran');
        $this->db->where('jenis', $jenis);
        $query = $this->db->get();
        return $query->row()->jumlah;
    }
    
    
    public function insert_saldo_rek_pengeluaran($jenis, $jumlah, $jumlah2, $tanggal, $deskripsi) {
        $data = array(
            'jenis' => $jenis,
            'jumlah' => $jumlah,
            'jumlah2' => $jumlah2,
            'tanggal' => $tanggal,
            'deskripsi' => $deskripsi
        );
        $this->db->insert('t_rek_pengeluaran', $data);
    }

    public function get_total_dana_pengeluaran() {
        $this->db->select_sum('jumlah');
        $total_dana_pengeluaran = $this->db->get('t_rek_pengeluaran')->row()->jumlah;

        $this->db->select_sum('jumlah');
        $total_pengeluaran = $this->db->get('trx_pengeluaran')->row()->jumlah;

        return $total_dana_pengeluaran - $total_pengeluaran;
    }

    public function get_dana_total_pengeluaran_cash(){
        $this->db->select_sum('jumlah');
        $this->db->where('jenis', 'Cash');
        $dana_rek_pengeluaran = $this->db->get('t_rek_pengeluaran')->row()->jumlah;
        
        $this->db->select_sum('jumlah');
        $this->db->where('jenis', 'Cash');
        $total_pengeluaran = $this->db->get('trx_pengeluaran')->row()->jumlah;

        return $dana_rek_pengeluaran - $total_pengeluaran;
    }
    public function get_dana_total_pengeluaran_rekening(){
        $this->db->select_sum('jumlah');
        $this->db->where('jenis', 'Rekening');
        $dana_rek_pengeluaran = $this->db->get('t_rek_pengeluaran')->row()->jumlah;
        
        $this->db->select_sum('jumlah');
        $this->db->where('jenis', 'Rekening');
        $total_pengeluaran = $this->db->get('trx_pengeluaran')->row()->jumlah;

        $this->db->select_sum('jumlah');
        $this->db->where('jenis', 'Cash');
        $this->db->where('deskripsi', 'Penarikan Dana');
        $total_dana_pengeluaran = $this->db->get('t_rek_pengeluaran')->row()->jumlah;

        return $dana_rek_pengeluaran - $total_pengeluaran - $total_dana_pengeluaran;
    }

    public function update_rek_pengeluaran($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('t_rek_pengeluaran', $data);
    }

    public function delete_rek_pengeluaran($id) {
        $this->db->where('id', $id);
        $this->db->delete('t_rek_pengeluaran');
    }
    
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class m_data_pendapatan extends CI_Model {

    public function __construct() {
        parent::__construct(); // Memanggil konstruktor dari CI_Model
        $this->load->database(); // Memastikan database di-load
    }

    public function get_pendapatan() {
        return $this->db->order_by('tanggal', 'DESC')->get('t_pendapatan')->result();
    }

    public function get_pengeluaran() {
        return $this->db->get('trx_pengeluaran')->result();
    }

    public function insert_pendapatan($data) {
        return $this->db->insert('t_pendapatan', $data);
    }

    public function update_pendapatan($pendapatan_id, $data) {
        $this->db->where('pendapatan_id', $pendapatan_id);
        $this->db->update('t_pendapatan', $data);
    }

    public function delete_pendapatan($pendapatan_id) {
        $this->db->where('pendapatan_id', $pendapatan_id);
        $this->db->delete('t_pendapatan');
    }
        
    public function get_saldo_by_sumber($sumber) {
        // Ambil saldo berdasarkan sumber dari `t_pendapatan`
        $this->db->select('SUM(jumlah) as saldo');
        $this->db->where('sumber', $sumber);
        $query = $this->db->get('t_pendapatan');
        return $query->row()->saldo;
    }
    
    public function update_saldo_by_sumber($sumber) {
        // Update saldo di `t_pendapatan` dengan mengurangi jumlah berdasarkan sumber
        $this->db->select_sum('jumlah');
        $this->db->where('sumber', $sumber);
        
    }
    
    public function get_saldo_rek_pengeluaran_by_jenis($jenis) {
        // Ambil saldo dari `t_rek_pengeluaran` berdasarkan jenis
        $this->db->select('jumlah');
        $this->db->where('jenis', $jenis);
        $query = $this->db->get('t_rek_pengeluaran');
        return $query->row() ? $query->row()->jumlah : null;
    }
    
    public function insert_saldo_rek_pengeluaran($jenis, $jumlah, $jumlah2, $tanggal, $deskripsi) {
        // Masukkan saldo baru ke `t_rek_pengeluaran`
        $data = [
            'jenis' => $jenis,
            'jumlah' => $jumlah,
            'jumlah2' => $jumlah2,
            'tanggal' => $tanggal,
            'deskripsi' => $deskripsi
        ];
        $this->db->insert('t_rek_pengeluaran', $data);
    }
    
    public function update_saldo_rek_pengeluaran($jenis, $jumlah) {
        // Update saldo di `t_rek_pengeluaran` dengan menambah jumlah
        $this->db->set('jumlah', 'jumlah + '.$jumlah, FALSE);
        $this->db->where('jenis', $jenis);
        $this->db->update('t_rek_pengeluaran');
    }
    
    public function get_dana_penerimaan() {
        $this->db->select_sum('jumlah');
        $dana_penerimaan = $this->db->get('t_pendapatan')->row()->jumlah;

        $this->db->select_sum('jumlah');
        $this->db->where('jenis', 'Rekening');
        $dana_rek_pengeluaran = $this->db->get('t_rek_pengeluaran')->row()->jumlah;

        return $dana_penerimaan - $dana_rek_pengeluaran;
    }

    public function hitung_saldo() {
        // Ambil total pendapatan
        $this->db->select_sum('jumlah');
        $total_pendapatan = $this->db->get('t_pendapatan')->row()->jumlah ?? 0;

        // Ambil total pengeluaran
        $this->db->select_sum('total');
        $total_pengeluaran = $this->db->get('trx_pengeluaran')->row()->total ?? 0;

        // Hitung saldo keseluruhan
        return $total_pendapatan - $total_pengeluaran;
    }
    
    
}

<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class m_data_pengeluaran extends CI_Model {

    public function __construct() {
        parent::__construct(); // Memanggil konstruktor dari CI_Model
        $this->load->database(); // Memastikan database di-load
    }

    // Mendapatkan data pengeluaran dan nama pajak
    public function get_pengeluaran() {
        $this->db->select('trx_pengeluaran.*, m_pajak.nama_pajak, m_pajak.persentase, m_output.kode, m_program.program'); // Pilih semua kolom dari pengeluaran dan nama_pajak dari tabel pajak
        $this->db->from('trx_pengeluaran');
        $this->db->join('m_pajak', 'm_pajak.pajak_id = trx_pengeluaran.pajak_id', 'left'); // Gabungkan dengan tabel pajak
        $this->db->join('m_akun', 'm_akun.akun_id = trx_pengeluaran.akun_id', 'left');
        $this->db->join('m_output', 'm_output.kode = trx_pengeluaran.output', 'left');
        $this->db->join('m_program', 'm_program.id = trx_pengeluaran.program', 'left');
        $this->db->order_by('tanggal', 'DESC'); // Urutkan berdasarkan tanggal
        return $this->db->get()->result(); // Ambil hasilnya
    }
    
    public function get_rek_pengeluaran() {
        return $this->db->get('t_rek_pengeluaran')->result();
    }

    public function get_output_by_program($programKode) {
        $this->db->select('kode, keterangan');
        $this->db->from('m_output'); // Sesuaikan dengan nama tabel output Anda
        $this->db->where('program', $programKode); // Sesuaikan nama kolom foreign key
        $query = $this->db->get();

        return $query->result();
    }


    // Mendapatkan data pendapatan
    public function get_pendapatan() {
        return $this->db->get('t_pendapatan')->result();
    }

    // Mendapatkan semua nilai enum untuk kolom jenis
    public function get_jenis_values() {
        $query = $this->db->query("SHOW COLUMNS FROM trx_pengeluaran LIKE 'jenis'");
        $row = $query->row();
        if ($row) {
            preg_match("/^enum\(\'(.*)\'\)$/", $row->Type, $matches);
            return explode("','", $matches[1]);
        }
        return [];
    }
    public function get_keterangan_values() {
        $query = $this->db->query("SHOW COLUMNS FROM trx_pengeluaran LIKE 'keterangan'");
        $row = $query->row();
        if ($row) {
            preg_match("/^enum\(\'(.*)\'\)$/", $row->Type, $matches);
            return explode("','", $matches[1]);
        }
        return [];
    }
    
    // Memasukkan data pengeluaran baru
    public function insert_pengeluaran($data) {
        return $this->db->insert('trx_pengeluaran', $data);
    }

    public function update_pengeluaran($pengeluaran_id, $data) {
        $this->db->where('pengeluaran_id', $pengeluaran_id);
        $this->db->update('trx_pengeluaran', $data);
    }

    public function delete_pengeluaran($pengeluaran_id) {
        $this->db->where('pengeluaran_id', $pengeluaran_id);
        $this->db->delete('trx_pengeluaran');
    }

    public function get_pengeluaran_by_id($pengeluaran_id) {
        $this->db->where('pengeluaran_id', $pengeluaran_id);
        return $this->db->get('trx_pengeluaran')->row();
    }

    public function get_saldo_rek_pengeluaran_by_jenis($jenis) {
        $this->db->select('jumlah');
        $this->db->where('jenis', $jenis);
        $result = $this->db->get('t_rek_pengeluaran')->row();
        return $result ? $result->jumlah : null;
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

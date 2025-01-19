<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class m_lap_transaksi extends CI_Model {

    public function __construct() {
        parent::__construct(); // Memanggil konstruktor dari CI_Model
        $this->load->database(); // Memastikan database di-load
    }

    public function get_pendapatan() {
        return $this->db->order_by('tanggal', 'DESC')->get('t_pendapatan')->result();
    }

    public function get_output() {
        $this->db->select('m_output.*, m_program.id');
        $this->db->from('m_output');
        $this->db->join('m_program', 'm_program.id = m_output.program', 'left');
        return $this->db->get()->result();
    }

    public function getUangMasuk($startDate = null, $endDate = null) {
        $this->db->select_sum('jumlah2');
        $this->db->where('jenis', 'Cash'); // Hanya ambil data dengan jenis "Cash"
        if ($startDate && $endDate) {
            $this->db->where('tanggal >=', $startDate);
            $this->db->where('tanggal <=', $endDate);
        }
        $query = $this->db->get('t_rek_pengeluaran');
        return $query->row()->jumlah2 ?: 0;
    }
    
    public function getUangKeluar($startDate = null, $endDate = null) {
        $this->db->select_sum('jumlah');
        if ($startDate && $endDate) {
            $this->db->where('tanggal >=', $startDate);
            $this->db->where('tanggal <=', $endDate);
        }
        $query = $this->db->get('trx_pengeluaran');
        return $query->row()->total ?: 0;
    }
    
    public function getLaporanData($startDate, $endDate, $outputFilter = null) {
        // Ambil data dari trx_pengeluaran
        $queryTrx = $this->db->select('trx_pengeluaran.*, m_pajak.nama_pajak, m_output.kode, m_program.program')
                             ->from('trx_pengeluaran')
                             ->join('m_pajak', 'm_pajak.pajak_id = trx_pengeluaran.pajak_id', 'left')
                             ->join('m_output', 'm_output.kode = trx_pengeluaran.output', 'left')
                             ->join('m_program', 'm_program.id = trx_pengeluaran.program', 'left')
                             ->where('tanggal >=', $startDate)
                             ->where('tanggal <=', $endDate);
    
        if ($outputFilter) {
            $this->db->where('trx_pengeluaran.output', $outputFilter);
        }
    
        $dataTrx = $this->db->get()->result();
        return $dataTrx;
    }

    public function getRekPengeluaran($startDate, $endDate) {
        // Ambil data dari t_rek_pengeluaran (uang masuk)
        $queryRek = $this->db->select('*')
                             ->from('t_rek_pengeluaran')
                             ->where('jenis', 'Cash')                             
                             ->where('tanggal >=', $startDate)
                             ->where('tanggal <=', $endDate);
        $dataRek = $this->db->get()->result();
        return $dataRek;
    }
    public function getRekPengeluaran1($startDate, $endDate) {
        // Ambil data dari t_rek_pengeluaran (uang masuk)
        $queryRek = $this->db->select('*')
                             ->from('t_rek_pengeluaran')
                             ->where('jenis', 'Rekening')                             
                             ->where('tanggal >=', $startDate)
                             ->where('tanggal <=', $endDate);
        $dataRek = $this->db->get()->result();
        return $dataRek;
    }
    
    
    public function getLaporanData1($startDate, $endDate) {
        // Ambil data dari trx_pengeluaran
        $queryTrx = $this->db->select('trx_pengeluaran.*, m_pajak.nama_pajak, m_pajak.persentase, m_output.kode, m_program.program'); // Pilih semua kolom dari pengeluaran dan nama_pajak dari tabel pajak
        $this->db->from('trx_pengeluaran');
        $this->db->join('m_pajak', 'm_pajak.pajak_id = trx_pengeluaran.pajak_id', 'left'); // Gabungkan dengan tabel pajak
        $this->db->join('m_akun', 'm_akun.akun_id = trx_pengeluaran.akun_id', 'left');
        $this->db->join('m_output', 'm_output.kode = trx_pengeluaran.output', 'left');
        $this->db->join('m_program', 'm_program.id = trx_pengeluaran.program', 'left');
        $this->db->order_by('tanggal', 'DESC'); // Urutkan berdasarkan tanggal
        return $this->db->get()->result(); // Ambil hasilnya
                             $this->db->where('tanggal >=', $startDate)
                             ->where('tanggal <=', $endDate)
                             ->get();
                             if ($outputFilter) {
                                $this->db->where('output', $outputFilter);  // Sesuaikan dengan field yang ada di tabel
                            }
        
        // Debug: Tampilkan query dan hasilnya
        echo $this->db->last_query(); // Menampilkan query terakhir
        var_dump($queryTrx->result()); // Menampilkan hasil query trx_pengeluaran
    
        // Ambil data dari t_rek_pengeluaran
        $queryRek = $this->db->select('*')
                             ->from('t_rek_pengeluaran')
                             ->where('tanggal >=', $startDate)
                             ->where('tanggal <=', $endDate)
                             ->get();
        // Debug: Tampilkan query dan hasilnya
        echo $this->db->last_query(); // Menampilkan query terakhir
        var_dump($queryRek->result()); // Menampilkan hasil query t_rek_pengeluaran
    
        // Gabungkan hasilnya
        $dataTrx = $queryTrx->result();
        $dataRek = $queryRek->result();
        $dataTrx1 = $queryTrx->result();
        $dataRek1 = $queryRek->result();
        
        // Gabungkan kedua data dan return
        return array_merge($dataTrx, $dataRek);
        return array_merge($dataTrx1, $dataRek1);
    }
    
    
}
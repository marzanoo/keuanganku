<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class m_dashboard3 extends CI_Model {

    public function __construct() {
        parent::__construct(); // Memanggil konstruktor dari CI_Model
        $this->load->database(); // Memastikan database di-load
    }

    public function get_pendapatan() {
        return $this->db->get('t_pendapatan')->result();
    }

    public function get_pengeluaran() {
        return $this->db->get('t_rek_pengeluaran')->result();
    }

    public function hitung_saldo($filter = 'total') {

        $total_pendapatan = 0;
        $total_pengeluaran = 0;
        // Jika filter adalah 'total', ambil seluruh jumlah dari t_rek_pengeluaran dan trx_pengeluaran
        if ($filter == 'total') {
            // Ambil total pendapatan seluruh jenis dana
            $this->db->select_sum('jumlah');
            $total_pendapatan = $this->db->get('t_pendapatan')->row()->jumlah ?? 0;

            // Ambil total pengeluaran seluruh jenis dana
            $this->db->select_sum('jumlah');
            $this->db->where('jenis', 'Rekening');
            $total_pengeluaran = $this->db->get('t_rek_pengeluaran')->row()->jumlah ?? 0;
        } else if ($filter == 'Rekening'){
            // Ambil total pendapatan seluruh jenis dana
            $this->db->select_sum('jumlah');
            $total_pendapatan = $this->db->get('t_pendapatan')->row()->jumlah ?? 0;

            // Ambil total pengeluaran seluruh jenis dana
            $this->db->select_sum('jumlah');
            $this->db->where('jenis', 'Rekening');
            $total_pengeluaran = $this->db->get('t_rek_pengeluaran')->row()->jumlah ?? 0;

        } else if ($filter == 'Cash'){
            
        }

        // Hitung saldo berdasarkan filter
        return $total_pendapatan - $total_pengeluaran;
    }

    public function hitung_penerimaan($filter = 'hari_ini') {
        $this->db->select_sum('jumlah');
    
        // Filter berdasarkan waktu
        if ($filter === 'hari_ini') {
            $this->db->where('DATE(tanggal)', date('Y-m-d'));
        } elseif ($filter === 'minggu_ini') {
            $senin = date('Y-m-d', strtotime('monday this week'));
            $minggu = date('Y-m-d', strtotime('sunday this week'));
            $this->db->where('DATE(tanggal) >=', $senin);
            $this->db->where('DATE(tanggal) <=', $minggu);
        } elseif ($filter === 'bulan_ini') {
            $bulan_awal = date('Y-m-01');
            $bulan_akhir = date('Y-m-t');
            $this->db->where('DATE(tanggal) >=', $bulan_awal);
            $this->db->where('DATE(tanggal) <=', $bulan_akhir);
        } else if ($filter == 'tahun_ini') {
            $this->db->where('YEAR(tanggal)', date('Y')); // Filter untuk tahun ini
        }
    
        $result = $this->db->get('t_pendapatan')->row();
        
        // Jika hasilnya null, maka 0
        return $result->jumlah ?? 0;
    }

    public function getFilteredData($filter) {
        $this->db->select('sumber, COUNT(sumber) as value');
        $this->db->from('t_pendapatan');

        if ($filter === 'hari_ini') {
            $this->db->where('DATE(tanggal)', date('Y-m-d'));
        } elseif ($filter === 'minggu_ini') {
            $senin = date('Y-m-d', strtotime('monday this week'));
            $minggu = date('Y-m-d', strtotime('sunday this week'));
            $this->db->where('DATE(tanggal) >=', $senin);
            $this->db->where('DATE(tanggal) <=', $minggu);
        } elseif ($filter === 'bulan_ini') {
            $bulan_awal = date('Y-m-01');
            $bulan_akhir = date('Y-m-t');
            $this->db->where('DATE(tanggal) >=', $bulan_awal);
            $this->db->where('DATE(tanggal) <=', $bulan_akhir);
        }

        $this->db->group_by('sumber');
        return $this->db->get()->result_array();
    }

    public function hitung_pengeluaran($filter = 'hari_ini') {
        // Tentukan rentang waktu berdasarkan filter
        $this->db->select_sum('jumlah');        
        $this->db->where('jenis', 'Rekening');
    
        // Filter berdasarkan waktu
        if ($filter === 'hari_ini') {
            $this->db->where('DATE(tanggal)', date('Y-m-d'));
        } elseif ($filter === 'minggu_ini') {
            $senin = date('Y-m-d', strtotime('monday this week'));
            $minggu = date('Y-m-d', strtotime('sunday this week'));
            $this->db->where('DATE(tanggal) >=', $senin);
            $this->db->where('DATE(tanggal) <=', $minggu);
        } elseif ($filter === 'bulan_ini') {
            $bulan_awal = date('Y-m-01');
            $bulan_akhir = date('Y-m-t');
            $this->db->where('DATE(tanggal) >=', $bulan_awal);
            $this->db->where('DATE(tanggal) <=', $bulan_akhir);
        } else if ($filter == 'tahun_ini') {
            $this->db->where('YEAR(tanggal)', date('Y')); // Filter untuk tahun ini
        }
    
        $result = $this->db->get('t_rek_pengeluaran')->row();
        
        // Jika hasilnya null, maka 0
        return $result->jumlah ?? 0;
    }
}
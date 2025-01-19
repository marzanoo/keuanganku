<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class m_dashboard2 extends CI_Model {

    public function __construct() {
        parent::__construct(); // Memanggil konstruktor dari CI_Model
        $this->load->database(); // Memastikan database di-load
    }

    public function get_pendapatan() {
        return $this->db->get('t_pendapatan')->result();
    }

    public function get_pengeluaran() {
        return $this->db->get('trx_pengeluaran')->result();
    }

    public function hitung_saldo($filter = 'total') {

        $total_pendapatan = 0;
        $total_pengeluaran = 0;
        // Jika filter adalah 'total', ambil seluruh jumlah dari t_rek_pengeluaran dan trx_pengeluaran
        if ($filter == 'total') {
            // Ambil total pendapatan seluruh jenis dana
            $this->db->select_sum('jumlah2');
            $this->db->where('jenis', 'Rekening');
            $total_pendapatan = $this->db->get('t_rek_pengeluaran')->row()->jumlah2 ?? 0;

            // Ambil total pengeluaran seluruh jenis dana
            $this->db->select_sum('jumlah');
            $total_pengeluaran = $this->db->get('trx_pengeluaran')->row()->jumlah ?? 0;
        } else if ($filter == 'Rekening'){
            // Filter berdasarkan jenis dana (rekening atau cash)
            $this->db->select_sum('jumlah2');
            $this->db->where('jenis', 'Rekening'); // Filter berdasarkan jenis dana
            $total_pendapatan_rek = $this->db->get('t_rek_pengeluaran')->row()->jumlah2 ?? 0;

            // Filter berdasarkan jenis dana (rekening atau cash)
            $this->db->select_sum('jumlah2');
            $this->db->where('jenis', 'Cash'); // Filter berdasarkan jenis dana
            $total_pendapatan_cash = $this->db->get('t_rek_pengeluaran')->row()->jumlah2 ?? 0;

            // Ambil total pengeluaran berdasarkan jenis dana (rekening atau cash)
            $this->db->select_sum('jumlah');
            $this->db->where('jenis', 'Rekening'); // Filter berdasarkan jenis dana
            $total_pengeluaran = $this->db->get('trx_pengeluaran')->row()->jumlah ?? 0;

            $total_pendapatan = $total_pendapatan_rek - $total_pendapatan_cash;
        } else if ($filter == 'Cash'){
            // Filter berdasarkan jenis dana (rekening atau cash)
            $this->db->select_sum('jumlah2');
            $this->db->where('jenis', 'Cash'); // Filter berdasarkan jenis dana
            $total_pendapatan = $this->db->get('t_rek_pengeluaran')->row()->jumlah2 ?? 0;

            // Ambil total pengeluaran berdasarkan jenis dana (rekening atau cash)
            $this->db->select_sum('jumlah');
            $this->db->where('jenis', 'Cash'); // Filter berdasarkan jenis dana
            $total_pengeluaran = $this->db->get('trx_pengeluaran')->row()->jumlah ?? 0;
        }

        // Hitung saldo berdasarkan filter
        return $total_pendapatan - $total_pengeluaran;
    }
    
    public function getFilteredData($filter) {
        $this->db->select('output, COUNT(output) as value');
        $this->db->from('trx_pengeluaran');

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

        $this->db->group_by('output');
        return $this->db->get()->result_array();
    }

    public function hitung_pengeluaran($filter = 'hari_ini') {
        // Tentukan rentang waktu berdasarkan filter
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
    
        $result = $this->db->get('trx_pengeluaran')->row();
        
        // Jika hasilnya null, maka 0
        return $result->jumlah ?? 0;
    }
    

    public function hitung_penerimaan($filter = 'hari_ini') {
        $this->db->select_sum('jumlah2');
        $this->db->where('jenis', 'Rekening'); // Filter berdasarkan jenis Rekening
    
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
        return $result->jumlah2 ?? 0;
    }
    
}
<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard1 extends CI_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('m_dashboard1');
        $this->load->library('session');
    }

	public function index()
	{
		$data['pendapatan'] = $this->m_dashboard1->get_pendapatan();
		$data['saldo_keseluruhan'] = $this->m_dashboard1->hitung_saldo();

		$this->db->select_sum('jumlah');
		$data['total_pengeluaran'] = $this->db->get('trx_pengeluaran')->row()->jumlah;

		$this->db->select_sum('jumlah');
        $data['total'] = $this->db->get('t_pendapatan')->row()->jumlah;

		check_not_login();
		$this->template->load('template', 'v_dashboard1', $data);
	}

	public function hitung_saldo() {
		$postData = json_decode(file_get_contents('php://input'), true); // Ambil data JSON
		$filter = $postData['filter'] ?? 'total'; // Default ke 'total' jika tidak ada filter
	
		// Panggil model untuk menghitung saldo berdasarkan filter
		$this->load->model('m_dashboard1');
		$saldo = $this->m_dashboard1->hitung_saldo($filter);
	
		// Kirim hasil ke view dalam bentuk JSON
		echo json_encode(['saldo' => $saldo]);
	}

	public function hitung_pengeluaran() {
		$postData = json_decode(file_get_contents('php://input'), true); // Ambil data JSON
		$filter = $postData['filter'] ?? 'hari_ini'; // Default ke 'total' jika tidak ada filter
		
		// Panggil model untuk menghitung pengeluaran berdasarkan filter
		$this->load->model('m_dashboard1');
		$pengeluaran = $this->m_dashboard1->hitung_pengeluaran($filter);
	
		// Kirim hasil ke view dalam bentuk JSON
		echo json_encode(['pengeluaran' => $pengeluaran]);
	}

	public function getFilteredData()
	{
		$postData = json_decode(file_get_contents('php://input'), true); // Ambil data JSON
		$filter = $postData['filter'] ?? 'hari_ini';

		// Load model
		$this->load->model('m_dashboard1');

		// Ambil data dari model
		$data = $this->m_dashboard1->getFilteredData($filter);

		// Kembalikan data sebagai JSON
		echo json_encode($data);
	}
}

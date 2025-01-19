<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard2 extends CI_Controller {
	
	public function __construct() {
        parent::__construct();
        $this->load->model('m_dashboard2');
        $this->load->library('session');
    }

	public function index()
	{
		$data['pendapatan'] = $this->m_dashboard2->get_pendapatan();
		$data['saldo_keseluruhan'] = $this->m_dashboard2->hitung_saldo();

		$this->db->select('output, COUNT(output) as value');
		$this->db->from('trx_pengeluaran');
		$this->db->group_by('output');
		$this->db->order_by('value', 'DESC'); // Urutkan berdasarkan value, descending
		$data['alokasi_pengeluaran'] = $this->db->get()->result_array(); // Mengambil semua data


		$this->db->select_sum('jumlah');
		$data['total_pengeluaran'] = $this->db->get('trx_pengeluaran')->row()->jumlah;

		$this->db->select_sum('jumlah2');
		$this->db->where('jenis', 'Rekening');
        $data['total'] = $this->db->get('t_rek_pengeluaran')->row()->jumlah2;

		check_not_login();
		$this->template->load('template', 'v_dashboard2', $data);
	}

	public function getFilteredData()
	{
		$postData = json_decode(file_get_contents('php://input'), true); // Ambil data JSON
		$filter = $postData['filter'] ?? 'hari_ini';

		// Load model
		$this->load->model('m_dashboard2');

		// Ambil data dari model
		$data = $this->m_dashboard2->getFilteredData($filter);

		// Kembalikan data sebagai JSON
		echo json_encode($data);
	}

	public function hitung_saldo() {
		$postData = json_decode(file_get_contents('php://input'), true); // Ambil data JSON
		$filter = $postData['filter'] ?? 'total'; // Default ke 'total' jika tidak ada filter
	
		// Panggil model untuk menghitung saldo berdasarkan filter
		$this->load->model('m_dashboard2');
		$saldo = $this->m_dashboard2->hitung_saldo($filter);
	
		// Kirim hasil ke view dalam bentuk JSON
		echo json_encode(['saldo' => $saldo]);
	}
	
	public function hitung_penerimaan() {
		// Ambil filter dari request
		$postData = json_decode(file_get_contents('php://input'), true); // Ambil data JSON
		$filter = $postData['filter'] ?? 'hari_ini'; // Default ke 'total' jika tidak ada filter
	
		// Panggil model untuk menghitung penerimaan berdasarkan filter
		$this->load->model('m_dashboard2');
		$penerimaan = $this->m_dashboard2->hitung_penerimaan($filter);
	
		// Kirim hasil ke view dalam bentuk JSON
		echo json_encode(['penerimaan' => $penerimaan]);
	}

	public function hitung_pengeluaran() {
		$postData = json_decode(file_get_contents('php://input'), true); // Ambil data JSON
		$filter = $postData['filter'] ?? 'hari_ini'; // Default ke 'total' jika tidak ada filter

		// Panggil model untuk menghitung pengeluaran berdasarkan filter
		$this->load->model('m_dashboard2');
		$pengeluaran = $this->m_dashboard2->hitung_pengeluaran($filter);
	
		// Kirim hasil ke view dalam bentuk JSON
		echo json_encode(['pengeluaran' => $pengeluaran]);
	}
}

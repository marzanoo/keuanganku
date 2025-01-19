<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Pengguna extends CI_Controller {
	
	function __construct()
	{
		parent::__construct(); 
		check_not_login();
		check_admin();
		$this->load->model('m_pengguna');
		$this->load->library('form_validation');
	}

	public function index()
	{
		$data['row'] = $this->m_pengguna->get();
		$this->template->load('template', 'pengguna/v_data_pengguna', $data);
	}

	public function tambah()
	{
		$this->form_validation->set_rules('f_NIP', 'NIP', 'required|max_length[18]|is_unique[t_pengguna.NIP]');
		$this->form_validation->set_rules('f_sandi', 'Sandi', 'required');
		$this->form_validation->set_rules('f_konfirmasi', 'Konfirmasi sandi', 'required|matches[f_sandi]', 
			array('matches' => '%s tidak sesuai.')
		);
		$this->form_validation->set_rules('f_nama', 'Nama', 'required');
		$this->form_validation->set_rules('f_email', 'Email', 'required');
		$this->form_validation->set_rules('f_level', 'Level', 'required');

		$this->form_validation->set_message('is_unique', '{field} ini sudah dipakai, silahkan ganti.');
		$this->form_validation->set_message('max_length', '{field} maksimal 18 karakter.');
		$this->form_validation->set_message('required', '{field} masih kosong, silahkan isi.');

		if ($this->form_validation->run() == FALSE) {
			$this->template->load('template', 'pengguna/v_tambah_pengguna');
		} else {
			$post = $this->input->post(null, TRUE );
			$this->m_pengguna->add($post);
			if($this->db->affected_rows() > 0) {
				echo "<script>alert('Data berhasil disimpan.');</script>";
			}
			echo "<script>window.location='".site_url('Pengguna')."';</script>";
		}	
	}

	public function ubah($id)
	{
		// $this->form_validation->set_rules('f_NIP', 'NIP', 'required|max_length[18]');
		// if ($this->input->post('f_sandi')) {
		// $this->form_validation->set_rules('f_sandi', 'Sandi');
		// $this->form_validation->set_rules('f_konfirmasi', 'Konfirmasi sandi', 'matches[f_sandi]', 
		// 	array('matches' => '%s tidak sesuai.')
		// );
		// }
		// if ($this->input->post('f_konfirmasi')) {
		// $this->form_validation->set_rules('f_konfirmasi', 'Konfirmasi sandi', 'matches[f_sandi]', 
		// 	array('matches' => '%s tidak sesuai.')
		// );
		// }
		// $this->form_validation->set_rules('f_nama', 'Nama', 'required');
		// $this->form_validation->set_rules('f_email', 'Email', 'required');
		// $this->form_validation->set_rules('f_level', 'Level', 'required');

		// $this->form_validation->set_message('is_unique', '{field} ini sudah dipakai, silahkan ganti.');
		// $this->form_validation->set_message('max_length', '{field} maksimal 18 karakter.');
		// $this->form_validation->set_message('required', '{field} masih kosong, silahkan isi.');

		if ($this->form_validation->run() == FALSE) {
		$query = $this->m_pengguna->get($id);
		if($query->num_rows() > 0) {
		$data['row'] = $query->row();
		$this->template->load('template', 'pengguna/v_ubah_pengguna', $data);
			} else {
				echo "<script>alert('Data tidak ditemukan.');";
				echo "window.location='".site_url('Pengguna')."';</script>";
			}
		} else {
			$post = $this->input->post(null, TRUE );
			$this->m_pengguna->edit($post);
			if($this->db->affected_rows() > 0) {
				echo "<script>alert('Data berhasil diubah.');</script>";
			}
			echo "<script>window.location='".site_url('Pengguna')."';</script>";
		}	
	}

	public function hapus()
	{
		$id = $this->input->post('f_NIP');
		$this->m_pengguna->delete($id);

		if($this->db->affected_rows() > 0) {
				echo "<script>alert('Data berhasil dihapus.');</script>";
			}
			echo "<script>window.location='".site_url('Pengguna')."';</script>";
	}
}

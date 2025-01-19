<?php

class Fungsi
{
	protected $ci;

	function __construct() {
		$this->ci = &get_instance();
	}

	function user_login() {
		$this->ci->load->model('m_pengguna');
		$NIP = $this->ci->session->userdata('NIP');
		$user_data = $this->ci->m_pengguna->get($NIP)->row();
		return $user_data;
	}

}
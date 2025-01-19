<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Lap_pajak extends CI_Controller {
	
	public function index()
	{
		check_not_login();
		$this->template->load('template', 'laporan_pajak/v_laporan_pajak');
	}
}

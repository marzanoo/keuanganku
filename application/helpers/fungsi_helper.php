<?php
function check_already_login()
{
	$ci = &get_instance();
	$user_session = $ci->session->userdata('NIP');
	if ($user_session) {
		// Sesuaikan rute ini
		redirect('auth/login');
	}
}


function check_not_login()
{
	$ci = &get_instance();
	$user_session = $ci->session->userdata('NIP');
	if (!$user_session) {
		redirect('auth/login');
	}
}

function check_admin()
{
	$ci = &get_instance();
	$ci->load->library('fungsi');
	if ($ci->fungsi->user_login()->level != 1) {
		$ci->session->set_flashdata('alert', 'Anda tidak memiliki akses ke tindakan ini.');
		redirect('dashboard');
	}
}

function check_bendahara_pengeluaran() {
	$ci = &get_instance();
	$ci->load->library('fungsi');
	if ($ci->fungsi->user_login()->level != 2 && $ci->fungsi->user_login()->level != 1) {
		$ci->session->set_flashdata('alert', 'Anda tidak memiliki akses ke tindakan ini.');
		redirect('dashboard');
	}
}

function check_bendahara_penerimaan() {
	$ci = &get_instance();
	$ci->load->library('fungsi');
	if ($ci->fungsi->user_login()->level != 3 && $ci->fungsi->user_login()->level != 1) {
		$ci->session->set_flashdata('alert', 'Anda tidak memiliki akses ke tindakan ini.');
		redirect('dashboard');
	}
}

// function check_auditor()
// {
// 	$ci = &get_instance();
// 	$ci->load->library('fungsi');
// 	if ($ci->fungsi->user_login()->level != 2) {
// 		$ci->session->set_flashdata('alert', 'Anda tidak memiliki akses ke tindakan ini.');
// 		redirect('dashboard');
// 	}
// }

// function check_auditi()
// {
// 	$ci = &get_instance();
// 	$ci->load->library('fungsi');
// 	if ($ci->fungsi->user_login()->level != 3) {
// 		$ci->session->set_flashdata('alert', 'Anda tidak memiliki akses ke tindakan ini.');
// 		redirect('dashboard');
// 	}
// }

// function check_manajemen_or_admin()
// {
// 	$ci = &get_instance();
// 	$ci->load->library('fungsi');
// 	$level = $ci->fungsi->user_login()->level;
// 	if ($level != 1 && $level != 4) {
// 		$ci->session->set_flashdata('alert', 'Anda tidak memiliki akses ke tindakan ini.');
// 		redirect('dashboard');
// 	}
// }

// function check_admin_or_auditor()
// {
// 	$ci = &get_instance();
// 	$ci->load->library('fungsi');
// 	$user_level = $ci->fungsi->user_login()->level;
// 	if ($user_level != 1 && $user_level != 2) {
// 		$ci->session->set_flashdata('alert', 'Anda tidak memiliki akses ke tindakan ini.');
// 		redirect('dashboard');
// 	}
// }

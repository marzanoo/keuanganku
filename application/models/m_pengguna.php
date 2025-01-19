<?php defined('BASEPATH') OR exit('No direct script access allowed');

class m_pengguna extends CI_Model {
	
	public function login($post)
	{
		$this->db->select('*');
		$this->db->from('t_pengguna');
		$this->db->where('NIP', $post['f_NIP']);
		$this->db->where('sandi', sha1($post['f_sandi']));
		$query = $this->db->get();
		return $query;
	}

	public function login_from_intra_byNip($Nip)
	{
		$this->db->select('*');
		$this->db->from('t_pengguna');
		$this->db->where('NIP', $Nip);
		$query = $this->db->get();
		return $query;
	}

	public function get($id = null)
	{
		$this->db->from('t_pengguna');
		if($id != null) {
			$this->db->where('NIP', $id);
		}
		$query = $this->db->get();
		return $query;
	}

	public function add($post)
	{
		$params['NIP'] = $post['f_NIP'];
		$params['sandi'] = sha1($post['f_sandi']);
		$params['nama'] = $post['f_nama'];
		$params['email'] = $post['f_email'];
		$params['level'] = $post['f_level'];
		$this->db->insert('t_pengguna', $params);
	}

	public function edit($post)
	{
	    $params = [
	        'NIP' => $post['f_NIP'],
	        'nama' => $post['f_nama'],
	        'email' => $post['f_email'],
	        'level' => $post['f_level']
	    ];
	    if (!empty($post['f_sandi'])) {
	        $params['sandi'] = sha1($post['f_sandi']);
	    }
	    $this->db->where('NIP', $post['f_NIP']);
	    $this->db->update('t_pengguna', $params);
	}

	public function delete($id)
	{
		$this->db->where('NIP', $id);
		$this->db->delete('t_pengguna');
	}

}
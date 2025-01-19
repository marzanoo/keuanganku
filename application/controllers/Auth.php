<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function login()
	{
		check_already_login();
		$this->load->view('v_login');
	}
    // Fungsi untuk decrypt
    private function decryptText($encryptedText, $key = "inikeynya") {
        $cipher = 'aes-256-cbc';
        $data = base64_decode($encryptedText);
        $ivLength = openssl_cipher_iv_length($cipher);
        $iv = substr($data, 0, $ivLength);
        $encrypted = substr($data, $ivLength);
        $decrypted = openssl_decrypt($encrypted, $cipher, $key, 0, $iv);
        if ($decrypted === false) {
            die("Dekripsi gagal!");
        }
        return $decrypted;
    }

    public function process()
    {
        $post = $this->input->post(null, TRUE);
        if (isset($post['login'])) {
            $this->load->model('m_pengguna');
            $query = $this->m_pengguna->login($post);
            if ($query->num_rows() > 0) {
                $row = $query->row();
                $params = array(
                    'NIP' => $row->NIP,
                    'level' => $row->level
                );
                $this->session->set_userdata($params);

                // Redirect berdasarkan level
                $level = $row->level;
                if ($level == 1) {
                    redirect('dashboard1');
                } elseif ($level == 2) {
                    redirect('dashboard2');
                } elseif ($level == 3) {
                    redirect('dashboard3');
                } else {
                    echo "<script>
                        alert('Galat, level tidak dikenali!');
                        window.location='" . site_url('auth/login') . "';
                    </script>";
                }
            } else {
                echo "<script>
                    alert('Galat, login gagal. Periksa NIP dan sandi anda!');
                    window.location='" . site_url('auth/login') . "';
                </script>";
            }
        }
    }

    public function process2($Nipfromurl) {
       
        $nip = $this->decryptText($Nipfromurl); // Dekripsi menggunakan fungsi decryptText
        if (!$nip) {
            show_error("Dekripsi NIP gagal!", 403);
        }

        // Ambil NIP terenkripsi dari database berdasarkan NIP yang ada di session
        $Nipfromdb = $this->db->select('NIP')
                                 ->from('t_pengguna')
                                 //->where('NIP', $Nipfromurl)  // Menggunakan NIP yang disimpan di session
                                 ->get()->where('NIP', $nip)
                                 ->row(); // Mengambil satu baris data
    
        // Cek jika query menghasilkan data
        if (!$Nipfromdb) {
            show_error("NIP terenkripsi tidak ditemukan!", 403);
        }
    
    
        // Periksa NIP di database
        $this->load->model('m_pengguna');
        $query = $this->m_pengguna->login_from_intra_byNip($Nipfromdb);
        if ($query->num_rows() > 0) {
            $row = $query->row();
    
            // Simpan session
            $params = [
                'NIP' => $row->NIP,
                'level' => $row->level
            ];
            $this->session->set_userdata($params);
    
            // Redirect ke dashboard berdasarkan level
            switch ($row->level) {
                case 1:
                    redirect('dashboard1'); // Admin
                    break;
                case 2:
                    redirect('dashboard2'); // Bendahara Pengeluaran
                    break;
                case 3:
                    redirect('dashboard3'); // Bendahara Penerimaan
                    break;
                default:
                    show_error("Level user tidak dikenali!", 403);
            }
        } else {
            show_error("NIP tidak ditemukan di database!", 403);
        }
    }
    

    public function logout() {
        $this->session->unset_userdata(['NIP', 'level']);
        redirect('auth/login');
    }
}

<?php
class auth extends CI_Controller{
    
    function __construct() {
        parent::__construct();
        $this->load->model('model_operator');
    }
    
    function login()
    {
        if(isset($_POST['submit'])){
            
            // proses login disini
            $username   =   $this->input->post('username');
            $password   =   $this->input->post('password');
            $hasil=  $this->model_operator->login($username,$password);
            if($hasil==1)
            {
                // update last login
                $this->db->where('username',$username);
                $this->db->update('operator',array('last_login'=>date('Y-m-d')));
                $this->session->set_userdata(array('status_login'=>'oke','username'=>$username));
                redirect('dashboard');
            }
            else{
                redirect('auth/login');
            }
        }
        else{
            //$this->load->view('form_login2');
            //chek_session_login();
            $this->load->view('form_login');
        }
    }
	
    function edit()
    {
        if (isset($_POST['submit'])) {
            $username      = $this->input->post('username');
            $password      = $this->input->post('password');
            $password_new  = $this->input->post('password_new');

            $hasil = $this->model_operator->login($username, $password);

            if ($hasil == 1) {
                // update password
                $this->db->where('username', $username);
                $this->db->update('operator', ['password' => md5($password_new)]);

                echo "<script>alert('✅ Reset password berhasil! Silakan login kembali.'); window.location='".base_url('auth/login')."';</script>";
            } else {
                echo "<script>alert('❌ Username atau password lama salah!'); window.location='".base_url('auth/form_reset')."';</script>";
            }
        } else {
            $this->load->view('form_reset');
        }
    }
    	
	function form_reset()
    {
        $this->load->view('form_reset');
    }
	
	function registrasi()
    {
        $this->load->view('form_regis');
    }
	
	function regis()
    {
        if(isset($_POST['submit'])){
            // proses data
            $nama       =  $this->input->post('nama',true);
            $username   =  $this->input->post('username',true);
            $password   =  $this->input->post('password',true);
			$tanggal	=  date('Y-m-d');
            $data       =  array(   'nama_lengkap'=>$nama,
                                    'username'=>$username,
                                    'password'=>md5($password),
									'last_login'=>$tanggal);
            $this->db->insert('operator',$data);
            redirect('auth/login');
        }
        else{
            //$this->load->view('operator/form_input');
			$this->load->view('form_regis');
        }
    }
    
    function logout()
    {
        $this->session->sess_destroy();
        redirect('auth/login');
    }
}
?>
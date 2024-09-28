<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('session');
        $this->load->helper(array('form', 'url'));
        $this->load->database();
    }

    public function index()
    {
        $this->load->view('auth/login_view');
    }

    public function process()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('auth/login_view');
        } else {
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            $checkUser = $this->User_model->checkLogin($username, $password);

            if ($checkUser) {
                $session_data = [
                    'username' => $checkUser->username,
                    'name' => $checkUser->name,
                    'logged_in' => TRUE,
                ];
                $this->session->set_userdata($session_data);
                redirect('dashboard');
            } else {
                $data['error'] = "Username atau Password salah!";
                $this->load->view('auth/login_view', $data);
            }
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('login');
    }

    public function create()
    {
        $dummy_users = [
            ['username' => 'andika123', 'name' => 'Andika Pratama'],
            ['username' => 'sari234', 'name' => 'Sari Ramadhani'],
            ['username' => 'budi456', 'name' => 'Budi Santoso'],
            ['username' => 'ratna789', 'name' => 'Ratna Sari'],
            ['username' => 'putra321', 'name' => 'Putra Wibowo'],
            ['username' => 'dian654', 'name' => 'Dian Kusuma'],
            ['username' => 'agus987', 'name' => 'Agus Setiawan'],
            ['username' => 'intan741', 'name' => 'Intan Permata'],
            ['username' => 'joni852', 'name' => 'Joni Saputra'],
            ['username' => 'lia963', 'name' => 'Lia Kartika'],
        ];

        // Hash password menggunakan bcrypt

        // Generate SQL untuk setiap user
        foreach ($dummy_users as $user) {
            $password = password_hash('qweqweqwe', PASSWORD_BCRYPT);
            // echo "INSERT INTO users (username, password, name) VALUES ('{$user['username']}', '$password', '{$user['name']}');\n";
            $data = [
                'username' => $user['username'],
                'password' => $password,
                'name' => $user['name'],
            ];
            $this->db->insert('users', $data);
        }
    }
}

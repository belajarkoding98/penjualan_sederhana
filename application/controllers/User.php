<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('session');
        $this->load->helper(array('form', 'url'));
        $this->load->database();
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
    }

    public function index()
    {
        $data['title'] = "Daftar User";
        $data['content'] = $this->load->view('user/index', '', TRUE);
        $this->load->view('layout/mainlayout_view', $data);
    }

    public function list()
    {
        $this->output_json($this->User_model->getAll(), false);
    }

    public function store()
    {
        $data = [
            'name' => $this->input->post('name'),
            'username' => $this->input->post('username'),
            'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT)
        ];
        $checkUser = $this->User_model->checkUser($this->input->post('username'));
        if (!$checkUser) {
            $this->User_model->insert($data);
            echo json_encode(['status' => true, 'message' => 'Data berhasil ditambahkan.', 'icon' => 'success', 'title' => 'Sukses']);
        } else {
            echo json_encode(['status' => true, 'message' => 'Data Gagal ditambahkan. Username sudah terdaftar', 'icon' => 'warning', 'title' => 'Gagal']);
        }
    }

    public function edit($id)
    {
        $data = $this->User_model->getId($id);
        echo json_encode($data);
    }

    public function update()
    {
        $checkUser = $this->User_model->checkUser($this->input->post('username'));
        $data = array(
            'name' => $this->input->post('name'),
            'username' => $this->input->post('username'),
        );
        if ($this->input->post('password')) {
            $data['password'] = password_hash($this->input->post('password'), PASSWORD_BCRYPT);
        }
        $this->User_model->update(array('id' => $this->input->post('id')), $data);
        echo json_encode(array("status" => true, 'message' => 'Data berhasil diubah.', 'icon' => 'success', 'title' => 'Sukses'));
    }

    public function delete($id)
    {
        $this->User_model->delete($id);
        echo json_encode(['status' => true]);
    }

    public function output_json($data, $encode = true)
    {
        if ($encode) $data = json_encode($data);
        $this->output->set_content_type('application/json')->set_output($data);
    }
}

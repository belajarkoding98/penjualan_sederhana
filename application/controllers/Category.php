<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Category extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Category_model');
        $this->load->library('session');
        $this->load->helper(array('form', 'url'));
        $this->load->database();
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
    }

    public function index()
    {
        $data['title'] = "Daftar Kategori";
        $data['content'] = $this->load->view('category/index', '', TRUE);
        $this->load->view('layout/mainlayout_view', $data);
    }

    public function list()
    {
        $this->output_json($this->Category_model->getAll(), false);
    }

    public function store()
    {
        $data = [
            'name' => $this->input->post('name'),
            'description' => $this->input->post('description')
        ];
        $this->Category_model->insert($data);
        echo json_encode(['status' => true, 'message' => 'Data berhasil ditambahkan.']);
    }

    public function edit($id)
    {
        $data = $this->Category_model->getId($id);
        echo json_encode($data);
    }

    public function update()
    {
        $data = array(
            'name' => $this->input->post('name'),
            'description' => $this->input->post('description'),
        );
        $this->Category_model->update(array('id' => $this->input->post('id')), $data);
        echo json_encode(array("status" => true, 'message' => 'Data berhasil diubah.'));
    }

    public function delete($id)
    {
        $this->Category_model->delete($id);
        echo json_encode(['status' => true]);
    }

    public function output_json($data, $encode = true)
    {
        if ($encode) $data = json_encode($data);
        $this->output->set_content_type('application/json')->set_output($data);
    }
}

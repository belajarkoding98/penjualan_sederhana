<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Customer extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Customer_model');
        $this->load->library('session');
        $this->load->helper(array('form', 'url'));
        $this->load->database();
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
    }

    public function index()
    {
        $data['title'] = "Daftar Konsumen";
        $data['content'] = $this->load->view('customer/index', '', TRUE);
        $this->load->view('layout/mainlayout_view', $data);
    }

    public function list()
    {
        $this->output_json($this->Customer_model->getAll(), false);
    }

    public function store()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('email', 'email', 'required');
        $this->form_validation->set_rules('phone', 'phone', 'required');
        $this->form_validation->set_rules('address', 'address', 'nullable');

        if ($this->form_validation->run()) {
            echo json_encode(['error' => validation_errors()]);
        } else {

            $data = array(
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone'),
                'address' => $this->input->post('address')
            );
            $this->Customer_model->insert($data);

            echo json_encode(['status' => true, 'message' => 'Data berhasil ditambahkan.']);
        }
    }

    public function edit($id)
    {
        $data = $this->Customer_model->getId($id);
        echo json_encode($data);
    }

    public function update()
    {
        $data = array(
            'name' => $this->input->post('name'),
            'email' => $this->input->post('email'),
            'phone' => $this->input->post('phone'),
            'address' => $this->input->post('address'),
        );
        $this->Customer_model->update(array('id' => $this->input->post('id')), $data);
        echo json_encode(array("status" => true, 'message' => 'Data berhasil diubah.'));
    }

    public function delete($id)
    {
        $this->Customer_model->delete($id);
        echo json_encode(['status' => true]);
    }

    public function output_json($data, $encode = true)
    {
        if ($encode) $data = json_encode($data);
        $this->output->set_content_type('application/json')->set_output($data);
    }
}

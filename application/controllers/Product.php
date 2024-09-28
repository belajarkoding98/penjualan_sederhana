<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('Product_model', 'Category_model'));
        $this->load->library('session');
        $this->load->helper(array('form', 'url'));
        $this->load->database();
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
    }

    public function index()
    {
        $data['title'] = "Daftar Produk";
        $data['categories'] = $this->Category_model->getCategories();
        // var_dump();
        $data['content'] = $this->load->view('product/index', $data, TRUE);
        $this->load->view('layout/mainlayout_view', $data);
    }

    public function store()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('price', 'Price', 'required');
        $this->form_validation->set_rules('category_id', 'Category', 'required');
        $this->form_validation->set_rules('photo', 'Photo', 'nullable');

        if ($this->form_validation->run()) {
            $config['upload_path'] = './uploads/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size'] = 2048;

            $this->load->library('upload', $config);

            if (!empty($_FILES['photo']['name'])) {
                if (!$this->upload->do_upload('photo')) {
                    $error = $this->upload->display_errors();
                    echo json_encode(['error' => $error]);
                } else {
                    $file_data = $this->upload->data();
                    $photo = $file_data['file_name'];
                }
            } else {
                $photo = null;
            }

            $data = [
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'price' => $this->input->post('price'),
                'category_id' => $this->input->post('category_id'),
                'photo' => $photo ? $photo : null
            ];

            $this->Product_model->insert($data);
            echo json_encode(['success' => 'Product added successfully!']);
        } else {
            echo json_encode(['error' => validation_errors()]);
        }
    }

    public function edit($id)
    {
        $data = $this->Product_model->getId($id);
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

    public function list()
    {
        $products = $this->Product_model->getAll();
        echo json_encode($products);
    }

    public function delete($id)
    {
        $this->Product_model->delete($id);
        echo json_encode(['status' => true]);
    }

    public function output_json($data, $encode = true)
    {
        if ($encode) $data = json_encode($data);
        $this->output->set_content_type('application/json')->set_output($data);
    }
}

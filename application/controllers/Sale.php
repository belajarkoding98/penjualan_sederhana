<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sale extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('Sale_model', 'Product_model', 'Customer_model'));
        $this->load->library('session');
        $this->load->helper(array('form', 'url'));
        $this->load->database();
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
    }

    public function index()
    {
        $data['title'] = "Daftar Penjualan";
        $data['products'] = $this->Product_model->getProducts();
        $data['customers'] = $this->Customer_model->getCustomers();
        // var_dump();
        $data['content'] = $this->load->view('sale/index', $data, TRUE);
        $this->load->view('layout/mainlayout_view', $data);
    }

    public function store()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('customer_id', 'customer_id', 'required');
        $this->form_validation->set_rules('product_id', 'product_id', 'required');
        $this->form_validation->set_rules('quantity', 'quantity', 'required');

        if ($this->form_validation->run()) {
            $data = [
                'customer_id' => $this->input->post('customer_id'),
                'sale_date' => date('Y-m-d H:i:s'),
                'total_amount' => $this->input->post('price') * $this->input->post('quantity')
            ];
            // var_dump($data);
            // Simpan data penjualan di tabel sales
            $this->db->insert('sales', $data);
            $sale_id = $this->db->insert_id();

            // Simpan detail penjualan di tabel sale_details
            $detail_data = [
                'sale_id' => $sale_id,
                'product_id' => $this->input->post('product_id'),
                'quantity' => $this->input->post('quantity'),
                'price' => $this->input->post('price')
            ];
            $this->db->insert('sale_details', $detail_data);

            // Redirect or return response
            echo json_encode(['status' => 'success', 'message' => 'Product added successfully!']);
        } else {
            // Jika validasi gagal, menampilkan error
            echo json_encode(['status' => 'error', 'message' => validation_errors()]);
        }
    }

    public function edit($id)
    {
        $data = $this->Sale_model->getId($id);
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
        $products = $this->Sale_model->getAll();
        echo json_encode($products);
    }

    public function delete($id)
    {
        $this->Sale_model->delete($id);
        echo json_encode(['status' => true]);
    }

    public function show($sale_id)
    {
        $this->db->select('sales.*, customers.name as customer_name');
        $this->db->from('sales');
        $this->db->join('customers', 'sales.customer_id = customers.id');
        $this->db->where('sales.id', $sale_id);
        $sale = $this->db->get()->row_array();

        if ($sale) {
            $this->db->select('sale_details.*, products.name as product_name');
            $this->db->from('sale_details');
            $this->db->join('products', 'sale_details.product_id = products.id');
            $this->db->where('sale_details.sale_id', $sale_id);
            $details = $this->db->get()->result_array();

            $data = [
                'status' => 'success',
                'sale' => $sale,
                'details' => $details
            ];
        } else {
            $data = [
                'status' => 'error',
                'message' => 'Sale not found'
            ];
        }

        $this->output_json($data);
    }

    public function output_json($data, $encode = true)
    {
        if ($encode) $data = json_encode($data);
        $this->output->set_content_type('application/json')->set_output($data);
    }
}

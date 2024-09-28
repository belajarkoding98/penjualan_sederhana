<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('User_model', 'Product_model', 'Sale_model', 'Category_model', 'Customer_model'));
        $this->load->library('session');
        $this->load->helper(array('form', 'url'));
        $this->load->database();
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
    }

    public function index()
    {
        $data['title'] = "Dashboard";
        $data['productCount'] = $this->Product_model->get_product_count();
        $data['consumerCount'] = $this->Customer_model->get_consumer_count();
        $data['salesCount'] = $this->Sale_model->get_sales_count();
        $data['categoryCount'] = $this->Category_model->get_category_count();
        $data['content'] = $this->load->view('dashboard_view', $data, TRUE);
        $this->load->view('layout/mainlayout_view', $data);
    }
}

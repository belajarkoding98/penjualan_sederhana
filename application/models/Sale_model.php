<?php

class Sale_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database(); // Pastikan database diload
    }
    protected $table = 'sales';
    protected $id = 'id';

    public function getAll()
    {
        $this->db->select('
        sales.id as id, 
        sales.id as sale_id, 
        sales.sale_date, 
        sales.total_amount, 
        customers.name as customer_name, 
        customers.email, 
        customers.phone, 
        customers.address, 
        sale_details.quantity, 
        sale_details.price as sale_price, 
        products.name as product_name, 
        products.description as product_description, 
        products.photo, 
        categories.name as category_name
    ');

        $this->db->from('sales');
        $this->db->join('customers', 'sales.customer_id = customers.id', 'left');
        $this->db->join('sale_details', 'sales.id = sale_details.sale_id', 'left');
        $this->db->join('products', 'sale_details.product_id = products.id', 'left');
        $this->db->join('categories', 'products.category_id = categories.id', 'left');

        $query = $this->db->get();
        return $query->result();
    }
    public function insert($data)
    {
        $this->db->insert($this->table, $data);
    }

    public function update($where, $data)
    {
        $this->db->update($this->table, $data, $where);
    }

    public function getId($id)
    {
        return $this->db->get_where($this->table, array('id' => $id))->row();
    }

    public function get_sales_count()
    {
        return $this->db->count_all($this->table);
    }

    function delete($id)
    {
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
    }
}

<?php

class Product_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('datatables');
    }
    protected $table = 'products';
    protected $id = 'id';

    public function getAll()
    {
        $this->db->select('products.*, categories.name as category_name');
        $this->db->from('products');
        $this->db->join('categories', 'products.category_id = categories.id');
        $query = $this->db->get();
        return $query->result();
    }
    public function insert($data)
    {
        $this->db->insert($this->table, $data);
    }

    public function getProducts()
    {
        return $this->db->get('products')->result();
    }

    public function update($where, $data)
    {
        $this->db->update($this->table, $data, $where);
    }

    public function getId($id)
    {
        return $this->db->get_where($this->table, array('id' => $id))->row();
    }

    public function get_product_count()
    {
        return $this->db->count_all($this->table);
    }


    function delete($id)
    {
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
    }
}

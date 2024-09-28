<?php

class Category_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('datatables');
    }
    protected $table = 'categories';
    protected $id = 'id';

    public function getAll()
    {
        $this->datatables->select('id, name, description')
            ->from($this->table);
        return $this->datatables->generate();
    }

    public function getCategories()
    {
        return $this->db->get($this->table)->result();
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

    public function get_category_count()
    {
        return $this->db->count_all($this->table);
    }

    function delete($id)
    {
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
    }
}

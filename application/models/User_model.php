<?php

class User_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('datatables');
    }
    protected $table = 'users';
    protected $id = 'id';

    public function getAll()
    {
        $this->datatables->select('id, name, username')
            ->from($this->table);
        return $this->datatables->generate();
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

    function delete($id)
    {
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
    }

    public function checkLogin($username, $password)
    {
        $this->db->where('username', $username);
        $query = $this->db->get($this->table);
        $user = $query->row();

        if ($user && password_verify($password, $user->password)) {
            return $user;
        } else {
            return FALSE;
        }
    }

    public function checkUser($username)
    {
        $this->db->where('username', $username);
        $query = $this->db->get('users');

        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }
}

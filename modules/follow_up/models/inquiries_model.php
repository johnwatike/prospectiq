<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Inquiries_model extends App_Model
{
    protected $table;

    public function __construct()
    {
        parent::__construct();
        $this->table = db_prefix() . 'inquiries';
    }

    /**
     * Get a single row by ID or return all rows.
     * @param int|null $id
     * @return array
     */
    public function get($id = null)
    {
        if ($id) {
            return $this->db->get_where($this->table, ['id' => $id])->row_array();
        }
        return $this->db->get($this->table)->result_array();
    }

    /**
     * Add a new row to the table
     * @param array $data
     * @return int Inserted ID
     */
    public function add($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    /**
     * Update a row by ID
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    /**
     * Delete a row by ID
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        return $this->db->delete($this->table, ['id' => $id]);
    }
}

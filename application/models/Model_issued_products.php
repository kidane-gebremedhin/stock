<?php 

class Model_issued_products extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/* get the brand data */
	public function getIssuedProductData($id = null)
	{
		if($id) {
			$sql = "SELECT * FROM issued_products where id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}

		$sql = "SELECT * FROM issued_products ORDER BY id DESC";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function getActiveIssuedProductData()
	{
		$sql = "SELECT * FROM issued_products WHERE active = ? ORDER BY id DESC";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}

	public function create($data)
	{
		if($data) {
			$insert = $this->db->insert('issued_products', $data);
			return ($insert == true) ? true : false;
		}
	}

	public function update($data, $id)
	{
		if($data && $id) {
			$this->db->where('id', $id);
			$update = $this->db->update('issued_products', $data);
			return ($update == true) ? true : false;
		}
	}

	public function remove($id)
	{
		if($id) {
			$this->db->where('id', $id);
			$delete = $this->db->delete('issued_products');
			return ($delete == true) ? true : false;
		}
	}

	public function countTotalIssuedProducts()
	{
		$sql = "SELECT * FROM issued_products";
		$query = $this->db->query($sql);
		return $query->num_rows();
	}

}
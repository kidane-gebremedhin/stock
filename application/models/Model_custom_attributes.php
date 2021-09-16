<?php 

class Model_custom_attributes extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	// get the active atttributes data 
	public function getActiveAttributeData()
	{
		$sql = "SELECT * FROM custom_attributes WHERE active = ?";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}

	/* get the attribute data */
	public function getAttributeData($id = null)
	{
		if($id) {
			$sql = "SELECT * FROM custom_attributes where id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}

		$sql = "SELECT * FROM custom_attributes";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	/* get the attribute value data */
	// $id = attribute_parent_id
	public function getOrderCustomAttributeValuesData($order_id = null)
	{
		$sql = "SELECT * FROM order_custom_attribute_values WHERE order_id = ?";
		$query = $this->db->query($sql, array($order_id));
		return $query->result_array();
	}

	public function getOrderCustomAttributeValuesByAttributeId($order_id = null, $custom_attribute_id = null)
	{
		$sql = "SELECT * FROM order_custom_attribute_values WHERE order_id = ? && custom_attribute_id = ?";
		$query = $this->db->query($sql, array($order_id, $custom_attribute_id));
		return $query->row_array();
	}

	public function create($data)
	{
		if($data) {
			$insert = $this->db->insert('custom_attributes', $data);
			return ($insert == true) ? true : false;
		}
	}

	public function update($data, $id)
	{
		if($data && $id) {
			$this->db->where('id', $id);
			$update = $this->db->update('custom_attributes', $data);
			return ($update == true) ? true : false;
		}
	}

	public function remove($id)
	{
		if($id) {
			$this->db->where('id', $id);
			$delete = $this->db->delete('custom_attributes');
			return ($delete == true) ? true : false;
		}
	}

	public function createOrderCustomAttributeValue($data)
	{
		if($data) {
			$insert = $this->db->insert('order_custom_attribute_values', $data);
			return ($insert == true) ? true : false;
		}
	}

	public function updateOrderCustomAttributeValue($data, $id)
	{
		if($data && $id) {
			$this->db->where('id', $id);
			$update = $this->db->update('order_custom_attribute_values', $data);
			return ($update == true) ? true : false;
		}
	}

	public function removeValue($id)
	{
		if($id) {
			$this->db->where('id', $id);
			$delete = $this->db->delete('order_custom_attribute_values');
			return ($delete == true) ? true : false;
		}
	}

}
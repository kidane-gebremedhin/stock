<?php 

class Model_received_products extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('model_products');
        $this->load->model('model_users');
	}
	/* get the brand data */
	public function getReceivedProductData($id = null)
	{
		if($id) {
			$sql = "SELECT * FROM received_products where id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}


        $user_id = $this->session->userdata('id');
        $user_data = $this->model_users->getUserData($user_id);
        if($user_data['store_id']==0){
			$sql = "SELECT * FROM received_products ORDER BY id DESC";
			$query = $this->db->query($sql);
        }
        else{
			$sql = "SELECT * FROM received_products WHERE store_id=? ORDER BY id DESC";
			$query = $this->db->query($sql, array($user_data['store_id']));
		}

		return $query->result_array();
	}

	public function getActiveReceivedProductData($store_id=0)
	{
		if($store_id==0){
			$sql = "SELECT * FROM received_products WHERE active = ? ORDER BY id DESC";
			$query = $this->db->query($sql, array(1));
		}
		else{
			$sql = "SELECT * FROM received_products WHERE store_id = ? and active = ? ORDER BY id DESC";
			$query = $this->db->query($sql, array($store_id, 1));
		}
		return $query->result_array();
	}

	public function inTransactions($id)
	{
		$user_id = $this->session->userdata('id');
        $user_data = $this->model_users->getUserData($user_id);

		if($id) {
			if($user_data['store_id']==0){
				$sql = "SELECT * FROM received_products where productId = ?";
				$query = $this->db->query($sql, array($id));
	        }
	        else{
				$sql = "SELECT * FROM received_products where productId = ? and store_id=?";
				$query = $this->db->query($sql, array($id, $user_data['store_id']));
			}
			
			return $query->result_array();
		}

        if($user_data['store_id']==0){
			$sql = "SELECT * FROM received_products ORDER BY productId DESC";
			$query = $this->db->query($sql);
        }
        else{
			$sql = "SELECT * FROM received_products where store_id=? ORDER BY productId DESC";
			$query = $this->db->query($sql, array($user_data['store_id']));
		}

		return $query->result_array();
	}

	public function outTransactions($id)
	{
		if($id) {
			$sql = "SELECT * FROM orders o, orders_item oi where oi.product_id=? AND o.id=oi.order_id ORDER BY o.id DESC";
			$query = $this->db->query($sql, array($id));
			return $query->result_array();
		}

        $user_id = $this->session->userdata('id');
        $user_data = $this->model_users->getUserData($user_id);
        if($user_data['store_id']==0){
			$sql = "SELECT * FROM orders o, orders_item oi where o.id=oi.order_id ORDER BY o.id DESC";
			$query = $this->db->query($sql);
        }
        else{
        	$sql = "SELECT * FROM orders o, orders_item oi where o.id=oi.order_id and o.store_id=? ORDER BY o.id DESC";
			$query = $this->db->query($sql, array($user_data['store_id']));
		}

		return $query->result_array();
	}

	public function createBkp($data)
	{
		if($data) {
			$insert = $this->db->insert('received_products', $data);
			return ($insert == true) ? true : false;
		}
	}

	public function create()
	{
		/*$user_id = $this->session->userdata('id');
		$bill_no = 'BILPR-'.strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 4));
    	$data = array(
    		'bill_no' => $bill_no,
    		'receivedDate' => $this->input->post('receivedDate'),
    		'store' => $this->input->post('store'),
    		'store' => $this->input->post('store'),
    		'store' => $this->input->post('store'),
    		'paid_status' => 2,
    		'user_id' => $user_id
    	);*/

//		$insert = $this->db->insert('orders', $data);
//		$order_id = $this->db->insert_id();


		$count_product = count($this->input->post('productId'));
    	for($x = 0; $x < $count_product; $x++) {
    		$row = array(
	    		'receivedDate' => $this->input->post('receivedDate'),
	    		'store_id' => $this->input->post('store'),
    			'productId' => $this->input->post('productId')[$x],
    			'refNumber' => $this->input->post('refNumber')[$x],
    			'qty' => $this->input->post('qty')[$x],
    			'defectedQty' => $this->input->post('defectedQty')[$x],
    			'expDate' => $this->input->post('expDate')[$x],
    			'cost' => $this->input->post('cost')[$x],
    			'price' => $this->input->post('price')[$x],
    			'description' => $this->input->post('description')[$x],
    			'active' => 1,
    		);

			$this->db->insert('received_products', $row);

            $this->model_products->updateQty($this->input->post('qty')[$x]-$this->input->post('defectedQty')[$x], $this->input->post('productId')[$x]);
            $this->model_products->updatePrice($this->input->post('price')[$x], $this->input->post('productId')[$x]);
/*            //save received_products
            $data = array(
    			'productId' => $this->input->post('product')[$x],
                'refNumber' => 'RF-1',//$this->input->post('refNumber'),
    			'qty' => $this->input->post('qty')[$x],
    			'rate' => $this->input->post('rate_value')[$x],
    			'price' => $this->input->post('amount_value')[$x],
    			'date' => '2020-05-12',
    			'active' => 1,
    		);
        	$create2 = $this->model_issued_products->create($data);

    		// now decrease the stock from the product
    		$product_data = $this->model_products->getProductData($this->input->post('product')[$x]);
    		$qty = (int) $product_data['qty'] - (int) $this->input->post('qty')[$x];

    		$update_product = array('qty' => $qty);


    		$this->model_products->update($update_product, $this->input->post('product')[$x]);*/
    	}

		return true;//($order_id) ? $order_id : false;
	}


	public function update($data, $id)
	{
		if($data && $id) {
			$this->db->where('id', $id);
			$update = $this->db->update('received_products', $data);
			return ($update == true) ? true : false;
		}
	}

	public function remove($id)
	{
		if($id) {
			$this->db->where('id', $id);
			$delete = $this->db->delete('received_products');
			return ($delete == true) ? true : false;
		}
	}

	public function countTotalReceivedProducts()
	{

        $user_id = $this->session->userdata('id');
        $user_data = $this->model_users->getUserData($user_id);
        if($user_data['store_id']==0){
			$sql = "SELECT * FROM received_products";
			$query = $this->db->query($sql);
        }
        else{
			$sql = "SELECT * FROM received_products WHERE store_id=?";
			$query = $this->db->query($sql, array($user_data['store_id']));
		}
		return $query->num_rows();
	}

}
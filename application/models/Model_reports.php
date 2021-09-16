<?php 

class Model_reports extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('model_products');
        $this->load->model('model_users');
	}

	/*getting the total months*/
	private function months()
	{
		return array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
	}

	/* getting the year of the orders */
	public function getOrderYear()
	{
		$user_id = $this->session->userdata('id');
        $user_data = $this->model_users->getUserData($user_id);
        if($user_data['store_id']==0){
			$sql = "SELECT * FROM orders WHERE paid_status = ?";
			$query = $this->db->query($sql, array(1));
        }
        else{
			$sql = "SELECT * FROM orders WHERE store_id=? AND paid_status = ?";
			$query = $this->db->query($sql, array($user_data['store_id'], 1));
		}

		$orders = $query->result_array();
		
		$return_data = array();
		foreach ($orders as $k => $v) {
			$date = date('Y', $v['date_time']);
			$return_data[] = $date;
		}

		$return_data = array_unique($return_data);

		return $return_data;
	}

	// getting the order reports based on the year and moths
	public function getOrderData($year)
	{	
		if($year) {
			$months = $this->months();
			
		$user_id = $this->session->userdata('id');
        $user_data = $this->model_users->getUserData($user_id);
        if($user_data['store_id']==0){
			$sql = "SELECT * FROM orders WHERE paid_status = ?";
			$query = $this->db->query($sql, array(1));
        }
        else{
        	$sql = "SELECT * FROM orders WHERE store_id=? AND paid_status = ?";
			$query = $this->db->query($sql, array($user_data['store_id'], 1));
		}

		$orders = $query->result_array();

			$final_data = array();
			foreach ($months as $month_k => $month_y) {
				$get_mon_year = $year.'-'.$month_y;	

				$final_data[$get_mon_year][] = '';
				foreach ($orders as $k => $v) {
					$month_year = date('Y-m', $v['date_time']);

					if($get_mon_year == $month_year) {
						$final_data[$get_mon_year][] = $v;
					}
				}
			}	


			return $final_data;
			
		}
	}

	// getting the order reports based on the year and moths
	public function getOrderDataByDate($fromDate, $toDate)
	{	
		$user_id = $this->session->userdata('id');
        $user_data = $this->model_users->getUserData($user_id);
        
		$product_orders_within_interval=[];
		if($fromDate==null && $toDate==null)
			return [];

			$products = $this->model_products->getProductData();
			foreach ($products as $product) {
			$report_one_row_data=['vat_charge'=>0, 'service_charge'=>0, 'net_amount'=>0, 'discount'=>0, 'gross_amount'=>0, 'qty'=>0];

			$sql = "SELECT * FROM orders_item WHERE product_id = ?";
			$query = $this->db->query($sql, array($product['id']));
			$product_order_items = $query->result_array();
			foreach ($product_order_items as $order_item) {
				if($user_data['store_id']==0){
					$sql = "SELECT * FROM orders WHERE paid_status = ? && id = ?";
					$query = $this->db->query($sql, array(1, $order_item['order_id']));
		        }
		        else{
			        $sql = "SELECT * FROM orders WHERE store_id=? AND paid_status = ? && id = ?";
					$query = $this->db->query($sql, array($user_data['store_id'], 1, $order_item['order_id']));
				}

				$product_orders = $query->result_array();

			$qty_added=false;
			foreach ($product_orders as $order) {
				if($order['date_time'] < strtotime($fromDate) || $order['date_time'] >= strtotime($toDate))
					continue;

				if(!$qty_added){
					$report_one_row_data['qty'] += $order_item['qty'];
					$qty_added=true;
				}
				$report_one_row_data['gross_amount'] += $order['gross_amount'];
				$report_one_row_data['vat_charge'] += $order['vat_charge'];
				$report_one_row_data['service_charge'] += $order['service_charge'];
				$report_one_row_data['discount'] += $order['discount']!=""? $order['discount']: 0;
				$report_one_row_data['net_amount'] += $order['net_amount'];
			}
			}
			$product_orders_within_interval[$product['name']]=$report_one_row_data;			
		}
		return $product_orders_within_interval;
	}
}
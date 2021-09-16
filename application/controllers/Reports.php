<?php  

defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends Admin_Controller 
{	
	public function __construct()
	{
		parent::__construct();
		$this->data['page_title'] = 'Stores';
		$this->load->model('model_reports');
	}

	/* 
    * It redirects to the report page
    * and based on the year, all the orders data are fetch from the database.
    */
	public function index()
	{
		if(!in_array('viewReports', $this->permission)) {
            redirect('dashboard', 'refresh');
        }
		
		$today_year = date('Y');

		if($this->input->post('select_year')) {
			$today_year = $this->input->post('select_year');
		}
		
		$fromDate = $this->input->post('fromDate');
		$toDate = $this->input->post('toDate');
		if($fromDate==null && $toDate==null){
			$fromDate=date('Y-m-d');
			$toDate=date('Y-m-d', strtotime('+1 day'));
		}
		$parking_data = $this->model_reports->getOrderData($today_year);
		$order_data_by_date = $this->model_reports->getOrderDataByDate($fromDate, $toDate);

		$this->data['report_years'] = $this->model_reports->getOrderYear();
		

		$final_gross_amount_data = array();
		foreach ($parking_data as $k => $v) {
			
			if(count($v) > 1) {
				$total_gross_amount_earned = array();
				$total_net_amount_earned = array();
				foreach ($v as $k2 => $v2) {
					if($v2) {
						$total_gross_amount_earned[] = $v2['gross_amount'];						
						$total_net_amount_earned[] = $v2['net_amount'];						
					}
				}
				$final_gross_amount_data[$k] = ['total_gross_amount'=>array_sum($total_gross_amount_earned), 'total_net_amount'=>array_sum($total_net_amount_earned)];	
			}
			else {
				$final_gross_amount_data[$k] = ['total_gross_amount'=>0, 'total_net_amount'=>0];	
			}
			
		}
		
		$this->data['selected_year'] = $today_year;
		$this->data['company_currency'] = $this->company_currency();
		$this->data['results'] = $final_gross_amount_data;
		$this->data['order_data_by_date'] = $order_data_by_date;
		$this->data['fromDate'] = $fromDate;
		$this->data['toDate'] = $toDate;

		$this->render_template('reports/index', $this->data);
	}
}	